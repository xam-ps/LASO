<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Revenue;
use App\Models\TravelAllowance;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AvailableYears
{
    private const AFA_COST_TYPE_SHORT_NAME = 'AfA';

    /**
     * @return Collection<int, int>
     */
    public function get(int $selectedYear): Collection
    {
        return collect()
            ->concat($this->transactionYears(Revenue::class))
            ->concat($this->transactionYears(Expense::class))
            ->concat($this->travelYears())
            ->concat($this->depreciationYears())
            ->push(Carbon::now()->year, $selectedYear)
            ->filter(fn ($year) => is_numeric($year))
            ->map(fn ($year) => (int) $year)
            ->unique()
            ->sort()
            ->values();
    }

    /**
     * @param  class-string<Revenue|Expense>  $model
     */
    private function transactionYears(string $model): Collection
    {
        return $model::query()
            ->selectRaw('YEAR(COALESCE(payment_date, billing_date)) as year')
            ->distinct()
            ->pluck('year');
    }

    private function travelYears(): Collection
    {
        return TravelAllowance::query()
            ->selectRaw('YEAR(travel_date) as year')
            ->distinct()
            ->pluck('year');
    }

    private function depreciationYears(): Collection
    {
        return Expense::query()
            ->whereHas('costType', function ($query) {
                $query->where('short_name', self::AFA_COST_TYPE_SHORT_NAME);
            })
            ->get(['payment_date', 'depreciation', 'net'])
            ->flatMap(function (Expense $expense) {
                if (
                    $expense->payment_date === null
                    || ! is_numeric($expense->depreciation)
                    || $expense->depreciation < 1
                    || ! is_numeric($expense->net)
                    || $expense->net <= 0
                ) {
                    return [];
                }

                $paymentDate = Carbon::parse($expense->payment_date);
                $lastYear = min(
                    $paymentDate->year + (int) $expense->depreciation,
                    Carbon::now()->year,
                );

                if ($paymentDate->month === 1) {
                    $lastYear--;
                }

                if ($paymentDate->year > $lastYear) {
                    return [];
                }

                return range($paymentDate->year, $lastYear);
            });
    }
}
