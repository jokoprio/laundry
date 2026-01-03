<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Expense; // Ensure Model created
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('tenant_id', Auth::user()->tenant_id)->latest('date')->get();
        return view('owner.expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Expense::create([
            'tenant_id' => Auth::user()->tenant_id,
            'category' => $request->category,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Expense added successfully.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->tenant_id !== Auth::user()->tenant_id)
            abort(403);
        $expense->delete();
        return redirect()->back()->with('success', 'Expense deleted.');
    }
}
