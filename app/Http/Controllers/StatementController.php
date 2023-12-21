<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Revenue;
use App\Models\TravelAllowance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatementController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->route('year', Carbon::now()->year);

        $revenues = Revenue::whereYear('payment_date', $year)->get();
        $expenses = Expense::whereYear('payment_date', $year)->get();
        $travelAllowance = TravelAllowance::whereYear('travel_date', $year)->get();
        $costsByCostType = Expense::join('cost_types', 'expenses.cost_type_id', '=', 'cost_types.id')
            ->groupBy('cost_types.id')
            ->select('cost_types.elster_id', 'cost_types.full_name', DB::raw('SUM(expenses.net) as total_cost'))
            ->whereYear('payment_date', $year)
            ->groupBy('cost_type_id')
            ->orderBy('elster_id')
            ->get();

        return view('statement', [
            'revNetSum' => $revenues->sum('net'),
            'revTaxSum' => $revenues->sum('tax'),

            'expTaxSum' => $expenses->sum('tax'),

            'costs' => $costsByCostType,

            'travelAllowanceTotal' => $travelAllowance->sum('refund'),

            'year' => $year,
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
