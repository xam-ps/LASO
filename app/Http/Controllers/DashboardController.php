<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', ['revenues' => Revenue::orderBy('billing_date')->orderBy('payment_date')->get()]);
    }
}
