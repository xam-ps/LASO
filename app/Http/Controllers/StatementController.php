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
        $revTaxSum = $revenues->sum('tax');
        $revNetSum = $revenues->sum('net');
        $revTotal = $revNetSum + $revTaxSum;

        $travelAllowance = TravelAllowance::whereYear('travel_date', $year)->get();
        $costsByCostType = Expense::join('cost_types', 'expenses.cost_type_id', '=', 'cost_types.id')
            ->groupBy('cost_types.id')
            ->select('cost_types.elster_id', 'cost_types.full_name', DB::raw('SUM(expenses.net) * cost_types.ratio as total_net'), DB::raw('SUM(expenses.tax) * cost_types.ratio as total_tax'))
            ->whereYear('payment_date', $year)
            ->groupBy('cost_type_id')
            ->orderBy('elster_id')
            ->get();

        //get the sum of all Expenses that have cost type afa, as they can't be used for revenue reduction in one year
        $expensesWithTypeAfa = Expense::whereYear('payment_date', $year)->whereHas('costType', function ($query) {
            $query->where('elster_id', 31);
        })->sum('net');

        //substract afa expenses from net, as they need to be calculated extra
        $expNetSum = $costsByCostType->sum('total_net') - $expensesWithTypeAfa;
        $expTaxSum = $costsByCostType->sum('total_tax');
        $expTravel = $travelAllowance->sum('refund');
        $expTotal = $expNetSum + $expTaxSum + $expTravel;

        return view('statement', [
            'revNetSum' => $revNetSum,
            'revTaxSum' => $revTaxSum,
            'revTotal' => $revTotal,

            'costs' => $costsByCostType,
            'travelAllowanceTotal' => $expTravel,
            'expTaxSum' => $expTaxSum,
            'expNetSum' => $expNetSum,
            'expTotal' => $expTotal,

            'profit' => $revTotal - $expTotal,

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
