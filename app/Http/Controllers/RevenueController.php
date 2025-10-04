<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use Carbon\Carbon;
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

        $now = Carbon::now()->toDateString();

        return view('revenue.create', ['customer_list' => $company_names, 'now' => $now]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validator($request);

        $revenue = new Revenue;
        $this->fillValues($validatedData, $revenue);

        try {
            $revenue->save();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) { // 1062 is the MySQL error code for a duplicate entry
                return redirect()->back()->withInput()->withErrors(['unique_column' => 'Die Rechnungsnummer existiert bereits.']);
            }
            throw $e;
        }

        return redirect()->route('dashboard.index')->with('success', 'Revenue created successfully.');
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
        $validatedData = $this->validator($request);

        $revenue = Revenue::find($id);
        $this->fillValues($validatedData, $revenue);

        try {
            $revenue->save();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) { // 1062 is the MySQL error code for a duplicate entry
                return redirect()->back()->withInput()->withErrors(['unique_column' => 'The value for Rechnungsnummer must be unique.']);
            }
            throw $e;
        }

        return redirect()->route('dashboard.index')->with('success', 'Revenue created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Revenue::destroy($id);

        return redirect()->route('dashboard.index')->with('success', 'Revenue deleted successfully.');
    }

    private function validator(Request $request)
    {
        $messages = [
            'billing_date' => 'Bitte geben Sie ein gültiges Rechnungsdatum ein.',
            'payment_date' => 'Bitte geben Sie ein gültiges Zahlungsdatum ein.',
            'company_name' => 'Bitte geben Sie einen Firmennamen an.',
            'invoice_number' => 'Bitte geben Sie eine Rechnungsnummer an.',
            'net' => 'Bitte geben Sie einen gültigen Netto-Betrag ein.',
            'tax' => 'Bitte geben Sie einen gültigen Steuer-Betrag ein.',
            'gross' => 'Bitte geben Sie einen gültigen Brutto-Betrag ein.',
        ];

        return $request->validate([
            'billing_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'company_name' => 'required|string',
            'invoice_number' => 'required|string',
            'net' => 'required|decimal:0,2',
            'tax' => 'required|decimal:0,2',
            'gross' => 'required|decimal:0,2',
        ], $messages);
    }

    private function fillValues($validatedData, $revenue)
    {
        $revenue->billing_date = $validatedData['billing_date'];
        $revenue->payment_date = $validatedData['payment_date'];
        $revenue->company_name = $validatedData['company_name'];
        $revenue->invoice_number = $validatedData['invoice_number'];
        $revenue->net = $validatedData['net'];
        $revenue->tax = $validatedData['tax'];
        $revenue->gross = $validatedData['gross'];
    }
}
