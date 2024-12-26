<?php

namespace Database\Seeders;

use App\Models\VatNotice;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VatNoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vatNotices = [
            [
                'notice_date' => Carbon::now()->setMonth(4)->setDay(2),
                'vat_received' => 300,
                'vat_paid' => 120.00,
            ],
            [
                'notice_date' => Carbon::now()->setMonth(7)->setDay(4),
                'vat_received' => 200,
                'vat_paid' => 80.00,
            ],
        ];

        foreach ($vatNotices as $notice) {
            VatNotice::create($notice);
        }
    }
}
