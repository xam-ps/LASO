<?php

namespace App\Http\Controllers;

use App\Models\TravelAllowance;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TravelAllowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $year = $request->route('year', Carbon::now()->year);

        $travelAllowance = TravelAllowance::whereYear('travel_date', $year)->orderBy('travel_date', 'DESC')->get();
        $uniqueYears = TravelAllowance::select(DB::raw('YEAR(travel_date) as year'))
            ->distinct()
            ->orderBy('year')
            ->pluck('year')
            ->filter();

        return view('travel-allowance.index', [
            'travel_allowances' => $travelAllowance,
            'total' => $travelAllowance->sum('refund'),
            'totalDistance' => $travelAllowance->sum('distance'),
            'years' => $uniqueYears,
            'year' => $year,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companyNames = DB::table('revenues')->select('company_name')->distinct()->pluck('company_name');

        $now = Carbon::now()->toDateString();

        return view('travel-allowance.create', ['companyNames' => $companyNames, 'now' => $now]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validator($request);

        $travelAllowance = new TravelAllowance;
        $this->fillValues($validatedData, $travelAllowance);

        try {
            $travelAllowance->save();
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['unique_column' => 'The value for Rechnungsnummer must be unique.']);
        }

        return redirect()->route('travel-allowance.index')->with('success', 'Travel allowance created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $companyNames = DB::table('revenues')->select('company_name')->distinct()->pluck('company_name');
        $travelAllowance = TravelAllowance::find($id);

        return view('travel-allowance.edit', ['travelAllowance' => $travelAllowance, 'companyNames' => $companyNames]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $this->validator($request);

        $travelAllowance = TravelAllowance::find($id);
        $this->fillValues($validatedData, $travelAllowance);

        try {
            $travelAllowance->save();
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['unique_column' => 'The value for Rechnungsnummer must be unique.']);
        }

        return redirect()->route('travel-allowance.index')->with('success', 'Travel allowance created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        TravelAllowance::destroy($id);

        return redirect()->route('travel-allowance.index')->with('success', 'Revenue deleted successfully.');
    }

    private function validator(Request $request)
    {
        $messages = [
            //please use all fields from the validator to create custom messages in German
            'travel_date' => 'Bitte geben Sie ein gültiges Reisedatum ein.',
            'start' => 'Bitte geben Sie eine gültige Startzeit ein.',
            'end' => 'Bitte geben Sie eine gültige Endzeit ein.',
            'destination' => 'Bitte geben Sie ein Reiseziel an.',
            'reason' => 'Bitte geben Sie einen Grund für die Reise an.',
            'company_name' => 'Bitte geben Sie einen Kunden an.',
            'distance' => 'Bitte geben Sie eine gültige Entfernung in km an.',
            'refund' => 'Bitte geben Sie einen Erstattungsbetrag an.',
        ];

        return $request->validate([
            'travel_date' => 'required|date',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i',
            'destination' => 'required|string',
            'reason' => 'required|string',
            'company_name' => 'nullable|string',
            'distance' => 'required|integer',
            'notes' => 'nullable|string',
            'refund' => 'required|decimal:0,2',
        ], $messages);
    }

    private function fillValues($validatedData, $travelAllowance)
    {
        $travelAllowance->travel_date = $validatedData['travel_date'];
        $travelAllowance->start = $validatedData['start'];
        $travelAllowance->end = $validatedData['end'];
        $travelAllowance->destination = $validatedData['destination'];
        $travelAllowance->reason = $validatedData['reason'];
        $travelAllowance->company = $validatedData['company_name'];
        $travelAllowance->distance = $validatedData['distance'];
        $travelAllowance->notes = $validatedData['notes'];
        $travelAllowance->refund = $validatedData['refund'];
    }
}
