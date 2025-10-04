<?php

namespace Database\Factories;

use App\Models\Revenue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Revenue>
 */
class RevenueFactory extends Factory
{
    protected $model = Revenue::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'billing_date' => $this->faker->dateTimeThisYear(),
            'company_name' => $this->faker->company,
            'invoice_number' => $this->faker->randomNumber(8),
            'net' => $this->faker->randomFloat(2, 120, 3000),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Revenue $revenue) {
            $rate = config('app.default_tax_rate', env('DEFAULT_TAX_RATE', 19));

            $revenue->tax = (int) $revenue->net * $rate / 100;
            $revenue->gross = $revenue->net + $revenue->tax;

            $revenue->payment_date ??= $this->faker->dateTimeBetween(
                $revenue->billing_date,
                min(
                    (clone $revenue->billing_date)->modify('+14 days'),
                    (clone $revenue->billing_date)->setDate((int) $revenue->billing_date->format('Y'), 12, 31)
                )
            );
        });
    }

    public function yearsBack(int $years): static
    {
        return $this->state(fn () => [
            'billing_date' => $this->faker->dateTimeBetween("-{$years} years", "-{$years} years"),
        ]);
    }
}
