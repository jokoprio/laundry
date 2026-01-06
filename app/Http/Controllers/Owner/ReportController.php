<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Payroll;
use App\Models\PurchaseOrder; // Assuming Model Exists
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function finance(Request $request)
    {
        $tenant_id = Auth::user()->tenant_id;
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        // Profit & Loss Data
        $revenue = Transaction::where('tenant_id', $tenant_id)
            ->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])
            ->whereIn('status', ['done', 'processing']) // Revenue recognized? Let's say done/processing.
            ->sum('total_price');

        $cogs = Transaction::where('tenant_id', $tenant_id)
            ->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])
            ->whereIn('status', ['done', 'processing'])
            ->sum('total_cogs');

        $salary_expenses = Payroll::where('tenant_id', $tenant_id)
            ->whereBetween('period_end', [$start_date, $end_date]) // Accrual basis usually on period end
            ->where('status', 'paid') // Only paid? Or accrued. Let's say paid for cash basis or just record based on payroll period. User said "Gaji Karyawan". 
            ->sum('total_amount');

        $operational_expenses = Expense::where('tenant_id', $tenant_id)
            ->whereBetween('date', [$start_date, $end_date])
            ->sum('amount');

        $net_profit = $revenue - $cogs - $salary_expenses - $operational_expenses;

        // Balance Sheet Data (Snapshot - All time)
        // Receivables: Unpaid transactions
        // Note: We need to handle partial payments. Logic: sum(total_price - amount_paid)
        // Using DB raw for efficiency or collection filtering.
        $receivables = Transaction::where('tenant_id', $tenant_id)
            ->where('payment_status', '!=', 'paid') // pending or partial
            ->get()
            ->sum(function ($t) {
                return $t->total_price - $t->amount_paid;
            });

        // Payables: Unpaid POs
        $payables = PurchaseOrder::where('tenant_id', $tenant_id)
            ->where('payment_status', '!=', 'paid')
            ->sum('total_amount'); // Assuming no partial PO tracking yet

        return view('owner.reports.finance', compact(
            'start_date',
            'end_date',
            'revenue',
            'cogs',
            'salary_expenses',
            'operational_expenses',
            'net_profit',
            'receivables',
            'payables'
        ));
    }
}
