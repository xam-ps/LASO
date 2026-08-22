<?php

namespace Tests\Feature;

use App\Models\TravelAllowance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Number;
use Tests\TestCase;

class TravelAllowanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_travel_allowance_page_is_loaded(): void
    {
        $user = User::factory()->createOne();
        $travel = TravelAllowance::factory()->create();

        $assetPage = $this->actingAs($user)
            ->get('/travel-allowance');

        $assetPage->assertSee($travel->destination);
        $assetPage->assertStatus(200);
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
    }

    public function test_create_travel_allowance_page_is_loaded(): void
    {
        $user = User::factory()->createOne();

        $createTravelAllowancePage = $this->actingAs($user)
            ->get('/travel-allowance/create');

        $createTravelAllowancePage->assertSee('Neue Fahrt anlegen');
        $createTravelAllowancePage->assertStatus(200);
    }

    public function test_refund_is_calculated_from_distance_on_the_server(): void
    {
        $user = User::factory()->createOne();

        $response = $this->actingAs($user)->post('/travel-allowance', [
            'travel_date' => '2026-07-01',
            'start' => '09:00',
            'end' => '10:00',
            'destination' => 'Server calculation test',
            'reason' => 'Kundentermin',
            'company_name' => 'Example GmbH',
            'distance' => 42,
            'notes' => 'Hin- und Rueckfahrt',
            'refund' => '999.99',
        ]);

        $this->assertDatabaseHas('travel_allowances', [
            'distance' => 42,
            'refund' => '12.60',
        ]);
        $response->assertRedirect('/travel-allowance');
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
    }

    public function test_deleting_travel_allowance_is_working(): void
    {
        $user = User::factory()->createOne();
        $travel = TravelAllowance::factory()->create();

        $response = $this->actingAs($user)
            ->delete('/travel-allowance/'.$travel->id);

        $this->assertDatabaseMissing('travel_allowances', [
            'id' => $travel->id,
        ]);
        $response->assertRedirect('/travel-allowance');
    }
}
