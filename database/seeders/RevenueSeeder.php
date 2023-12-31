<?php

namespace Database\Seeders;

use App\Models\Revenue;
use Illuminate\Database\Seeder;

class RevenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Revenue::factory()->create();
        Revenue::factory()->create();
        Revenue::factory()->create();
        Revenue::factory()->create();
        Revenue::factory()->yearsBack(1)->create();
        Revenue::factory()->yearsBack(1)->create();
        Revenue::factory()->yearsBack(1)->create();
        Revenue::factory()->yearsBack(1)->create();
    }
}
