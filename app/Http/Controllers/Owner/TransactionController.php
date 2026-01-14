<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Customer;
use App\Models\PointsHistory;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\CustomerBalanceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('tenant_id', Auth::user()->tenant_id)
            ->with(['items.service', 'customer'])
            ->latest()
            ->paginate(10);
        return view('owner.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $services = Service::where('tenant_id', Auth::user()->tenant_id)->get();
        $customers = Customer::where('tenant_id', Auth::user()->tenant_id)->with('membershipLevel')->get();
        return view('owner.transactions.create', compact('services', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,balance',
            'payment_scheme' => 'required|in:lunas,dp,bayar_nanti',
            'dp_amount' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $total_price_raw = 0;
                $total_cogs = 0;
                $items_prepared = [];

                foreach ($request->items as $item) {
                    $service = Service::with('materials.inventoryItem')->findOrFail($item['service_id']);
                    $qty = (float) $item['qty'];
                    $item_price = $service->price * $qty;

                    $item_cogs = 0;
                    foreach ($service->materials as $material) {
                        $item_cogs += ($material->quantity * ($material->inventoryItem->avg_cost ?? 0)) * $qty;
                    }

                    $total_price_raw += $item_price;
                    $total_cogs += $item_cogs;

                    $items_prepared[] = [
                        'service' => $service,
                        'qty' => $qty,
                        'cogs' => $item_cogs,
                        'price' => $service->price
                    ];
                }

                // Handle Membership Discount
                $discount_amount = 0;
                $customer = null;
                if ($request->customer_id) {
                    $customer = Customer::with('membershipLevel')->find($request->customer_id);
                    if ($customer && $customer->membershipLevel) {
                        $discount_amount = ($total_price_raw * $customer->membershipLevel->discount_percent) / 100;
                    }
                }

                $final_total = $total_price_raw - $discount_amount;

                // Determine payment status and amount paid based on scheme
                $payment_scheme = $request->payment_scheme;
                $amount_paid = 0;
                $payment_status = 'pending';

                if ($payment_scheme === 'lunas') {
                    // Full payment upfront
                    $amount_paid = $final_total;
                    $payment_status = 'paid';
                } elseif ($payment_scheme === 'dp') {
                    // Down payment
                    $dp_amount = (float) $request->dp_amount;
                    if ($dp_amount <= 0 || $dp_amount >= $final_total) {
                        throw new \Exception('Jumlah DP harus lebih dari 0 dan kurang dari total.');
                    }
                    $amount_paid = $dp_amount;
                    $payment_status = 'partial';
                } elseif ($payment_scheme === 'bayar_nanti') {
                    // Pay later
                    $amount_paid = 0;
                    $payment_status = 'pending';
                }

                // Create Transaction
                $transaction = Transaction::create([
                    'tenant_id' => Auth::user()->tenant_id,
                    'customer_id' => $request->customer_id,
                    'total_price' => $final_total,
                    'total_cogs' => $total_cogs,
                    'status' => 'pending',
                    'payment_status' => $payment_status,
                    'payment_scheme' => $payment_scheme,
                    'amount_paid' => $amount_paid,
                ]);

                // Create Items & Deduct Stock
                foreach ($items_prepared as $data) {
                    $transaction->items()->create([
                        'service_id' => $data['service']->id,
                        'qty' => $data['qty'],
                        'price' => $data['price'],
                        'cogs' => $data['cogs'],
                    ]);

                    foreach ($data['service']->materials as $material) {
                        if ($material->inventoryItem) {
                            $material->inventoryItem->decrement('stock', $material->quantity * $data['qty']);
                        }
                    }
                }

                // Handle Balance Deduction (only for cash payment with lunas or dp)
                if ($request->payment_method === 'balance' && $amount_paid > 0) {
                    if (!$customer || $customer->balance < $amount_paid) {
                        throw new \Exception('Saldo pelanggan tidak mencukupi.');
                    }

                    $balanceBefore = $customer->balance;
                    $customer->decrement('balance', $amount_paid);

                    CustomerBalanceHistory::create([
                        'tenant_id' => Auth::user()->tenant_id,
                        'customer_id' => $customer->id,
                        'transaction_id' => $transaction->id,
                        'amount' => -$amount_paid,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $customer->balance,
                        'type' => 'payment',
                        'description' => 'Pembayaran Transaksi #' . substr($transaction->id, 0, 8),
                    ]);
                }

                // Assign points only if fully paid
                if ($payment_status === 'paid' && $customer) {
                    $this->assignPoints($transaction);
                }
            });

            return redirect()->route('owner.transactions.index')->with('success', 'Transaksi berhasil dibuat.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        if ($request->has('status')) {
            $transaction->update(['status' => $request->status]);
        }

        if ($request->has('payment_status') && $request->payment_status === 'paid') {
            if ($transaction->payment_status !== 'paid') {
                $transaction->update([
                    'payment_status' => 'paid',
                    'amount_paid' => $transaction->total_price,
                ]);

                if ($transaction->customer_id) {
                    $this->assignPoints($transaction);
                }
            }
        }

        return back()->with('success', 'Transaksi diperbarui.');
    }

    private function assignPoints(Transaction $transaction)
    {
        $points = floor($transaction->total_price / 10000);

        if ($points > 0) {
            DB::transaction(function () use ($transaction, $points) {
                $customer = Customer::find($transaction->customer_id);
                if ($customer) {
                    $customer->increment('points', $points);

                    PointsHistory::create([
                        'tenant_id' => $transaction->tenant_id,
                        'customer_id' => $transaction->customer_id,
                        'transaction_id' => $transaction->id,
                        'points' => $points,
                        'type' => 'earned',
                        'description' => 'Earned from Transaction #' . $transaction->id,
                    ]);
                }
            });
        }
    }

    public function printReceipt(Transaction $transaction)
    {
        if ($transaction->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        $transaction->load(['items.service', 'customer.membershipLevel']);
        $tenant = Auth::user()->tenant;

        // Using direct HTML view for browser print (better for thermal printers)
        return view('owner.transactions.receipt', compact('transaction', 'tenant'));
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->tenant_id !== Auth::user()->tenant_id)
            abort(403);
        $transaction->delete();
        return back()->with('success', 'Transaksi dihapus.');
    }
}
