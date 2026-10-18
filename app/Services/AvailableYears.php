<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Revenue;
use App\Models\TravelAllowance;
use App\Models\VatNotice;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AvailableYears
{
    /**
     * @return Collection<int, int>
     */
    public function get(int $selectedYear): Collection
    {
        return collect()
            ->concat($this->transactionYears(Revenue::class))
            ->concat($this->transactionYears(Expense::class))
            ->concat($this->travelYears())
            ->concat($this->vatNoticeYears())
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

    private function vatNoticeYears(): Collection
    {
        return VatNotice::query()
            ->selectRaw('YEAR(notice_date) as year')
            ->distinct()
            ->pluck('year');
    }
}
