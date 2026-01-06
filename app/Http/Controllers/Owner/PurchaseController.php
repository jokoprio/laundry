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
            ->get();
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

                // 1. Create Purchase Order
                $purchase = PurchaseOrder::create([
                    'tenant_id' => Auth::user()->tenant_id,
                    'supplier_id' => $request->supplier_id,
                    'total_amount' => $total_amount,
                    'status' => 'received', // Auto-receive for simplicity in this flow
                    'payment_status' => 'paid',
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
                    // New Avg = ((OldQty * OldCost) + (NewQty * NewCost)) / (OldQty + NewQty)
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

                // 3. Record as Expense
                Expense::create([
                    'tenant_id' => Auth::user()->tenant_id,
                    'category' => 'Belanja Bahan',
                    'amount' => $total_amount,
                    'date' => now(),
                    'description' => 'Pembelian bahan dari ' . Supplier::find($request->supplier_id)->name,
                ]);
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

        // Note: Destroying a purchase doesn't revert stock in this simple implementation
        // to avoid complexity with avg_cost history. User should adjust stock manually if needed.
        $purchase->delete();
        return back()->with('success', 'Riwayat pembelian dihapus.');
    }
}
