<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::all();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric',
            'max_uses' => 'nullable|integer',
            'expires_at' => 'nullable|date',
        ]);

        Coupon::create($data);
        return back()->with('success', 'Coupon created successfully.');
    }
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted successfully.');
    }
}
