<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::where('tenant_id', Auth::user()->tenant_id)->latest()->get();
        return view('owner.employees.index', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'salary_type' => 'required|in:daily,monthly,borongan_item,borongan_kg',
            'base_salary' => 'nullable|numeric|min:0',
            'rate_per_unit' => 'nullable|numeric|min:0',
        ]);

        Employee::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $request->name,
            'position' => $request->position,
            'phone' => $request->phone,
            'salary_type' => $request->salary_type,
            'base_salary' => $request->base_salary ?? 0,
            'rate_per_unit' => $request->rate_per_unit ?? 0,
            // 'user_id' can be linked later if they have login access
        ]);

        return redirect()->route('owner.employees.index')->with('success', 'Employee added successfully.');
    }

    public function update(Request $request, Employee $employee)
    {
        if ($employee->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'salary_type' => 'required|in:daily,monthly,borongan_item,borongan_kg',
            'base_salary' => 'nullable|numeric|min:0',
            'rate_per_unit' => 'nullable|numeric|min:0',
        ]);

        $employee->update([
            'name' => $request->name,
            'position' => $request->position,
            'phone' => $request->phone,
            'salary_type' => $request->salary_type,
            'base_salary' => $request->base_salary ?? 0,
            'rate_per_unit' => $request->rate_per_unit ?? 0,
        ]);

        return redirect()->route('owner.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        $employee->delete();
        return redirect()->route('owner.employees.index')->with('success', 'Employee deleted successfully.');
    }
}
