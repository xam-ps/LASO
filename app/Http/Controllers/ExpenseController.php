<?php

namespace App\Http\Controllers;

use App\Models\CostType;
use App\Models\Expense;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company_names = DB::table('expenses')->select('supplier_name')->distinct()->pluck('supplier_name');

        $cost_types = CostType::all();

        return view('expense.create', ['supplier_list' => $company_names, 'cost_types' => $cost_types]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'billing_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'supplier_name' => 'required|string',
            'product_name' => 'required|string',
            'invoice_number' => 'required|string',
            'net' => 'required|decimal:0,2',
            'tax' => 'required|decimal:0,2',
            'gross' => 'required|decimal:0,2',
            'cost_type' => 'required|integer',
        ]);

        $expense = new Expense();
        $expense->billing_date = $validatedData['billing_date'];
        $expense->payment_date = $validatedData['payment_date'];
        $expense->supplier_name = $validatedData['supplier_name'];
        $expense->product_name = $validatedData['product_name'];
        $expense->invoice_number = $validatedData['invoice_number'];
        $expense->net = $validatedData['net'];
        $expense->tax = $validatedData['tax'];
        $expense->gross = $validatedData['gross'];
        $expense->cost_type_id = $validatedData['cost_type'];

        try {
            $expense->save();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) { // 1062 is the MySQL error code for a duplicate entry
                return redirect()->back()->withInput()->withErrors(['unique_column' => 'The value for Rechnungsnummer must be unique.']);
            }

            throw $e;
        }

        // Redirect to a page or route after successful submission
        return redirect()->route('dashboard.index')->with('success', 'Revenue created successfully.');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $company_names = DB::table('expenses')->select('supplier_name')->distinct()->pluck('supplier_name');

        $cost_types = CostType::all();

        return view('expense.edit', ['expense' => Expense::find($id), 'supplier_list' => $company_names, 'cost_types' => $cost_types]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'billing_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'supplier_name' => 'required|string',
            'product_name' => 'required|string',
            'invoice_number' => 'required|string',
            'net' => 'required|decimal:0,2',
            'tax' => 'required|decimal:0,2',
            'gross' => 'required|decimal:0,2',
            'cost_type' => 'required|integer',
        ]);

        $expense = Expense::find($id);
        $expense->billing_date = $validatedData['billing_date'];
        $expense->payment_date = $validatedData['payment_date'];
        $expense->supplier_name = $validatedData['supplier_name'];
        $expense->product_name = $validatedData['product_name'];
        $expense->invoice_number = $validatedData['invoice_number'];
        $expense->net = $validatedData['net'];
        $expense->tax = $validatedData['tax'];
        $expense->gross = $validatedData['gross'];
        $expense->cost_type_id = $validatedData['cost_type'];

        try {
            $expense->save();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) { // 1062 is the MySQL error code for a duplicate entry
                return redirect()->back()->withInput()->withErrors(['unique_column' => 'The value for Rechnungsnummer must be unique.']);
            }

            throw $e;
        }

        // Redirect to a page or route after successful submission
        return redirect()->route('dashboard.index')->with('success', 'Revenue created successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
