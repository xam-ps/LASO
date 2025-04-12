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
            $query->where('id', 6);
        })->orderBy('payment_date', 'DESC')->get();

        foreach ($expensesWithTypeAfa as $expense) {
            $this->calcDepreciationValues($expense, $year);
        }

        $expensesWithTypeAfa = $expensesWithTypeAfa->sortBy('percUsed');

        return view('asset.index', ['expensesWithTypeAfa' => $expensesWithTypeAfa, 'year' => $year]);
    }

    public static function calcDepreciationValues($expense, $year)
    {
        $expense->yearsInUse = $year - Carbon::parse($expense->payment_date)->year;
        $expense->percUsed = $expense->yearsInUse * 100 / $expense->depreciation;
        $costPerMonth = $expense->net / $expense->depreciation / 12;
        $expense->firstYear = $costPerMonth * (12 - (Carbon::parse($expense->payment_date)->month - 1));
        $expense->middleYear = $expense->net / $expense->depreciation;
        $expense->lastYear = $costPerMonth * ((Carbon::parse($expense->payment_date)->month - 1));
        if ($expense->yearsInUse == 0) {
            $expense->residualValue = $expense->net;
        } elseif ($expense->yearsInUse > $expense->depreciation) {
            $expense->residualValue = 0;
        } else {
            $expense->residualValue = $expense->net - $expense->firstYear - ($expense->middleYear * min($expense->yearsInUse - 1, $expense->depreciation - 1));
        }
    }

    public static function calcAfaForYear($expensesWithTypeAfa, $year)
    {
        $afaSum = 0;
        foreach ($expensesWithTypeAfa as $expense) {
            $paymentDate = Carbon::parse($expense->payment_date);
            AssetController::calcDepreciationValues($expense, $year);
            if ($paymentDate->year == $year) {
                $afaSum += $expense->firstYear;
            } elseif ($year > $paymentDate->year && $year < $paymentDate->year + $expense->depreciation) {
                $afaSum += $expense->middleYear;
            } elseif ($year == ($paymentDate->year + $expense->depreciation)) {
                $afaSum += $expense->lastYear;
            }
        }

        return $afaSum;
    }
}
