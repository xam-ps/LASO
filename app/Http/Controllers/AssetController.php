<?php

namespace App\Http\Controllers;

use App\Models\Expense;

class AssetController extends Controller
{
    protected function index()
    {
        $expensesWithTypeAfa = Expense::whereHas('costType', function ($query) {
            $query->where('elster_id', 31);
        })->orderBy('payment_date', 'DESC')->get();

        return view('asset.index', ['expensesWithTypeAfa' => $expensesWithTypeAfa]);
    }
}
