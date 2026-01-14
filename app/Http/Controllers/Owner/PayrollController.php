<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Employee;
use App\Models\Payroll;
use App\Models\ProductionAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF; // Assuming a PDF wrapper exists or we'll just use a view for print

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::where('tenant_id', Auth::user()->tenant_id)
            ->with('employee')
            ->latest('period_end')
            ->paginate(10);
        return view('owner.payroll.index', compact('payrolls'));
    }

    public function create()
    {
        $employees = Employee::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('owner.payroll.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            // 'days_worked' optional for daily/monthly overrides
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        $base_amount = 0;
        $total_amount = 0;
        $details = [];

        // Logic based on salary type
        if ($employee->salary_type === 'monthly') {
            $base_amount = $employee->base_salary;
            $details['type'] = 'Monthly';
            $details['gross_salary'] = $base_amount;
        } elseif ($employee->salary_type === 'daily') {
            $days_worked = $request->days_worked ?? 26; // Default to 26 days if not specified
            $base_amount = $employee->base_salary * $days_worked;
            $details['type'] = 'Daily';
            $details['days_worked'] = $days_worked;
            $details['daily_rate'] = $employee->base_salary;
        } elseif (in_array($employee->salary_type, ['borongan_item', 'borongan_kg'])) {
            // Fetch production in period
            $production = ProductionAssignment::where('employee_id', $employee->id)
                ->whereBetween('created_at', [$request->period_start . ' 00:00:00', $request->period_end . ' 23:59:59'])
                ->get();

            $total_qty = $production->sum('qty');
            // Note: We might want to use the rate_snapshot from assignment, but for now let's use current rate or avg. 
            // Actually, if we stored rate_snapshot, we should use it. 
            // BUT, user requirement says "based on rate per unit". 
            // Ideally we sum (qty * rate_snapshot)

            $calculated_pay = 0;
            foreach ($production as $p) {
                // If rate_snapshot is 0 (old data), use current employee rate? 
                // Let's assume rate_snapshot is valid if we implement assignment correctly.
                // For now, let's use employee current rate to simulate if snapshot missing.
                $rate = $p->rate_snapshot > 0 ? $p->rate_snapshot : $employee->rate_per_unit;
                $calculated_pay += ($p->qty * $rate);
            }

            // Fallback if no assignments found but user wants to manually generate? 
            // For now rely on assignments.

            $base_amount = $calculated_pay;
            $details['type'] = 'Borongan';
            $details['total_qty'] = $total_qty;
            $details['assignments_count'] = $production->count();
        }

        $bonus = $request->bonus ?? 0;
        $deductions = $request->deductions ?? 0;
        $total_amount = $base_amount + $bonus - $deductions;

        Payroll::create([
            'tenant_id' => Auth::user()->tenant_id,
            'employee_id' => $employee->id,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'base_amount' => $base_amount,
            'bonus' => $bonus,
            'deductions' => $deductions,
            'total_amount' => $total_amount,
            'status' => 'draft', // User can confirm later
            'details' => $details,
        ]);

        return redirect()->route('owner.payroll.index')->with('success', 'Payroll generated successfully.');
    }

    public function show(Payroll $payroll)
    {
        if ($payroll->tenant_id !== Auth::user()->tenant_id)
            abort(403);
        return view('owner.payroll.show', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        // For changing status to paid
        if ($payroll->tenant_id !== Auth::user()->tenant_id)
            abort(403);

        $payroll->update(['status' => 'paid']);
        return back()->with('success', 'Marked as Paid.');
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->tenant_id !== Auth::user()->tenant_id)
            abort(403);
        $payroll->delete();
        return back()->with('success', 'Payroll record deleted.');
    }
}
