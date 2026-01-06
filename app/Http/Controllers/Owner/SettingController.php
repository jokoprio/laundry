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
        $tenant = Auth::user()->tenant;
        return view('owner.settings.index', compact('tenant'));
    }

    public function update(Request $request)
    {
        $tenant = Auth::user()->tenant;

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
            if ($tenant->logo) {
                Storage::disk('public')->delete($tenant->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $path;
        }

        $tenant->update($data);

        return redirect()->route('owner.settings.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
