<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Customer;
use App\Models\MembershipLevel;
use App\Models\CustomerBalanceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('tenant_id', Auth::user()->tenant_id)
            ->with('membershipLevel')
            ->paginate(10);
        $levels = MembershipLevel::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('owner.customers.index', compact('customers', 'levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('customers')->where(fn($query) => $query->where('tenant_id', Auth::user()->tenant_id))
            ],
            'address' => 'nullable|string',
            'membership_level_id' => 'nullable|exists:membership_levels,id',
        ]);

        Customer::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'membership_level_id' => $request->membership_level_id,
        ]);

        return back()->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function update(Request $request, Customer $customer)
    {
        if ($customer->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('customers')->where(fn($query) => $query->where('tenant_id', Auth::user()->tenant_id))->ignore($customer->id)
            ],
            'address' => 'nullable|string',
            'membership_level_id' => 'nullable|exists:membership_levels,id',
        ]);

        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'membership_level_id' => $request->membership_level_id,
        ]);

        return back()->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function topup(Request $request, Customer $customer)
    {
        if ($customer->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($customer, $request) {
            $balanceBefore = $customer->balance;
            $customer->increment('balance', $request->amount);

            CustomerBalanceHistory::create([
                'tenant_id' => Auth::user()->tenant_id,
                'customer_id' => $customer->id,
                'amount' => $request->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $customer->balance,
                'type' => 'topup',
                'description' => $request->description ?: 'Top-up saldo',
            ]);
        });

        return back()->with('success', 'Saldo berhasil ditambahkan.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->tenant_id !== Auth::user()->tenant_id)
            abort(403);
        $customer->delete();
        return back()->with('success', 'Pelanggan berhasil dihapus.');
    }
}
