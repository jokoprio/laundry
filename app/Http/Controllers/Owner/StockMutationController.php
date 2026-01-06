<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\InventoryItem;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMutationController extends Controller
{
    public function index()
    {
        $mutations = StockMutation::with(['inventoryItem', 'fromBranch', 'toBranch'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $branches = Branch::where('tenant_id', auth()->user()->tenant_id)->get();
        $inventoryItems = InventoryItem::where('tenant_id', auth()->user()->tenant_id)
            ->with('branch')
            ->get();

        return view('owner.stock_mutations.index', compact('mutations', 'branches', 'inventoryItems'));
    }

    public function create()
    {
        $branches = Branch::where('tenant_id', auth()->user()->tenant_id)->get();
        // Only show items that belong to the current context (if any) or all if in "Pusat"
        $inventoryItems = InventoryItem::all();

        return view('owner.stock_mutations.create', compact('branches', 'inventoryItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'from_branch_id' => 'nullable|exists:branches,id',
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'quantity' => 'required|numeric|min:0.01',
            'note' => 'nullable|string'
        ]);

        $item = InventoryItem::findOrFail($request->inventory_item_id);

        // Check stock availability if from_branch_id is set
        // In this simple multi-tenant setup, we assume stock is tracked PER item record.
        // If items are scoped by branch, moving means decreasing from one record and increasing another.

        DB::transaction(function () use ($request, $item) {
            // Find or create item record in the destination branch
            $destinationItem = InventoryItem::where('tenant_id', auth()->user()->tenant_id)
                ->where('branch_id', $request->to_branch_id)
                ->where('name', $item->name) // Assuming same name means same item
                ->first();

            if (!$destinationItem) {
                $destinationItem = $item->replicate();
                $destinationItem->branch_id = $request->to_branch_id;
                $destinationItem->stock = 0;
                $destinationItem->save();
            }

            // Deduct from source (if not from external/central)
            if ($item->stock < $request->quantity) {
                throw new \Exception("Stok tidak mencukupi di cabang asal.");
            }

            $item->decrement('stock', $request->quantity);
            $destinationItem->increment('stock', $request->quantity);

            StockMutation::create([
                'tenant_id' => auth()->user()->tenant_id,
                'inventory_item_id' => $item->id,
                'from_branch_id' => $request->from_branch_id,
                'to_branch_id' => $request->to_branch_id,
                'quantity' => $request->quantity,
                'type' => 'transfer',
                'status' => 'completed',
                'note' => $request->note
            ]);
        });

        return redirect()->route('owner.stock_mutations.index')->with('success', 'Mutasi stok berhasil dilakukan.');
    }
}
