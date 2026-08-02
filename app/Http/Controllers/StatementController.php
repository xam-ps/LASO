<?php

namespace App\Http\Controllers;

use App\Models\CostType;
use App\Models\Expense;
use App\Models\Revenue;
use App\Models\TravelAllowance;
use App\Models\VatNotice;
use App\Support\ElsterLines;
use App\Services\AvailableYears;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatementController extends Controller
{
    /**
     * Sort order of the statement rows that are not backed by a cost type.
     * The values slot them into the sequence the Anlage EÜR uses.
     */
    private const VORSTEUER_SORT_ORDER = 85;

    private const TRAVEL_SORT_ORDER = 95;

    /**
     * Cost types are identified by their short_name - the same key the ELSTER
     * line mapping in config/elster/{year}.php uses.
     */
    private const AFA_SHORT_NAME = 'AfA';

    private const VAT_PAID_SHORT_NAME = 'F-Ust';

    public function index(Request $request, AvailableYears $availableYears)
    {
        $year = (int) $request->route('year', Carbon::now()->year);

        $costTypes = CostType::all();
        $afaCostType = $costTypes->firstWhere('short_name', self::AFA_SHORT_NAME);

        // calculate all needed number for revenues
        $revenues = Revenue::whereYear('payment_date', $year)->get();
        $revTaxSum = $revenues->sum('tax');
        $revNetSum = $revenues->sum('net');

        // calculate vat payments
        $vatNotices = VatNotice::whereYear('notice_date', $year)->get();
        // This is the sum of all received payments from the financial office during the year (months where expenses are higher than revenues)
        $receivedVatPayments = 0;
        // This is the sum of all payments to the financial office during the year (months where revenues are higher than expenses)
        $alreadyPaidVat = 0;
        foreach ($vatNotices as $notice) {
            // for each vat notice, we calculate the difference between received vat (from revenues) and paid vat (from expenses)
            $noticeBalance = $notice->vat_received - $notice->vat_paid;
            // if the difference is positive, it means we already had to pay the vat to the financial office, so we add it to the alreadyPaidVat
            if ($noticeBalance > 0) {
                $alreadyPaidVat += $noticeBalance;
                // if the difference is negative, it means we received money from the financial office, so we add it to the receivedVatPayments
            } else {
                $receivedVatPayments += -1 * $noticeBalance;
            }
        }
        $revTotal = $revNetSum + $revTaxSum + $receivedVatPayments;

        // --------------------------------------------------------------------------------------------------------------

        // get all travel allowances for the year
        $travelAllowance = TravelAllowance::whereYear('travel_date', $year)->get();

        // get all expenses for the year
        $costsByCostType = Expense::join('cost_types', 'expenses.cost_type_id', '=', 'cost_types.id')
            ->groupBy('cost_types.id')
            ->select('cost_types.id', 'cost_types.short_name as elster_key', 'cost_types.sort_order', 'cost_types.full_name', 'cost_types.description', DB::raw('SUM(expenses.net) * cost_types.ratio as total_net'), DB::raw('SUM(expenses.tax) * cost_types.ratio as total_tax'))
            ->whereYear('payment_date', $year)
            ->get();

        // tax is calculated from ALL expenses of the year including afa
        $expTaxSum = $costsByCostType->sum('total_tax');

        // remove afa from costs - only the depreciation instalment is deductible,
        // not the purchase price paid this year
        $costsByCostType = $costsByCostType->reject(function ($value) {
            return $value->elster_key === self::AFA_SHORT_NAME;
        });

        // calculate afa for the year
        $afaSum = 0;
        if ($afaCostType !== null) {
            $expensesWithTypeAfa = Expense::where('cost_type_id', $afaCostType->id)->get();
            $afaSum = AssetController::calcAfaForYear($expensesWithTypeAfa, $year);

            $expAfaObject = new Expense;
            $expAfaObject->total_net = $afaSum;
            $expAfaObject->full_name = $afaCostType->full_name;
            $expAfaObject->description = $afaCostType->description;
            $expAfaObject->elster_key = $afaCostType->short_name;
            $expAfaObject->sort_order = $afaCostType->sort_order;
            $costsByCostType->push($expAfaObject);
        }

        // calculate travel allowance for the year
        $expTravel = $travelAllowance->sum('refund');

        $expTaxObject = new Expense;
        $expTaxObject->total_net = $expTaxSum;
        $expTaxObject->full_name = 'Gezahlte Vorsteuer';
        $expTaxObject->description = 'Gezahlte Vorsteuerbeträge';
        $expTaxObject->elster_key = 'vorsteuer';
        $expTaxObject->sort_order = self::VORSTEUER_SORT_ORDER;
        $costsByCostType->push($expTaxObject);

        $expTravelObject = new Expense;
        $expTravelObject->total_net = $expTravel;
        $expTravelObject->full_name = 'Fahrtkosten';
        $expTravelObject->description = 'Fahrtkosten für nicht zum Betriebsvermögen gehörende Fahrzeuge (Nutzungseinlage)';
        $expTravelObject->elster_key = 'travel';
        $expTravelObject->sort_order = self::TRAVEL_SORT_ORDER;
        $costsByCostType->push($expTravelObject);

        // the vat paid to the financial office is added to the matching cost type
        // if the user booked expenses on it, otherwise it becomes its own row
        $payedVat = $costsByCostType->first(function ($item) {
            return $item->elster_key === self::VAT_PAID_SHORT_NAME;
        });
        if ($payedVat === null) {
            $vatCostType = $costTypes->firstWhere('short_name', self::VAT_PAID_SHORT_NAME);
            $payedVat = new Expense;
            $payedVat->total_net = $alreadyPaidVat;
            $payedVat->full_name = $vatCostType->full_name ?? 'An Finanzamt gezahlte Umsatzsteuer';
            $payedVat->description = $vatCostType->description ?? null;
            $payedVat->elster_key = self::VAT_PAID_SHORT_NAME;
            $payedVat->sort_order = $vatCostType->sort_order ?? 90;
            $costsByCostType->push($payedVat);
        } else {
            $payedVat['total_net'] += $alreadyPaidVat;
        }

        // sort costs to use them in the statement view in the order of the form
        $costsByCostType = $costsByCostType->sortBy('sort_order');

        // sum of all expenses, including afa of current year
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
            'years' => $availableYears->get($year),
            'elster' => ElsterLines::for($year),
            'elsterFormPending' => $this->elsterFormIsPending($year),
        ]);
    }

    /**
     * ELSTER publishes the form for a tax year at the start of the following
     * year, and the EÜR for the running year is not filed before it ends. A
     * missing mapping is therefore expected for the current and future years -
     * the statement says so plainly instead of raising a warning. For a past
     * year the form does exist, so a missing mapping means LASO is behind and
     * the user has to look up the lines themselves.
     */
    private function elsterFormIsPending($year): bool
    {
        return is_numeric($year) && (int) $year >= Carbon::now()->year;
    }
}
