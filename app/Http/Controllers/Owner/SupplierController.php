<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::where('tenant_id', Auth::user()->tenant_id)->paginate(10);
        return view('owner.suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Supplier::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(Request $request, Supplier $supplier)
    {
        if ($supplier->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $supplier->update($request->only(['name', 'phone', 'address']));

        return back()->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        if ($supplier->purchaseOrders()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus supplier yang sudah memiliki riwayat transaksi.');
        }

        $supplier->delete();
        return back()->with('success', 'Supplier berhasil dihapus.');
    }
}
