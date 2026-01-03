<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;

Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::post('/register', [LandingController::class, 'register'])->name('landing.register');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin_access'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/map-data', [App\Http\Controllers\Admin\DashboardController::class, 'map'])->name('map');

    // Reports
    Route::get('/reports/revenue', [App\Http\Controllers\Admin\RevenueReportController::class, 'index'])->name('reports.revenue');
    Route::get('/reports/revenue/export', [App\Http\Controllers\Admin\RevenueReportController::class, 'export'])->name('reports.export');

    Route::resource('users', App\Http\Controllers\Admin\AdminUserController::class);

    Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);
    Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);
    Route::resource('tenants', App\Http\Controllers\Admin\TenantController::class);

    // System Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Profile & Password
    Route::get('/profile/change-password', [App\Http\Controllers\Admin\ProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::put('/profile/update-password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.update-password');
});

Route::prefix('owner')->name('owner.')->middleware(['auth', 'owner', 'check_subscription'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('inventory', App\Http\Controllers\Owner\InventoryController::class);
    Route::resource('services', App\Http\Controllers\Owner\ServiceController::class);
    Route::resource('employees', App\Http\Controllers\Owner\EmployeeController::class);
    Route::resource('payroll', App\Http\Controllers\Owner\PayrollController::class);
    Route::resource('expenses', App\Http\Controllers\Owner\ExpenseController::class);
    Route::get('/reports/finance', [App\Http\Controllers\Owner\ReportController::class, 'finance'])->name('reports.finance');

    Route::resource('transactions', App\Http\Controllers\Owner\TransactionController::class);
    Route::get('transactions/{transaction}/receipt', [App\Http\Controllers\Owner\TransactionController::class, 'printReceipt'])->name('transactions.receipt');
    Route::resource('customers', App\Http\Controllers\Owner\CustomerController::class);
    Route::post('customers/{customer}/topup', [App\Http\Controllers\Owner\CustomerController::class, 'topup'])->name('customers.topup');
    Route::resource('suppliers', App\Http\Controllers\Owner\SupplierController::class);
    Route::resource('purchases', App\Http\Controllers\Owner\PurchaseController::class);
    Route::resource('membership-levels', App\Http\Controllers\Owner\MembershipLevelController::class);

    Route::get('/loyalty', [App\Http\Controllers\Owner\LoyaltyController::class, 'index'])->name('loyalty.index');
    Route::post('/loyalty/redeem', [App\Http\Controllers\Owner\LoyaltyController::class, 'redeem'])->name('loyalty.redeem');

    // Users (Staff) Management
    Route::resource('users', App\Http\Controllers\Owner\OwnerUserController::class);

    // Branch Management
    Route::resource('branches', App\Http\Controllers\Owner\BranchController::class);
    Route::post('branches/{branch}/activate', [App\Http\Controllers\Owner\BranchController::class, 'activate'])->name('branches.activate');
    Route::post('branches/switch', [App\Http\Controllers\Owner\BranchController::class, 'switch'])->name('branches.switch');
    Route::resource('stock-mutations', App\Http\Controllers\Owner\StockMutationController::class);

    // Maintenance (Data Reset)
    Route::get('/maintenance', [App\Http\Controllers\Owner\MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::post('/maintenance/reset', [App\Http\Controllers\Owner\MaintenanceController::class, 'reset'])->name('maintenance.reset');

    // Settings
    Route::get('/settings', [App\Http\Controllers\Owner\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\Owner\SettingController::class, 'update'])->name('settings.update');

    // Profile & Password
    Route::get('/profile/change-password', [App\Http\Controllers\Owner\ProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::put('/profile/update-password', [App\Http\Controllers\Owner\ProfileController::class, 'updatePassword'])->name('profile.update-password');
});
