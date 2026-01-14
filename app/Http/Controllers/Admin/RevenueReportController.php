<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RevenueReportController extends Controller
{
    public function __construct()
    {
        // Enforce permission check
        // Assuming 'view_revenue_reports' is the permission key
        // Super admin bypasses automatically if CheckPermission middleware is correct, 
        // but here we use the middleware directly or rely on route grouping.
        // Let's add specific permission middleware
        $this->middleware('permission:view_revenue_reports')->only(['index', 'export']);
    }

    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Get tenants created within range (New Subscriptions)
        // Since we don't have a payments table, we use new tenants as proxy
        $tenants = Tenant::with('subscriptionPackage')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay() // Use endOfDay to include the last day fully
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalRevenue = $tenants->getCollection()->sum(fn($t) => $t->subscriptionPackage->price ?? 0);

        // Chart Data (Group by Day)
        $chartData = $tenants->getCollection()->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        })->map(function ($dayTenants) {
            return $dayTenants->sum(fn($t) => $t->subscriptionPackage->price ?? 0);
        });

        // Fill missing days
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        $labels = [];
        $data = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $data[] = $chartData[$formattedDate] ?? 0;
        }

        return view('admin.revenue.index', compact('tenants', 'totalRevenue', 'startDate', 'endDate', 'labels', 'data'));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $tenants = Tenant::with('subscriptionPackage')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $tenants->sum(fn($t) => $t->subscriptionPackage->price ?? 0);

        $pdf = Pdf::loadView('admin.revenue.pdf', compact('tenants', 'totalRevenue', 'startDate', 'endDate'));
        return $pdf->download('laporan-pendapatan-' . $startDate . '-to-' . $endDate . '.pdf');
    }
}
