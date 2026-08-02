<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Revenue;
use App\Services\AvailableYears;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request, AvailableYears $availableYears)
    {
        $year = (int) $request->route('year', Carbon::now()->year);

        $revenues = Revenue::where(function ($query) use ($year) {
            $query->whereYear('payment_date', $year)
                ->orWhere(function ($query) use ($year) {
                    $query->whereNull('payment_date')->whereYear('billing_date', $year);
                });
        })->orderBy('billing_date', 'DESC')->orderBy('payment_date', 'DESC')->get();

        $expenses = Expense::where(function ($query) use ($year) {
            $query->whereYear('payment_date', $year)
                ->orWhere(function ($query) use ($year) {
                    $query->whereNull('payment_date')->whereYear('billing_date', $year);
                });
        })->orderBy('billing_date', 'DESC')->orderBy('payment_date', 'DESC')->get();

        return view('dashboard', [
            'revenues' => $revenues,
            'revNetSum' => $revenues->sum('net'),
            'revTaxSum' => $revenues->sum('tax'),
            'revGrossSum' => $revenues->sum('gross'),
            'expenses' => $expenses,
            'expNetSum' => $expenses->sum('net'),
            'expTaxSum' => $expenses->sum('tax'),
            'expGrossSum' => $expenses->sum('gross'),
            'years' => $availableYears->get($year),
            'year' => $year,
        ]);
    }
}
