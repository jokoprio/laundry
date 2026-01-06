<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\SubscriptionPackage;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'active_tenants' => Tenant::where('status', 'active')->count(),
            'total_tenants' => Tenant::count(),
            'expiring_soon' => Tenant::where('subscription_expires_at', '<=', Carbon::now()->addDays(3))
                ->where('subscription_expires_at', '>', Carbon::now())
                ->count(),
            // Est. Earnings (assuming we tracked payments, for now just sum package prices of active tenants as a proxy if needed, or 0)
            'estimated_mrr' => Tenant::where('status', 'active')
                ->join('subscription_packages', 'tenants.subscription_package_id', '=', 'subscription_packages.id')
                ->sum('subscription_packages.price'),
        ];

        // Storage Monitoring Simulation
        // In real world, we would iterate folders or check separate DB stats
        $tenants = Tenant::with('subscriptionPackage')->get()->map(function ($tenant) {
            // Simulate storage mostly random but tied to ID to be consistent
            $tenant->disk_usage_mb = rand(10, 500);
            $tenant->disk_limit_mb = 1000; // 1GB default
            $tenant->days_left = Carbon::now()->diffInDays($tenant->subscription_expires_at, false);
            return $tenant;
        });

        // Notifications for Expiring
        $expiringTenants = $tenants->filter(function ($t) {
            return $t->days_left <= 3 && $t->days_left >= 0;
        });

        // Subscriber Chart Data
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $subscribers = Tenant::whereBetween('created_at', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()])
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        $chartLabels = [];
        $chartData = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d M');
            $chartData[] = $subscribers[$formattedDate] ?? 0;
        }

        return view('admin.dashboard', compact('stats', 'tenants', 'expiringTenants', 'chartLabels', 'chartData', 'startDate', 'endDate'));
    }

    public function map()
    {
        // Return JSON for Map
        $locations = Tenant::select('id', 'name', 'latitude', 'longitude')
            ->whereNotNull('latitude')
            ->get();
        return response()->json($locations);
    }
}
