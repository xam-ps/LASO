<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company_names = DB::table('revenues')->select('company_name')->distinct()->pluck('company_name');

        return view('revenue.create', ['customer_list' => $company_names]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'billing_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'company_name' => 'required|string',
            'invoice_number' => 'required|string',
            'net' => 'required|decimal:0,2',
            'tax' => 'required|decimal:0,2',
            'gross' => 'required|decimal:0,2',
        ]);

        $revenue = new Revenue();
        $revenue->billing_date = $validatedData['billing_date'];
        $revenue->payment_date = $validatedData['payment_date'];
        $revenue->company_name = $validatedData['company_name'];
        $revenue->invoice_number = $validatedData['invoice_number'];
        $revenue->net = $validatedData['net'];
        $revenue->tax = $validatedData['tax'];
        $revenue->gross = $validatedData['gross'];

        try {
            $revenue->save();
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
     * Display the specified resource.
     */
    public function show(Revenue $revenues)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $company_names = DB::table('revenues')->select('company_name')->distinct()->pluck('company_name');

        return view('revenue.edit', ['revenue' => Revenue::find($id), 'customer_list' => $company_names]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'billing_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'company_name' => 'required|string',
            'invoice_number' => 'required|string',
            'net' => 'required|decimal:0,2',
            'tax' => 'required|decimal:0,2',
            'gross' => 'required|decimal:0,2',
        ]);

        $revenue = Revenue::find($id);
        $revenue->billing_date = $validatedData['billing_date'];
        $revenue->payment_date = $validatedData['payment_date'];
        $revenue->company_name = $validatedData['company_name'];
        $revenue->invoice_number = $validatedData['invoice_number'];
        $revenue->net = $validatedData['net'];
        $revenue->tax = $validatedData['tax'];
        $revenue->gross = $validatedData['gross'];

        try {
            $revenue->save();
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
    public function destroy(Revenue $revenues)
    {
        //
    }
}
