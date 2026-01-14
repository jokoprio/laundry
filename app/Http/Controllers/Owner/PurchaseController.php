<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\MasterData\Supplier;
use App\Models\PurchaseOrder;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = PurchaseOrder::where('tenant_id', Auth::user()->tenant_id)
            ->with(['supplier', 'items.inventoryItem'])
            ->latest()
            ->paginate(10);
        return view('owner.purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::where('tenant_id', Auth::user()->tenant_id)->get();
        $items = InventoryItem::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('owner.purchases.create', compact('suppliers', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.cost' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,debt,installment',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $total_amount = 0;
                $items_data = [];

                foreach ($request->items as $item) {
                    $subtotal = $item['qty'] * $item['cost'];
                    $total_amount += $subtotal;
                    $items_data[] = $item;
                }

                // Calculate payment amounts based on method
                $paymentMethod = $request->payment_method ?? 'cash';
                $paidAmount = $paymentMethod === 'cash' ? $total_amount : ($request->paid_amount ?? 0);
                $remainingAmount = $total_amount - $paidAmount;

                // 1. Create Purchase Order
                $purchase = PurchaseOrder::create([
                    'tenant_id' => Auth::user()->tenant_id,
                    'supplier_id' => $request->supplier_id,
                    'total_amount' => $total_amount,
                    'payment_method' => $paymentMethod,
                    'paid_amount' => $paidAmount,
                    'remaining_amount' => $remainingAmount,
                    'payment_status' => $remainingAmount > 0 ? ($paidAmount > 0 ? 'partial' : 'unpaid') : 'paid',
                    'due_date' => $request->due_date,
                    'status' => 'received',
                ]);

                // 2. Process Items & Update Inventory
                foreach ($items_data as $data) {
                    $purchase->items()->create([
                        'inventory_item_id' => $data['inventory_item_id'],
                        'qty' => $data['qty'],
                        'cost' => $data['cost'],
                    ]);

                    $invItem = InventoryItem::find($data['inventory_item_id']);

                    // Calc Weighted Average Cost
                    $oldStock = $invItem->stock;
                    $oldCost = $invItem->avg_cost;
                    $newQty = $data['qty'];
                    $newCost = $data['cost'];

                    if (($oldStock + $newQty) > 0) {
                        $newAvgCost = (($oldStock * $oldCost) + ($newQty * $newCost)) / ($oldStock + $newQty);
                        $invItem->avg_cost = $newAvgCost;
                    }

                    $invItem->stock += $newQty;
                    $invItem->save();
                }

                // 3. Record as Expense (only if paid amount > 0)
                if ($paidAmount > 0) {
                    Expense::create([
                        'tenant_id' => Auth::user()->tenant_id,
                        'category' => 'Belanja Bahan',
                        'amount' => $paidAmount,
                        'date' => now(),
                        'description' => 'Pembelian bahan dari ' . Supplier::find($request->supplier_id)->name . ' (' . ucfirst($paymentMethod) . ')',
                    ]);
                }
            });

            return redirect()->route('owner.purchases.index')->with('success', 'Pembelian berhasil dicatat dan stok telah diperbarui.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal mencatat pembelian: ' . $e->getMessage());
        }
    }

    public function destroy(PurchaseOrder $purchase)
    {
        if ($purchase->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        try {
            DB::transaction(function () use ($purchase) {
                // Reverse stock changes
                foreach ($purchase->items as $item) {
                    $invItem = $item->inventoryItem;

                    // Reduce stock
                    $invItem->stock -= $item->qty;

                    // Recalculate average cost (simplified approach)
                    // Note: This is not perfect but practical
                    if ($invItem->stock > 0) {
                        // Keep current avg_cost as we can't perfectly reverse it
                        // In production, you might want to track cost history
                    } else {
                        $invItem->avg_cost = 0;
                    }

                    $invItem->save();
                }

                // Delete related expense if exists
                Expense::where('tenant_id', $purchase->tenant_id)
                    ->where('category', 'Belanja Bahan')
                    ->where('amount', $purchase->paid_amount)
                    ->where('date', $purchase->created_at->toDateString())
                    ->delete();

                // Delete purchase (cascade will delete items and payments)
                $purchase->delete();
            });

            return back()->with('success', 'Pembelian berhasil dihapus dan stok telah disesuaikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pembelian: ' . $e->getMessage());
        }
    }

    public function edit(PurchaseOrder $purchase)
    {
        if ($purchase->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        $suppliers = Supplier::where('tenant_id', Auth::user()->tenant_id)->get();
        $items = InventoryItem::where('tenant_id', Auth::user()->tenant_id)->get();

        return view('owner.purchases.edit', compact('purchase', 'suppliers', 'items'));
    }

    public function update(Request $request, PurchaseOrder $purchase)
    {
        if ($purchase->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.cost' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,debt,installment',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        try {
            DB::transaction(function () use ($request, $purchase) {
                // 1. Reverse old stock changes
                foreach ($purchase->items as $oldItem) {
                    $invItem = $oldItem->inventoryItem;
                    $invItem->stock -= $oldItem->qty;
                    $invItem->save();
                }

                // 2. Delete old items
                $purchase->items()->delete();

                // 3. Calculate new totals
                $total_amount = 0;
                $items_data = [];

                foreach ($request->items as $item) {
                    $subtotal = $item['qty'] * $item['cost'];
                    $total_amount += $subtotal;
                    $items_data[] = $item;
                }

                // Calculate payment amounts
                $paymentMethod = $request->payment_method ?? 'cash';
                $paidAmount = $paymentMethod === 'cash' ? $total_amount : ($request->paid_amount ?? 0);
                $remainingAmount = $total_amount - $paidAmount;

                // 4. Update purchase
                $purchase->update([
                    'supplier_id' => $request->supplier_id,
                    'total_amount' => $total_amount,
                    'payment_method' => $paymentMethod,
                    'paid_amount' => $paidAmount,
                    'remaining_amount' => $remainingAmount,
                    'payment_status' => $remainingAmount > 0 ? ($paidAmount > 0 ? 'partial' : 'unpaid') : 'paid',
                    'due_date' => $request->due_date,
                ]);

                // 5. Add new items and update stock
                foreach ($items_data as $data) {
                    $purchase->items()->create([
                        'inventory_item_id' => $data['inventory_item_id'],
                        'qty' => $data['qty'],
                        'cost' => $data['cost'],
                    ]);

                    $invItem = InventoryItem::find($data['inventory_item_id']);

                    // Recalculate weighted average cost
                    $oldStock = $invItem->stock;
                    $oldCost = $invItem->avg_cost;
                    $newQty = $data['qty'];
                    $newCost = $data['cost'];

                    if (($oldStock + $newQty) > 0) {
                        $newAvgCost = (($oldStock * $oldCost) + ($newQty * $newCost)) / ($oldStock + $newQty);
                        $invItem->avg_cost = $newAvgCost;
                    }

                    $invItem->stock += $newQty;
                    $invItem->save();
                }
            });

            return redirect()->route('owner.purchases.index')->with('success', 'Pembelian berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui pembelian: ' . $e->getMessage());
        }
    }
}
