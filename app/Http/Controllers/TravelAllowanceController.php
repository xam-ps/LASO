<?php

namespace App\Http\Controllers;

use App\Models\TravelAllowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TravelAllowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $travelAllowance = TravelAllowance::all();

        return view('travel-allowance.index', ['travel_allowances' => $travelAllowance]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companyNames = DB::table('revenue')->select('supplier_name')->distinct()->pluck('supplier_name');

        return view('travel-allowance.create', ['companyNames' => $companyNames]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TravelAllowance $travelAllowance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TravelAllowance $travelAllowance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TravelAllowance $travelAllowance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TravelAllowance $travelAllowance)
    {
        //
    }
}
