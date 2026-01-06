<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        $tenant = Auth::user()->tenant;
        return view('owner.settings.index', compact('tenant'));
=======
        $branchId = session('active_branch_id');
        if ($branchId) {
            $branch = \App\Models\Branch::where('tenant_id', Auth::user()->tenant_id)
                ->where('id', $branchId)
                ->firstOrFail();
            return view('owner.settings.index', [
                'entity' => $branch,
                'isBranch' => true
            ]);
        }

        $tenant = Auth::user()->tenant;
        return view('owner.settings.index', [
            'entity' => $tenant,
            'isBranch' => false
        ]);
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df
    }

    public function update(Request $request)
    {
<<<<<<< HEAD
        $tenant = Auth::user()->tenant;
=======
        $branchId = session('active_branch_id');
        $entity = $branchId
            ? \App\Models\Branch::where('tenant_id', Auth::user()->tenant_id)->where('id', $branchId)->firstOrFail()
            : Auth::user()->tenant;
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'receipt_footer' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'address', 'phone', 'receipt_footer']);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
<<<<<<< HEAD
            if ($tenant->logo) {
                Storage::disk('public')->delete($tenant->logo);
=======
            if ($entity->logo) {
                Storage::disk('public')->delete($entity->logo);
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df
            }
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $path;
        }

<<<<<<< HEAD
        $tenant->update($data);
=======
        $entity->update($data);
>>>>>>> 9513f9d9f392a3b2852b4daca22d48e1b98290df

        return redirect()->route('owner.settings.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
