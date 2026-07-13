<?php

namespace Database\Factories;

use App\Models\VatNotice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VatNotice>
 */
class VatNoticeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'notice_date' => $this->faker->dateTimeThisYear(),
            'vat_received' => $this->faker->randomFloat(2, 0, 300),
            'vat_paid' => $this->faker->randomFloat(2, 0, 150),
        ];
    }
}
