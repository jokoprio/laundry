<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MembershipLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipLevelController extends Controller
{
    public function index()
    {
        $levels = MembershipLevel::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('owner.membership_levels.index', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'discount_percent' => 'required|integer|min:0|max:100',
            'min_points' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        MembershipLevel::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $request->name,
            'discount_percent' => $request->discount_percent,
            'min_points' => $request->min_points,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Level Member berhasil ditambahkan.');
    }

    public function update(Request $request, MembershipLevel $membershipLevel)
    {
        if ($membershipLevel->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'discount_percent' => 'required|integer|min:0|max:100',
            'min_points' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $membershipLevel->update($request->only(['name', 'discount_percent', 'min_points', 'description']));

        return back()->with('success', 'Level Member berhasil diperbarui.');
    }

    public function destroy(MembershipLevel $membershipLevel)
    {
        if ($membershipLevel->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        if ($membershipLevel->customers()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus level yang masih memiliki pelanggan.');
        }

        $membershipLevel->delete();
        return back()->with('success', 'Level Member berhasil dihapus.');
    }
}
