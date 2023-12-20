<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RevenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $net = 589;
        $tax = $net * 19 / 100;
        $gross = $net + $tax;
        DB::table('revenues')->insert([
            'billing_date' => Carbon::create(2023, 7, 10),
            'payment_date' => Carbon::create(2023, 7, 24),
            'company_name' => 'Company Name 2',
            'invoice_number' => '20230801',
            'net' => $net,
            'tax' => $tax,
            'gross' => $gross,
        ]);

        $net = $net * 1.11;
        $tax = $net * 19 / 100;
        $gross = $net + $tax;
        DB::table('revenues')->insert([
            'billing_date' => Carbon::create(2023, 8, 31),
            'payment_date' => Carbon::create(2023, 9, 13),
            'company_name' => 'Company Name 1',
            'invoice_number' => '20230802',
            'net' => $net,
            'tax' => $tax,
            'gross' => $gross,
        ]);

        DB::table('revenues')->insert([
            'billing_date' => Carbon::create(2021, 12, 24),
            'payment_date' => Carbon::create(2022, 1, 10),
            'company_name' => 'Some Company',
            'invoice_number' => '20211205',
            'net' => 1000,
            'tax' => 190,
            'gross' => 1190,
        ]);

        $currentYear = date('Y');
        $currentMonth = date('m');
        DB::table('revenues')->insert([
            'billing_date' => now(),
            'payment_date' => now(),
            'company_name' => 'Another Company',
            'invoice_number' => $currentYear.$currentMonth.'05',
            'net' => 1000,
            'tax' => 190,
            'gross' => 1190,
        ]);
    }
}
