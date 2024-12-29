<?php

namespace Tests\Feature;

use App\Models\TravelAllowance;
use App\Models\User;
use Illuminate\Support\Number;
use Tests\TestCase;

class TravelAllowanceTest extends TestCase
{
    public function test_travel_allowance_page_is_loaded(): void
    {
        $user = User::factory()->createOne();
        $travel = TravelAllowance::factory()->create();

        $assetPage = $this->actingAs($user)
            ->get('/travel-allowance');

        $assetPage->assertSee($travel->destination);
        $assetPage->assertStatus(200);

        $travel->delete();
        $user->delete();
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
        $user->delete();
    }

    public function test_create_travel_allowance_page_is_loaded(): void
    {
        $user = User::factory()->createOne();

        $createTravelAllowancePage = $this->actingAs($user)
            ->get('/travel-allowance/create');

        $createTravelAllowancePage->assertSee('Neue Fahrt anlegen');
        $createTravelAllowancePage->assertStatus(200);
        $user->delete();
    }

    public function test_edit_travel_allowance_page_is_loaded(): void
    {
        $user = User::factory()->createOne();
        $travel = TravelAllowance::factory()->create();

        $editTravelAllowancePage = $this->actingAs($user)
            ->get('/travel-allowance/edit/'.$travel->id);

        $editTravelAllowancePage->assertSee('Fahrt bearbeiten');
        $editTravelAllowancePage->assertSee($travel->destination);
        $editTravelAllowancePage->assertStatus(200);

        $travel->delete();
        $user->delete();
    }

    // public function test_deleting_travel_allowance_is_working(): void
    // {
    //     $user = User::factory()->createOne();
    //     $travel = TravelAllowance::factory()->create();

    //     $response = $this->actingAs($user)
    //         ->delete('/travel-allowance/'.$travel->id);

    //     $this->assertDatabaseMissing('travel_allowances', [
    //         'id' => $travel->id,
    //     ]);
    //     $response->assertRedirect('/travel-allowance');

    //     $user->delete();
    //     $travel->delete();
    // }
}
