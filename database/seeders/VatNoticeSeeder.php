<?php

namespace Database\Seeders;

use App\Models\VatNotice;
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
        VatNotice::factory()->count(3)->create();
    }
}
