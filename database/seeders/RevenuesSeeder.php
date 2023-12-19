<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RevenuesSeeder extends Seeder
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
            'billing_date' => Carbon::create(2023, 8, 31),
            'payment_date' => Carbon::create(2023, 9, 13),
            'company_name' => 'Company Name 1',
            'invoice_number' => '20230801',
            'net' => $net,
            'tax' => $tax,
            'gross' => $gross,
        ]);

        $net = $net * 1.11;
        $tax = $net * 19 / 100;
        $gross = $net + $tax;
        DB::table('revenues')->insert([
            'billing_date' => Carbon::create(2023, 7, 10),
            'payment_date' => Carbon::create(2023, 7, 24),
            'company_name' => 'Company Name 2',
            'invoice_number' => '20230802',
            'net' => $net,
            'tax' => $tax,
            'gross' => $gross,
        ]);
    }
}
