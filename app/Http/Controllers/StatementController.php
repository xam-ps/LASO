<?php

namespace App\Http\Controllers;

use App\Models\CostType;
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

        $costTypes = CostType::all();

        //calculate all needed number for revenues
        $revenues = Revenue::whereYear('payment_date', $year)->get();
        $revTaxSum = $revenues->sum('tax');
        $revNetSum = $revenues->sum('net');
        $revTotal = $revNetSum + $revTaxSum;

        //get all travel allowances for the year
        $travelAllowance = TravelAllowance::whereYear('travel_date', $year)->get();

        //get all expenses for the year
        $costsByCostType = Expense::join('cost_types', 'expenses.cost_type_id', '=', 'cost_types.id')
            ->groupBy('cost_types.id')
            ->select('cost_types.id', 'cost_types.elster_id', 'cost_types.full_name', 'cost_types.description', DB::raw('SUM(expenses.net) * cost_types.ratio as total_net'), DB::raw('SUM(expenses.tax) * cost_types.ratio as total_tax'))
            ->whereYear('payment_date', $year)
            ->groupBy('cost_type_id')
            ->get();

        //tax is calculated from ALL expenses of the year including afa
        $expTaxSum = $costsByCostType->sum('total_tax');

        //remove afa from costs
        $costsByCostType = $costsByCostType->reject(function ($value) {
            return $value->id == 6;
        });

        //get all depreciations for the year
        $expensesWithTypeAfa = Expense::whereHas('costType', function ($query) {
            $query->where('id', 6);
        })->get();

        //calculate afa for the year
        $afaSum = AssetController::calcAfaForYear($expensesWithTypeAfa, $year);

        //calculate travel allowance for the year
        $expTravel = $travelAllowance->sum('refund');

        //add afa, tax and travel allowance to costs
        $expAfaObject = new Expense();
        $expAfaObject->total_net = $afaSum;
        $expAfaObject->full_name = $costTypes->where('id', 6)->first()->full_name;
        $expAfaObject->elster_id = $costTypes->where('id', 6)->first()->elster_id;
        $costsByCostType->push($expAfaObject);

        $expTaxObject = new Expense();
        $expTaxObject->total_net = $expTaxSum;
        $expTaxObject->full_name = 'Gezahlte Vorsteuer';
        $expTaxObject->elster_id = 55;
        $costsByCostType->push($expTaxObject);

        $expTravelObject = new Expense();
        $expTravelObject->total_net = $expTravel;
        $expTravelObject->full_name = 'Fahrtkosten';
        $expTravelObject->discription = 'Fahrtkosten für nicht zum Betriebsvermögen gehörende Fahrzeuge (Nutzungseinlage)';
        $expTravelObject->elster_id = 68;
        $costsByCostType->push($expTravelObject);

        //sort costs by elster_id to use them in the statement view in the correct order
        $costsByCostType = $costsByCostType->sortBy('elster_id');

        //sum of all expenses, including afa of current year
        $expTotal = $costsByCostType->sum('total_net');

        return view('statement', [
            'revNetSum' => $revNetSum,
            'revTaxSum' => $revTaxSum,
            'revTotal' => $revTotal,

            'costs' => $costsByCostType,
            'travelAllowanceTotal' => $expTravel,
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
