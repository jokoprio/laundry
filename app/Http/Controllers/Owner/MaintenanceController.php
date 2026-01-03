<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\MasterData\Customer;
use App\Models\InventoryItem;
use App\Models\StockMutation;
use App\Models\MasterData\Supplier;
use App\Models\PurchaseOrder;
use App\Models\MasterData\Employee;
use App\Models\User;
use App\Models\Payroll;
use App\Models\Expense;
use App\Models\Service;
use App\Models\MembershipLevel;
use App\Models\PointsHistory;
use App\Models\ProductionAssignment;
use App\Models\CustomerBalanceHistory;

class MaintenanceController extends Controller
{
    public function index()
    {
        return view('owner.maintenance.index');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'confirmation' => 'required|string|in:REKONFIRMASI',
        ], [
            'confirmation.in' => 'Teks konfirmasi harus bertuliskan REKONFIRMASI.',
        ]);

        $categories = $request->categories;
        $tenantId = Auth::user()->tenant_id;
        $currentUser = Auth::user();

        try {
            DB::beginTransaction();

            // Define deletion order based on foreign key dependencies
            // Child tables must be deleted before parent tables
            $deletionOrder = [
                'loyalty',           // Reset points (no FK constraints)
                'stock_mutations',   // Depends on: inventory_items, branches
                'payroll',           // Depends on: employees
                'expenses',          // Independent
                'transactions',      // Depends on: services, customers (has many children)
                'purchases',         // Depends on: suppliers (has purchase_order_items child)
                'services',          // Depends on: nothing (has service_materials, transaction_items children)
                'membership_levels', // Depends on: nothing (referenced by customers)
                'inventory',         // Depends on: nothing (has service_materials, purchase_order_items, stock_mutations children)
                'employees',         // Depends on: users (has payroll, production_assignments children)
                'suppliers',         // Depends on: nothing (has purchase_orders child)
                'customers',         // Depends on: membership_levels (has many children)
                'users',             // Depends on: nothing (referenced by employees)
                'branches',          // Depends on: nothing (referenced by many tables)
            ];

            // Filter to only selected categories and maintain order
            $orderedCategories = array_values(array_intersect($deletionOrder, $categories));

            foreach ($orderedCategories as $category) {
                switch ($category) {
                    case 'branches':
                        // Delete branches (will set null on related records due to onDelete('set null'))
                        $count = Branch::where('tenant_id', $tenantId)->delete();
                        \Log::info("Deleted $count branches");
                        break;

                    case 'transactions':
                        // Delete all transaction dependencies first
                        ProductionAssignment::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();
                        PointsHistory::where('tenant_id', $tenantId)->delete();
                        CustomerBalanceHistory::where('tenant_id', $tenantId)->delete();
                        // TransactionItems will cascade automatically

                        $count = Transaction::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();
                        \Log::info("Deleted $count transactions and all dependencies");
                        break;

                    case 'customers':
                        // Delete customer dependencies (some have cascade, some don't)
                        CustomerBalanceHistory::where('tenant_id', $tenantId)->delete();
                        PointsHistory::where('tenant_id', $tenantId)->delete();
                        DB::table('customer_coupons')->where('tenant_id', $tenantId)->delete();

                        $count = Customer::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();
                        \Log::info("Deleted $count customers and all dependencies");
                        break;

                    case 'inventory':
                        // Delete inventory dependencies
                        DB::table('service_materials')
                            ->whereIn('inventory_item_id', function ($query) use ($tenantId) {
                                $query->select('id')
                                    ->from('inventory_items')
                                    ->where('tenant_id', $tenantId);
                            })
                            ->delete();

                        DB::table('purchase_order_items')
                            ->whereIn('inventory_item_id', function ($query) use ($tenantId) {
                                $query->select('id')
                                    ->from('inventory_items')
                                    ->where('tenant_id', $tenantId);
                            })
                            ->delete();

                        StockMutation::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();

                        $count = InventoryItem::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();
                        \Log::info("Deleted $count inventory items and all dependencies");
                        break;

                    case 'stock_mutations':
                        $count = StockMutation::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();
                        \Log::info("Deleted $count stock mutations");
                        break;

                    case 'suppliers':
                        // Delete purchase orders first (and their items will cascade)
                        PurchaseOrder::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();

                        $count = Supplier::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();
                        \Log::info("Deleted $count suppliers and purchase orders");
                        break;

                    case 'purchases':
                        // Purchase order items will cascade
                        $count = PurchaseOrder::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();
                        \Log::info("Deleted $count purchase orders");
                        break;

                    case 'employees':
                        // Delete employee dependencies
                        Payroll::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();
                        ProductionAssignment::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();

                        $count = Employee::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();
                        \Log::info("Deleted $count employees and all dependencies");
                        break;

                    case 'users':
                        $count = User::withoutGlobalScope('branch')->where('tenant_id', $tenantId)
                            ->where('id', '!=', $currentUser->id)
                            ->delete();
                        \Log::info("Deleted $count users");
                        break;

                    case 'payroll':
                        $count = Payroll::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();
                        \Log::info("Deleted $count payroll records");
                        break;

                    case 'expenses':
                        $count = Expense::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();
                        \Log::info("Deleted $count expenses");
                        break;

                    case 'services':
                        // Delete service dependencies
                        DB::table('service_materials')
                            ->whereIn('service_id', function ($query) use ($tenantId) {
                                $query->select('id')
                                    ->from('services')
                                    ->where('tenant_id', $tenantId);
                            })
                            ->delete();

                        // Note: transaction_items references services but doesn't cascade
                        // If transactions exist, this will fail - which is correct behavior

                        $count = Service::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();
                        \Log::info("Deleted $count services and materials");
                        break;

                    case 'membership_levels':
                        // Note: customers reference membership_levels
                        // If customers exist with this level, this will fail - which is correct
                        $count = MembershipLevel::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->delete();
                        \Log::info("Deleted $count membership levels");
                        break;

                    case 'loyalty':
                        PointsHistory::where('tenant_id', $tenantId)->delete();
                        Customer::withoutGlobalScope('branch')->where('tenant_id', $tenantId)->update(['points' => 0]);
                        \Log::info("Reset loyalty points");
                        break;
                }
            }

            DB::commit();
            \Log::info('Maintenance Reset Committed Successfully');
            return redirect()->back()->with('success', 'Data terpilih berhasil direset.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Maintenance Reset Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Provide user-friendly error message with dependency hints
            $errorMsg = $e->getMessage();
            $hint = '';

            if (str_contains($errorMsg, 'foreign key constraint') || str_contains($errorMsg, 'FOREIGN KEY')) {
                $hint = ' PETUNJUK: Beberapa data memiliki ketergantungan. Coba hapus dalam urutan ini: 1) Riwayat Transaksi, 2) Pelanggan, 3) Layanan & Harga, 4) Data Staff, 5) Stok Barang, 6) Supplier, 7) Data Cabang.';
            }

            return redirect()->back()->with('error', 'Gagal mereset data: ' . $errorMsg . $hint);
        }
    }
}
