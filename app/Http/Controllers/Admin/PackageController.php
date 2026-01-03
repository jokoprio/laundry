<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = SubscriptionPackage::all();
        return view('admin.packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'max_users' => 'nullable|integer',
            'max_devices' => 'nullable|integer',
        ]);

        SubscriptionPackage::create($data);
        return back()->with('success', 'Package created successfully.');
    }

    public function edit(SubscriptionPackage $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, SubscriptionPackage $package)
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'max_users' => 'nullable|integer',
            'max_devices' => 'nullable|integer',
        ]);

        $package->update($data);
        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(SubscriptionPackage $package)
    {
        $package->delete();
        return back()->with('success', 'Package deleted successfully.');
    }
}
