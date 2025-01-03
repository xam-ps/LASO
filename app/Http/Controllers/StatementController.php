<?php

namespace App\Http\Controllers;

use App\Models\CostType;
use App\Models\Expense;
use App\Models\Revenue;
use App\Models\TravelAllowance;
use App\Models\VatNotice;
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

        //calculate vat payments
        $vatNotices = VatNotice::whereYear('notice_date', $year)->get();
        //This is the sum of all received payments from the financial office during the year (months where expenses are higher than revenues)
        $receivedVatPayments = 0;
        //This is the sum of all payments to the financial office during the year (months where revenues are higher than expenses)
        $alreadyPaidVat = 0;
        foreach ($vatNotices as $notice) {
            //for each vat notice, we calculate the difference between received vat (from revenues) and paid vat (from expenses)
            $noticeBalance = $notice->vat_received - $notice->vat_paid;
            //if the difference is positive, it means we already had to pay the vat to the financial office, so we add it to the alreadyPaidVat
            if ($noticeBalance > 0) {
                $alreadyPaidVat += $noticeBalance;
                //if the difference is negative, it means we received money from the financial office, so we add it to the receivedVatPayments
            } else {
                $receivedVatPayments += -1 * $noticeBalance;
            }
        }
        $revTotal = $revNetSum + $revTaxSum + $receivedVatPayments;

        //--------------------------------------------------------------------------------------------------------------

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
        $expAfaObject = new Expense;
        $expAfaObject->total_net = $afaSum;
        $expAfaObject->full_name = $costTypes->where('id', 6)->first()->full_name;
        $expAfaObject->elster_id = $costTypes->where('id', 6)->first()->elster_id;
        $costsByCostType->push($expAfaObject);

        $expTaxObject = new Expense;
        $expTaxObject->total_net = $expTaxSum;
        $expTaxObject->full_name = 'Gezahlte Vorsteuer';
        $expTaxObject->elster_id = 55;
        $costsByCostType->push($expTaxObject);

        $expTravelObject = new Expense;
        $expTravelObject->total_net = $expTravel;
        $expTravelObject->full_name = 'Fahrtkosten';
        $expTravelObject->discription = 'Fahrtkosten für nicht zum Betriebsvermögen gehörende Fahrzeuge (Nutzungseinlage)';
        $expTravelObject->elster_id = 68;
        $costsByCostType->push($expTravelObject);

        $payedVat = $costsByCostType->first(function ($item) {
            return $item->elster_id == 64;
        });
        if ($payedVat == null) {
            $vatCostType = CostType::where('elster_id', 64)->first();
            $payedVat = new Expense;
            $payedVat->total_net = $alreadyPaidVat;
            $payedVat->full_name = $vatCostType->full_name;
            $payedVat->description = $vatCostType->description;
            $payedVat->elster_id = $vatCostType->elster_id;
            $costsByCostType->push($payedVat);
        } else {
            $payedVat['total_net'] += $alreadyPaidVat;
        }

        //sort costs by elster_id to use them in the statement view in the correct order
        $costsByCostType = $costsByCostType->sortBy('elster_id');

        //sum of all expenses, including afa of current year
        $expTotal = $costsByCostType->sum('total_net');

        return view('statement', [
            'revNetSum' => $revNetSum,
            'revTaxSum' => $revTaxSum,
            'receivedVatPayments' => $receivedVatPayments,
            'revTotal' => $revTotal,

            'costs' => $costsByCostType,
            'alreadyPaidVat' => $alreadyPaidVat,
            'travelAllowanceTotal' => $expTravel,
            'expTotal' => $expTotal,

            'profit' => $revTotal - $expTotal,

            'year' => $year,
            'years' => $this->getYearList($year),
        ]);
    }

    private function getYearList($currentYear)
    {
        $uniqueYears = Revenue::select(DB::raw('YEAR(billing_date) as year'))
            ->union(Revenue::select(DB::raw('YEAR(payment_date) as year')))
            ->distinct()
            ->orderBy('year')
            ->pluck('year')
            ->filter();

        if (! $uniqueYears->contains($currentYear)) {
            $uniqueYears->push($currentYear);
        }

        return $uniqueYears;
    }
}
