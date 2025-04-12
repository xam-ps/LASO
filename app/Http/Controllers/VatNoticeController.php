<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Revenue;
use App\Models\VatNotice;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VatNoticeController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->route('year', Carbon::now()->year);

        $uniqueYears = Revenue::select(DB::raw('YEAR(billing_date) as year'))
            ->union(Revenue::select(DB::raw('YEAR(payment_date) as year')))
            ->union(Expense::select(DB::raw('YEAR(payment_date) as year')))
            ->distinct()
            ->orderBy('year')
            ->pluck('year')
            ->filter();
        if (! $uniqueYears->contains($year)) {
            $uniqueYears->push($year);
        }

        $totalRevenueTax = Revenue::whereYear('payment_date', $year)->sum('tax');
        $totalExpenseTax = round(Expense::join('cost_types', 'expenses.cost_type_id', '=', 'cost_types.id')
            ->whereYear('expenses.payment_date', $year)
            ->select(DB::raw('SUM(expenses.tax * cost_types.ratio) as total_tax'))
            ->value('total_tax'), 2);

        $vatNotices = VatNotice::whereYear('notice_date', $year)->orderBy('notice_date', 'DESC')->get();

        $totalReportedRevenueTax = $vatNotices->sum('vat_received');
        $totalReportedExpenseTax = $vatNotices->sum('vat_paid');

        $remainingRevenueTax = $totalRevenueTax - $totalReportedRevenueTax;
        $remainingExpenseTax = $totalExpenseTax - $totalReportedExpenseTax;

        $remainingNetRevenue = round($remainingRevenueTax * 100 / 19, 0);
        $remainingRevenueTax = $remainingNetRevenue * 19 / 100;

        return view('vat-notice.index', [
            'totalRevenueTax' => $totalRevenueTax,
            'totalExpenseTax' => $totalExpenseTax,
            'remainingNetRevenue' => $remainingNetRevenue,
            'remainingRevenueTax' => $remainingRevenueTax,
            'remainingExpenseTax' => $remainingExpenseTax,
            'vat_notices' => $vatNotices,
            'years' => $uniqueYears,
            'year' => $year,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $now = Carbon::now()->toDateString();
        $year = $request->route('year', Carbon::now()->year);

        $totalRevenueTax = Revenue::whereYear('payment_date', $year)->sum('tax');
        $totalExpenseTax = round(Expense::join('cost_types', 'expenses.cost_type_id', '=', 'cost_types.id')
            ->whereYear('expenses.payment_date', $year)
            ->select(DB::raw('SUM(expenses.tax * cost_types.ratio) as total_tax'))
            ->value('total_tax'), 2);

        $vatNotices = VatNotice::whereYear('notice_date', $year)->orderBy('notice_date', 'DESC')->get();

        $totalReportedRevenueTax = $vatNotices->sum('vat_received');
        $totalReportedExpenseTax = $vatNotices->sum('vat_paid');

        $remainingRevenueTax = $totalRevenueTax - $totalReportedRevenueTax;
        $remainingExpenseTax = $totalExpenseTax - $totalReportedExpenseTax;

        $remainingRevenueTax = round($remainingRevenueTax * 100 / 19, 0) * 19 / 100; // Elster only let you use non decimal numbers for the net revenue

        return view('vat-notice.create', ['now' => $now, 'remainingRevenueTax' => $remainingRevenueTax, 'remainingExpenseTax' => $remainingExpenseTax, 'year' => $year]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validator($request);

        $vatNotice = new VatNotice;
        $this->fillValues($validatedData, $vatNotice);

        try {
            $vatNotice->save();
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Es ist ein Fehler beim Speichern aufgetreten.']);
        }

        return redirect()->route('vat-notice.year', ['year' => $request['year']])->with('success', 'Vat Notice created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $vatNotice = VatNotice::find($id);

        return view('vat-notice.edit', ['vatNotice' => $vatNotice]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $this->validator($request);

        $vatNotice = VatNotice::find($id);
        $this->fillValues($validatedData, $vatNotice);

        try {
            $vatNotice->save();
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Es ist ein Fehler beim Speichern aufgetreten.']);
        }

        return redirect()->route('vat-notice.index')->with('success', 'Vat Notice created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        VatNotice::destroy($id);

        return redirect()->route('vat-notice.index')->with('success', 'Vat Notice deleted successfully.');
    }

    private function validator(Request $request)
    {
        $messages = [
            'notice_date.required' => 'Bitte geben Sie ein gültiges Datum ein.',
            'vat_received.required' => 'Bitte geben Sie einen Betrag für die eingenommene Umsatzsteuer ein.',
            'vat_paid.required' => 'Bitte geben Sie einen Betrag für die gezahlte Umsatzsteuer ein.',
        ];

        return $request->validate([
            'notice_date' => 'required|date',
            'vat_received' => 'required|decimal:0,2',
            'vat_paid' => 'required|decimal:0,2',
        ], $messages);
    }

    private function fillValues($validatedData, $vatNotice)
    {
        $vatNotice->notice_date = $validatedData['notice_date'];
        $vatNotice->vat_received = $validatedData['vat_received'];
        $vatNotice->vat_paid = $validatedData['vat_paid'];
    }
}
