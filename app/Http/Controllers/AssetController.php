<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Carbon\Carbon;

class AssetController extends Controller
{
    protected function index()
    {
        $year = Carbon::now()->year;

        $expensesWithTypeAfa = Expense::whereHas('costType', function ($query) {
            $query->where('elster_id', 31);
        })->orderBy('payment_date', 'DESC')->get();

        foreach ($expensesWithTypeAfa as $expense) {
            $expense->yearsInUse = $year - Carbon::parse($expense->payment_date)->year;
        }

        $expensesWithTypeAfa = $expensesWithTypeAfa->sortBy('yearsInUse');

        return view('asset.index', ['expensesWithTypeAfa' => $expensesWithTypeAfa, 'year' => $year]);
    }
}
