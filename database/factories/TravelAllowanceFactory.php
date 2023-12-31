<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TravelAllowance>
 */
class TravelAllowanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $distance = $this->faker->randomNumber(2);
        $refund = $distance * 0.3;

        return [
            'travel_date' => $this->faker->dateTimeThisYear()->format('Y-m-d'),
            'start' => $this->faker->time($format = 'H:i', $max = 'now'),
            'end' => $this->faker->time($format = 'H:i', $max = 'now'),
            'destination' => $this->faker->city,
            'reason' => $this->faker->sentence,
            'company' => $this->faker->company,
            'distance' => $distance,
            'notes' => $this->faker->paragraph,
            'refund' => $refund,
        ];
    }
}
