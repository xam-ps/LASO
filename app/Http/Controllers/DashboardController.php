<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->route('year', Carbon::now()->year);

        if ($year === Carbon::now()->year) {
            $revenues = Revenue::whereYear('payment_date', $year)->orWhereNull('payment_date')->orderBy('billing_date')->orderBy('payment_date')->get();
        } else {
            $revenues = Revenue::whereYear('payment_date', $year)->orderBy('billing_date')->orderBy('payment_date')->get();
        }

        return view('dashboard', [
            'revenues' => $revenues,
            'netSum' => $revenues->sum('net'),
            'taxSum' => $revenues->sum('tax'),
            'grossSum' => $revenues->sum('gross'),
            'years' => $this->getYearList(),
        ]);
    }

    private function getYearList()
    {
        $uniqueYears = Revenue::select(DB::raw('YEAR(billing_date) as year'))
            ->union(Revenue::select(DB::raw('YEAR(payment_date) as year')))
            ->distinct()
            ->orderBy('year')
            ->pluck('year')
            ->filter();

        return $uniqueYears;
    }
}
