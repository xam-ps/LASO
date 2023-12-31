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
        $tax = $net * 0.19;
        $gross = $net + $tax;

        return [
            'billing_date' => $this->faker->dateTimeThisYear(),
            'payment_date' => $this->faker->dateTimeThisYear(),
            'company_name' => $this->faker->company,
            'invoice_number' => $this->faker->randomNumber(),
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
            $revenue['payment_date'] = $this->faker->dateTimeBetween('-'.$yearsBack.' years', '-'.$yearsBack.' years');

            return $revenue;
        });
    }
}
