<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index($year = 0)
    {
        $revenues = Revenue::orderBy('billing_date')->orderBy('payment_date')->get();

        $uniqueYears = $revenues->pluck('payment_date')->map(function ($date) {
            return Carbon::parse($date)->year;
        })->unique();

        return view('dashboard', [
            'revenues' => $revenues,
            'netSum' => $revenues->sum('net'),
            'taxSum' => $revenues->sum('tax'),
            'grossSum' => $revenues->sum('gross'),
            'years' => $uniqueYears,
        ]);
    }
}
