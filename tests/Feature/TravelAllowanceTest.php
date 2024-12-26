<?php

namespace Tests\Feature;

use App\Models\TravelAllowance;
use App\Models\User;
use Illuminate\Support\Number;
use Tests\TestCase;

class TravelAllowanceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_travel_allowance_page(): void
    {
        $user = User::factory()->createOne();
        $travel = TravelAllowance::factory()->create();

        $assetPage = $this->actingAs($user)
            ->get('/travel-allowance');

        $assetPage->assertSee($travel->destination);
        $assetPage->assertStatus(200);

        $travel->delete();
    }

    public function test_travel_allowance_sum_is_correct(): void
    {
        $user = User::factory()->createOne();
        $travel1 = TravelAllowance::factory()->create();
        $travel2 = TravelAllowance::factory()->create();
        $travelSum = $travel1->refund + $travel2->refund;

        $assetPage = $this->actingAs($user)
            ->get('/travel-allowance');

        $assetPage->assertSee(Number::currency($travelSum, in: 'EUR', locale: 'de'));
        $assetPage->assertStatus(200);

        $travel1->delete();
        $travel2->delete();
    }
}
