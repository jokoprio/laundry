<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of admin users.
     */
    public function index()
    {
        $users = User::where('role', '!=', 'owner')
            ->where('role', '!=', 'cashier')
            ->whereNull('tenant_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new admin user.
     */
    public function create()
    {
        $availablePermissions = $this->getAvailablePermissions();
        return view('admin.users.form', compact('availablePermissions'));
    }

    /**
     * Store a newly created admin user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['super_admin', 'admin'])],
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
            'tenant_id' => null,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin user berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified admin user.
     */
    public function edit(User $user)
    {
        // Prevent editing owner users
        if ($user->role === 'owner' || $user->tenant_id !== null) {
            abort(403, 'Tidak dapat mengedit user ini.');
        }

        $availablePermissions = $this->getAvailablePermissions();
        return view('admin.users.form', compact('user', 'availablePermissions'));
    }

    /**
     * Update the specified admin user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Prevent editing owner users
        if ($user->role === 'owner' || $user->tenant_id !== null) {
            abort(403, 'Tidak dapat mengedit user ini.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['super_admin', 'admin'])],
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

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin user berhasil diperbarui.');
    }

    /**
     * Remove the specified admin user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting owner users or self
        if ($user->role === 'owner' || $user->tenant_id !== null) {
            abort(403, 'Tidak dapat menghapus user ini.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->withErrors(['error' => 'Tidak dapat menghapus akun Anda sendiri.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin user berhasil dihapus.');
    }

    /**
     * Get available permissions for admin users
     */
    private function getAvailablePermissions()
    {
        return [
            'Manajemen Tenant' => [
                'view_tenants' => 'Lihat Daftar Tenant',
                'create_tenants' => 'Tambah Tenant Baru',
                'edit_tenants' => 'Edit Data Tenant',
                'delete_tenants' => 'Hapus Tenant',
            ],
            'Manajemen Paket' => [
                'view_packages' => 'Lihat Daftar Paket',
                'create_packages' => 'Tambah Paket Baru',
                'edit_packages' => 'Edit Paket',
                'delete_packages' => 'Hapus Paket',
            ],
            'Manajemen Kupon' => [
                'view_coupons' => 'Lihat Daftar Kupon',
                'create_coupons' => 'Tambah Kupon Baru',
                'edit_coupons' => 'Edit Kupon',
                'delete_coupons' => 'Hapus Kupon',
            ],
            'Manajemen Admin' => [
                'view_admin_users' => 'Lihat Daftar Admin',
                'create_admin_users' => 'Tambah Admin Baru',
                'edit_admin_users' => 'Edit Admin',
                'delete_admin_users' => 'Hapus Admin',
            ],
            'Laporan' => [
                'view_revenue_reports' => 'Lihat Laporan Pendapatan',
                'export_reports' => 'Export Laporan',
            ],
        ];
    }
}
