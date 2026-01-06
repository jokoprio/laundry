<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('subscriptionPackage')->get();
        return view('admin.tenants.index', compact('tenants'));
    }
    public function destroy(Request $request, Tenant $tenant)
    {
        // 1. Validation: Name Confirmation
        $request->validate([
            'confirm_name' => 'required|in:' . $tenant->name,
        ], [
            'confirm_name.in' => 'The entered business name does not match.',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($tenant) {
                // 2. Cascade Delete (Order matters if FKs exist, but here we do it safe locally)

                // Users
                \App\Models\User::where('tenant_id', $tenant->id)->delete();

                // Inventory & Production
                \App\Models\InventoryItem::where('tenant_id', $tenant->id)->delete();
                \App\Models\Service::where('tenant_id', $tenant->id)->delete();
                // Note: ServiceMaterial (pivot) usually deletes via cascade on Service delete if migration set correctly.
                // If not, we should rely on Model constraints or delete manually. 
                // Assuming standard cascade or pivot table behavior.

                // Transactions
                // Transactions might have items (TransactionItem), usually cascade on transaction delete.
                \App\Models\Transaction::where('tenant_id', $tenant->id)->delete();

                // Optional: Employees, Customers, Suppliers if scoped by tenant
                // \App\Models\Customer::where('tenant_id', $tenant->id)->delete(); 

                // Finally Delete Tenant
                $tenant->delete();
            });

            return back()->with('success', 'Tenant and all related data deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete tenant: ' . $e->getMessage()]);
        }
    }
}
