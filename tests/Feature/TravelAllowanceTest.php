<?php

namespace Tests\Feature;

use App\Models\TravelAllowance;
use App\Models\User;
use Carbon\Carbon;
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
        $assetPage->assertSee(route('travel-allowance.copy', ['travelAllowance' => $travel->id]));
        $assetPage->assertSee('Als neue Fahrt übernehmen');
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
        $createTravelAllowancePage->assertSee('action="'.route('travel-allowance.store').'"', false);
        $createTravelAllowancePage->assertDontSee('name="_method"', false);
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

    public function test_trip_can_be_used_as_template_for_new_trip(): void
    {
        $this->travelTo(Carbon::parse('2026-10-20 10:00:00'));
        $travel = TravelAllowance::factory()->create([
            'travel_date' => '2024-05-07',
            'start' => '08:15:00',
            'end' => '09:45:00',
            'destination' => 'Testziel',
            'reason' => 'Kundentermin & Planung',
            'company' => 'Example GmbH',
            'distance' => 42,
            'notes' => 'Hin- und Rueckfahrt',
            'refund' => '12.60',
        ]);
        $originalAttributes = $travel->fresh()->getAttributes();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('travel-allowance.copy', ['travelAllowance' => $travel->id]));

        $response->assertOk()
            ->assertSee('Neue Fahrt anlegen')
            ->assertSee('action="'.route('travel-allowance.store').'"', false)
            ->assertDontSee('name="_method"', false);

        foreach (['2026-10-20', '08:15', '09:45', 'Testziel', 'Kundentermin & Planung', 'Example GmbH', '42', 'Hin- und Rueckfahrt', '12.60'] as $value) {
            $response->assertSee('value="'.e($value).'"', false);
        }

        $draft = $response->viewData('travelAllowance');
        $this->assertFalse($draft->exists);
        $this->assertNull($draft->id);
        $this->assertDatabaseCount('travel_allowances', 1);
        $this->assertSame($originalAttributes, $travel->fresh()->getAttributes());

        $this->post(route('travel-allowance.store'), [
            'travel_date' => $draft->travel_date,
            'start' => Carbon::parse($draft->start)->format('H:i'),
            'end' => Carbon::parse($draft->end)->format('H:i'),
            'destination' => $draft->destination,
            'reason' => $draft->reason,
            'company_name' => $draft->company,
            'distance' => $draft->distance,
            'notes' => $draft->notes,
            'refund' => '999.99',
        ])->assertRedirect(route('travel-allowance.index'));

        $this->assertDatabaseCount('travel_allowances', 2);
        $copy = TravelAllowance::where('id', '!=', $travel->id)->sole();
        $this->assertSame('2026-10-20', $copy->travel_date);
        $this->assertSame(
            $travel->only(['start', 'end', 'destination', 'reason', 'company', 'distance', 'notes', 'refund']),
            $copy->only(['start', 'end', 'destination', 'reason', 'company', 'distance', 'notes', 'refund']),
        );
        $this->assertSame($originalAttributes, $travel->fresh()->getAttributes());
    }

    public function test_copying_trip_preserves_empty_optional_fields(): void
    {
        $travel = TravelAllowance::factory()->create(['company' => null, 'notes' => null]);

        $this->actingAs(User::factory()->create())
            ->get(route('travel-allowance.copy', ['travelAllowance' => $travel->id]))
            ->assertOk()
            ->assertViewHas('travelAllowance', fn ($draft) => $draft->company === null && $draft->notes === null);
    }

    public function test_copying_missing_trip_returns_not_found(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('travel-allowance.copy', ['travelAllowance' => 999999]))
            ->assertNotFound();
    }

    public function test_copying_trip_requires_authentication(): void
    {
        $travel = TravelAllowance::factory()->create();

        $this->get(route('travel-allowance.copy', ['travelAllowance' => $travel->id]))
            ->assertRedirect(route('login'));
    }

    public function test_validation_errors_keep_edits_to_copied_trip(): void
    {
        $travel = TravelAllowance::factory()->create();
        $copyUrl = route('travel-allowance.copy', ['travelAllowance' => $travel->id]);

        $this->actingAs(User::factory()->create())
            ->from($copyUrl)
            ->post(route('travel-allowance.store'), [
                'travel_date' => '2026-10-19',
                'start' => '08:15',
                'end' => '09:45',
                'destination' => 'Changed destination',
                'reason' => 'Kundentermin',
                'company_name' => 'Changed company',
                'distance' => 0,
                'notes' => null,
            ])
            ->assertRedirect($copyUrl)
            ->assertSessionHasErrors('distance');

        $this->get($copyUrl)
            ->assertOk()
            ->assertSee('value="2026-10-19"', false)
            ->assertSee('value="Changed destination"', false)
            ->assertSee('value="Changed company"', false)
            ->assertSee('value="0"', false)
            ->assertDontSee('name="_method"', false);

        $this->assertDatabaseCount('travel_allowances', 1);
    }

    public function test_edit_travel_allowance_page_is_loaded(): void
    {
        $user = User::factory()->createOne();
        $travel = TravelAllowance::factory()->create();

        $editTravelAllowancePage = $this->actingAs($user)
            ->get('/travel-allowance/edit/'.$travel->id);

        $editTravelAllowancePage->assertSee('Fahrt bearbeiten');
        $editTravelAllowancePage->assertSee($travel->destination);
        $editTravelAllowancePage->assertSee('action="'.route('travel-allowance.update', ['id' => $travel->id]).'"', false);
        $editTravelAllowancePage->assertSee('name="_method" value="PUT"', false);
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
