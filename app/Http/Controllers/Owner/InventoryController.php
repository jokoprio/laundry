<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $items = InventoryItem::where('tenant_id', Auth::user()->tenant_id)->paginate(10);
        return view('owner.inventory.index', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'unit' => 'required', // ml, kg, pcs
            'stock' => 'required|numeric',
            'avg_cost' => 'required|numeric',
        ]);

        $data['tenant_id'] = Auth::user()->tenant_id;

        InventoryItem::create($data);
        return back()->with('success', 'Item added successfully.');
    }

    public function update(Request $request, InventoryItem $inventory) // Param: 'inventory' from Resource Route
    {
        // Security check
        if ($inventory->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        $data = $request->validate([
            'name' => 'required',
            'unit' => 'required',
            'stock' => 'required|numeric',
            'avg_cost' => 'required|numeric',
        ]);

        $inventory->update($data);
        return back()->with('success', 'Item updated successfully.');
    }

    public function destroy(InventoryItem $inventory)
    {
        if ($inventory->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        $inventory->delete();
        return back()->with('success', 'Item deleted successfully.');
    }
}
