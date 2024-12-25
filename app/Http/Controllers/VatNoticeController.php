<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class VatNoticeController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->route('year', Carbon::now()->year);

        $uniqueYears = TravelAllowance::select(DB::raw('YEAR(travel_date) as year'))
            ->distinct()
            ->orderBy('year')
            ->pluck('year')
            ->filter();

        return view('vat-notice.index', [
            'year' => $year,
        ]);
    }
}
