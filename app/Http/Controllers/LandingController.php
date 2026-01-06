<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPackage;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LandingController extends Controller
{
    public function index()
    {
        $packages = SubscriptionPackage::all();
        return view('landing.index', compact('packages'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'coupon_code' => 'nullable|exists:coupons,code',
        ]);

        try {
            DB::beginTransaction();

            // 1. Determine Package (Default to Trial if no selection logic yet, or find 'Trial')
            // For now, let's assume we look for a 'Trial' package or create one if missing
            $package = SubscriptionPackage::where('name', 'Trial')->first();

            if (!$package) {
                // Fallback: Create a trial package if it doesn't exist (Self-healing or initial seed)
                $package = SubscriptionPackage::create([
                    'name' => 'Trial',
                    'price' => 0,
                    'duration_days' => 14,
                    'features' => ['basic_pos']
                ]);
            }

            $expiryDate = Carbon::now()->addDays($package->duration_days);

            // 2. Handle Coupon
            if ($request->coupon_code) {
                $coupon = Coupon::where('code', $request->coupon_code)->first();
                // Logic to apply coupon if we had paid registration. 
                // For now just increment usage
                if ($coupon) {
                    $coupon->increment('used_count');
                }
            }

            // 3. Create Tenant
            $tenant = Tenant::create([
                'name' => $request->business_name,
                'owner_name' => $request->owner_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => 'active',
                'subscription_package_id' => $package->id,
                'subscription_expires_at' => $expiryDate,
                'settings' => ['currency' => 'IDR', 'timezone' => 'Asia/Jakarta'],
            ]);

            // 4. Create User (Owner)
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $request->owner_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'owner',
            ]);

            DB::commit();

            // Auto-login (Optional)
            // auth()->login($user);

            return redirect()->route('landing.index')->with('success', 'Registration successful! Please login.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Registration failed. Please try again.'])->withInput();
        }
    }
}
