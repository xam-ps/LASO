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
        TravelAllowance::create([
            'travel_date' => now(),
            'start' => '16:00',
            'end' => '18:00',
            'destination' => 'Einsteinstraße 1, 81675 München',
            'reason' => 'Acquisition meeting',
            'company' => 'Crazy new startup',
            'distance' => 81,
            'notes' => 'Some important note',
            'refund' => '27.58',
        ]);

        TravelAllowance::create([
            'travel_date' => now(),
            'start' => '08:00',
            'end' => '10:00',
            'destination' => 'Humboldtstraße 1, 90459 Nürnberg',
            'reason' => 'Fixing everything',
            'company' => 'Some 100 years old company',
            'distance' => 98,
            'refund' => '34.04',
        ]);
    }
}
