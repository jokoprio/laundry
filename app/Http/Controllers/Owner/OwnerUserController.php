<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OwnerUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('tenant_id', auth()->user()->tenant_id)
            ->where('id', '!=', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('owner.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $availableRoles = [
            'cashier' => 'Kasir / Staff',
            // Future roles can be added here
        ];

        $availablePermissions = $this->getAvailablePermissions();

        return view('owner.users.form', compact('availableRoles', 'availablePermissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['cashier'])], // Limit to cashier for now
            'permissions' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'permissions' => $validated['permissions'] ?? [],
            'is_active' => $request->has('is_active'),
            'tenant_id' => auth()->user()->tenant_id,
        ]);

        return redirect()->route('owner.users.index')
            ->with('success', 'Staff berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Check ownership
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $availableRoles = [
            'cashier' => 'Kasir / Staff',
        ];

        $availablePermissions = $this->getAvailablePermissions();

        return view('owner.users.form', compact('user', 'availableRoles', 'availablePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['cashier'])],
            'permissions' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->role = $validated['role'];
        $user->permissions = $validated['permissions'] ?? [];
        $user->is_active = $request->has('is_active');
        $user->save();

        return redirect()->route('owner.users.index')
            ->with('success', 'Staff berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus akun sendiri.']);
        }

        $user->delete();

        return redirect()->route('owner.users.index')
            ->with('success', 'Staff berhasil dihapus.');
    }

    private function getAvailablePermissions()
    {
        // Define permissions tailored for owner's staff (Cashier/Employee)
        return [
            'Transaksi' => [
                'view_transactions' => 'Lihat Transaksi',
                'create_transactions' => 'Buat Transaksi Baru',
                'edit_transactions' => 'Edit Transaksi', // Maybe restriction needed?
            ],
            'Pelanggan' => [
                'view_customers' => 'Lihat Data Pelanggan',
                'create_customers' => 'Tambah Pelanggan',
                'edit_customers' => 'Edit Pelanggan',
            ],
            'Layanan & Inventaris' => [
                'view_services' => 'Lihat Layanan',
                'view_inventory' => 'Lihat Inventaris',
                'edit_inventory' => 'Update Stok',
            ],
            'Laporan' => [
                'view_reports' => 'Lihat Laporan Harian',
            ],
        ];
    }
}
