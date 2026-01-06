<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::where('tenant_id', auth()->user()->tenant_id)->get();
        $branchAddonPrice = SystemSetting::get('branch_addon_price', 50000);

        return view('owner.branches.index', compact('branches', 'branchAddonPrice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        // In a real scenario, this might involve a payment step.
        // For now, we create it and they will need to "activate" it or it starts as trial.
        Branch::create([
            'tenant_id' => auth()->user()->tenant_id,
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'status' => 'active',
            'expires_at' => now()->addDays(7), // Default 7 days trial for new branch
        ]);

        return redirect()->route('owner.branches.index')->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function update(Request $request, Branch $branch)
    {
        $this->authorizeOwner($branch);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        $branch->update($request->only(['name', 'address', 'phone', 'status']));

        return redirect()->route('owner.branches.index')->with('success', 'Informasi cabang berhasil diperbarui.');
    }

    public function destroy(Branch $branch)
    {
        $this->authorizeOwner($branch);
        $branch->delete();

        return redirect()->route('owner.branches.index')->with('success', 'Cabang berhasil dihapus.');
    }

    /**
     * Simulation of branch renewal/activation
     */
    public function activate(Request $request, Branch $branch)
    {
        $this->authorizeOwner($branch);

        // Add 30 days to expiration
        $currentExpiry = $branch->expires_at && $branch->expires_at->isFuture()
            ? $branch->expires_at
            : now();

        $branch->update([
            'expires_at' => $currentExpiry->addDays(30),
            'status' => 'active'
        ]);

        return redirect()->route('owner.branches.index')->with('success', 'Masa aktif cabang berhasil diperpanjang.');
    }

    /**
     * Switch active branch context (for Owner)
     */
    public function switch(Request $request)
    {
        $branchId = $request->branch_id;

        if ($branchId) {
            $branch = Branch::where('tenant_id', auth()->user()->tenant_id)->where('id', $branchId)->firstOrFail();
            session(['active_branch_id' => $branch->id, 'active_branch_name' => $branch->name]);
        } else {
            session()->forget(['active_branch_id', 'active_branch_name']);
        }

        return redirect()->back()->with('success', 'Konteks cabang berhasil diubah.');
    }

    private function authorizeOwner(Branch $branch)
    {
        if ($branch->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }
    }
}
