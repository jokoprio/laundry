<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InventoryItem;
use App\Models\Transaction; // Assuming we use sales later
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->tenant_id) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Data Integrity Error: No Tenant assigned. Please register a new account.']);
        }

        $tenantId = $user->tenant_id;
        $tenant = Auth::user()->tenant;

        // Date Filter
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Basic Stats
        $todaySales = Transaction::where('tenant_id', $tenantId)
            ->whereDate('created_at', Carbon::today())
            ->sum('total_price');

        $monthSales = Transaction::where('tenant_id', $tenantId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_price');

        $stats = [
            'low_stock_count' => InventoryItem::where('tenant_id', $tenantId)
                ->where('stock', '<=', 10)
                ->count(),
            'today_sales' => $todaySales,
            'month_sales' => $monthSales,
        ];

        // Chart Data (Daily Revenue)
        $revenueData = Transaction::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d');
            });

        // Fill missing days
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        $chartLabels = [];
        $chartData = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d M');
            $chartData[] = isset($revenueData[$formattedDate])
                ? $revenueData[$formattedDate]->sum('total_price')
                : 0;
        }

        // Top Services
        $topServices = \App\Models\TransactionItem::select('services.name', \Illuminate\Support\Facades\DB::raw('SUM(transaction_items.qty * transaction_items.price) as total_revenue'))
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('services', 'transaction_items.service_id', '=', 'services.id')
            ->where('transactions.tenant_id', $tenantId)
            ->whereBetween('transactions.created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        $lowStockItems = InventoryItem::where('tenant_id', $tenantId)
            ->where('stock', '<=', 10)
            ->take(5)
            ->get();

        return view('owner.dashboard', compact('stats', 'lowStockItems', 'tenant', 'startDate', 'endDate', 'chartLabels', 'chartData', 'topServices'));
    }
}
