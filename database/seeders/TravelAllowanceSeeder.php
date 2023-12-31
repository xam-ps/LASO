<?php

namespace Database\Seeders;

use App\Models\TravelAllowance;
use Illuminate\Database\Seeder;

class TravelAllowanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TravelAllowance::factory()->count(5)->create();
    }
}
