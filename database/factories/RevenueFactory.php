<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Revenue>
 */
class RevenueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $net = $this->faker->randomFloat(2, 0, 3000);
        $tax = (int) $net * env('DEFAULT_TAX_RATE') / 100;
        $gross = $net + $tax;

        $billigDate = $this->faker->dateTimeThisYear();

        return [
            'billing_date' => $billigDate,
            'payment_date' => $this->getPaymentDateFromBillingDate($billigDate),
            'company_name' => $this->faker->company,
            'invoice_number' => $this->faker->randomNumber(8),
            'net' => $net,
            'tax' => $tax,
            'gross' => $gross,
        ];
    }

    public function yearsBack($yearsBack)
    {
        return $this->state(function (array $attributes) use ($yearsBack) {
            $revenue = $this->definition();
            $revenue['billing_date'] = $this->faker->dateTimeBetween('-'.$yearsBack.' years', '-'.$yearsBack.' years');
            $revenue['payment_date'] = $this->getPaymentDateFromBillingDate($revenue['billing_date']);

            return $revenue;
        });
    }

    private function getPaymentDateFromBillingDate($billingDate)
    {
        $maxPaymentDate = (clone $billingDate)->modify('+14 days');
        $endOfYear = (clone $billingDate)->setDate($billingDate->format('Y'), 12, 31);

        return $this->faker->dateTimeBetween($billingDate, min($maxPaymentDate, $endOfYear));
    }
}
