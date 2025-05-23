<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $net = $this->faker->randomFloat(2, 50, 600);
        $tax = (int) $net * env('DEFAULT_TAX_RATE') / 100;
        $gross = $net + $tax;

        $billingDate = $this->faker->dateTimeBetween('first day of January this year', 'last day of December this year');

        return [
            'billing_date' => $billingDate,
            'payment_date' => $this->getPaymentDateFromBillingDate($billingDate),
            'supplier_name' => $this->faker->company,
            'product_name' => $this->faker->sentence($nbWords = 3, $variableNbWords = true),
            'invoice_number' => $this->faker->randomNumber(),
            'net' => $net,
            'tax' => $tax,
            'gross' => $gross,
            'cost_type_id' => rand(1, 5),
            'depreciation' => null,
        ];
    }

    public function specificTypeYearsBack($TypeId, $yearsBack)
    {
        return $this->state(function (array $attributes) use ($TypeId, $yearsBack) {
            $expense = $this->definition();
            $net = $expense['net'];
            if ($TypeId == 6) {
                $depreciation = rand(3, 20);
                $net = rand(1000, 2000);
            } else {
                $depreciation = null;
            }
            $tax = (int) $net * env('DEFAULT_TAX_RATE') / 100;
            $gross = $net + $tax;

            $expense['cost_type_id'] = $TypeId;
            if ($yearsBack > 0) {
                $expense['billing_date'] = $this->faker->dateTimeBetween('-'.$yearsBack.' years', '-'.$yearsBack.' years');
                $expense['payment_date'] = $this->getPaymentDateFromBillingDate($expense['billing_date']);
            }
            $expense['net'] = $net;
            $expense['tax'] = $tax;
            $expense['gross'] = $gross;
            $expense['depreciation'] = $depreciation;

            return $expense;
        });
    }

    private function getPaymentDateFromBillingDate($billingDate)
    {
        $maxPaymentDate = (clone $billingDate)->modify('+14 days');
        $endOfYear = (clone $billingDate)->setDate($billingDate->format('Y'), 12, 31);

        return $this->faker->dateTimeBetween($billingDate, min($maxPaymentDate, $endOfYear));
    }
}
