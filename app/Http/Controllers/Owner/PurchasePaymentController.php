<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchasePayment;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchasePaymentController extends Controller
{
    public function index()
    {
        $purchases = PurchaseOrder::where('tenant_id', Auth::user()->tenant_id)
            ->whereIn('payment_method', ['debt', 'installment'])
            ->with(['supplier', 'payments'])
            ->latest()
            ->paginate(10);

        return view('owner.purchase-payments.index', compact('purchases'));
    }

    public function show(PurchaseOrder $purchase)
    {
        if ($purchase->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        $purchase->load(['supplier', 'payments']);
        return view('owner.purchase-payments.show', compact('purchase'));
    }

    public function store(Request $request, PurchaseOrder $purchase)
    {
        if ($purchase->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        if ($request->amount > $purchase->remaining_amount) {
            return back()->with('error', 'Jumlah pembayaran melebihi sisa tagihan.');
        }

        try {
            DB::transaction(function () use ($request, $purchase) {
                // 1. Create Payment Record
                $payment = PurchasePayment::create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'purchase_order_id' => $purchase->id,
                    'tenant_id' => Auth::user()->tenant_id,
                    'amount' => $request->amount,
                    'payment_date' => $request->payment_date,
                    'notes' => $request->notes
                ]);

                // 2. Update Purchase Order
                $purchase->paid_amount = $purchase->paid_amount + $request->amount;
                $purchase->remaining_amount = $purchase->remaining_amount - $request->amount;

                if ($purchase->remaining_amount <= 0) {
                    $purchase->payment_status = 'paid';
                } else {
                    $purchase->payment_status = 'partial';
                }

                $purchase->save();

                // 3. Record as Expense
                Expense::create([
                    'tenant_id' => Auth::user()->tenant_id,
                    'category' => 'Bayar Hutang Bahan',
                    'amount' => $request->amount,
                    'date' => $request->payment_date,
                    'description' => 'Pembayaran ' . ($purchase->payment_method == 'debt' ? 'hutang' : 'cicilan') . ' ke ' . $purchase->supplier->name . ' (PO: ' . substr($purchase->id, 0, 8) . ')',
                ]);
            });

            return back()->with('success', 'Pembayaran berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }
}
