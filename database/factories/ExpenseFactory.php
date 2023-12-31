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
        $net = $this->faker->randomFloat(2, 0, 600);
        $tax = (int) $net * 0.19;
        $gross = $net + $tax;

        return [
            'billing_date' => $this->faker->dateTimeThisYear(),
            'payment_date' => $this->faker->dateTimeThisYear(),
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
            $tax = (int) $net * 0.19;
            $gross = $net + $tax;

            $expense['cost_type_id'] = $TypeId;
            if ($yearsBack > 0) {
                $expense['billing_date'] = $this->faker->dateTimeBetween('-'.$yearsBack.' years', '-'.$yearsBack.' years');
                $expense['payment_date'] = $this->faker->dateTimeBetween('-'.$yearsBack.' years', '-'.$yearsBack.' years');
            }
            $expense['net'] = $net;
            $expense['tax'] = $tax;
            $expense['gross'] = $gross;
            $expense['depreciation'] = $depreciation;

            return $expense;
        });
    }
}
