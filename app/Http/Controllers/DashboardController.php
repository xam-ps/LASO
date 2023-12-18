<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $revenues = Revenue::orderBy('billing_date')->orderBy('payment_date')->get();
        return view('dashboard', [
            'revenues' => $revenues,
            'netSum' => $revenues->sum('net'),
            'taxSum' => $revenues->sum('tax'),
            'grossSum' => $revenues->sum('gross'),
        ]);
    }
}
