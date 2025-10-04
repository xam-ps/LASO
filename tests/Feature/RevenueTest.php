<?php

namespace Tests\Feature;

use App\Models\Revenue;
use App\Models\User;
use Database\Seeders\CostTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Number;
use Tests\TestCase;

class RevenueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CostTypeSeeder::class);
    }

    public function test_create_revenue_page_is_loaded(): void
    {
        $user = User::factory()->create();

        $revenuePage = $this->actingAs($user)
            ->get('/revenue/create');

        $revenuePage->assertSeeInOrder([
            'Rechnungsdatum',
            'Zahlungseingang',
            'Kunde',
            'Rechnungsnummer',
            'Netto',
            'Steuer',
            'Brutto',
        ]);
        $revenuePage->assertStatus(200);
    }

    public function test_store_revenue_is_working(): void
    {
        $user = User::factory()->create();
        $revenue = Revenue::factory()->makeOne();
        $revenue->billing_date = '2021-01-01';
        $revenue->payment_date = '2021-01-01';
        $revenue->invoice_number = '12345678';
        $formData = $revenue->toArray();

        $response = $this->actingAs($user)
            ->post('/revenue', $formData);

        $this->assertDatabaseHas('revenues', [
            'company_name' => $revenue->company_name,
        ]);
        $response->assertRedirect('/');

        $user->delete();
        $revenue->delete();
    }

    public function test_edit_revenue_page_is_loaded(): void
    {
        $user = User::factory()->createOne();
        $revenue = Revenue::factory()->create();

        $editRevenuePage = $this->actingAs($user)
            ->get('/revenue/edit/' . $revenue->id);

        $editRevenuePage->assertSee('Einnahme bearbeiten');
        $editRevenuePage->assertSee($revenue->company_name);
        $editRevenuePage->assertStatus(200);

        $revenue->delete();
        $user->delete();
    }

    public function test_deleting_revenue_is_working(): void
    {
        $user = User::factory()->createOne();
        $revenue = Revenue::factory()->create();

        $response = $this->actingAs($user)
            ->delete('/revenue/' . $revenue->id);

        $this->assertDatabaseMissing('revenues', [
            'id' => $revenue->id,
        ]);
        $response->assertRedirect('/');

        $user->delete();
        $revenue->delete();
    }

    public function test_overview_page_shows_correct_total_for_multiple_revenues(): void
    {
        $user = User::factory()->createOne();

        $revenues = collect([
            Revenue::factory()->create(['net' => 112.38]),
            Revenue::factory()->create(['net' => 238.47]),
            Revenue::factory()->create(['net' => 323.99]),
        ]);

        $response = $this->actingAs($user)
            ->get('');

        $totalNet = $revenues->sum('net');
        $totalTax = $revenues->sum('tax');
        $totalGross = $revenues->sum('gross');

        $formatEuro = fn($value) => Number::currency($value, in: 'EUR', locale: 'de');

        $response->assertSee($formatEuro($totalNet));
        $response->assertSee($formatEuro($totalTax));
        $response->assertSee($formatEuro($totalGross));
    }

    // A test, that checks if an error message is shown, when trying to create a revenue with a duplicate invoice number
    public function test_store_revenue_with_duplicate_invoice_number_shows_error_message(): void
    {
        $user = User::factory()->create();
        $revenue = Revenue::factory()->createOne(['invoice_number' => '12345678']);
        $formData = Revenue::factory()->makeOne(['invoice_number' => '12345678'])->toArray();
        $formData['billing_date'] = '2021-01-01';
        $formData['payment_date'] = '2021-01-01';

        $response = $this->actingAs($user)
            ->post('/revenue', $formData);

        $response->assertSessionHasErrors(['unique_column' => 'Die Rechnungsnummer existiert bereits.']);

        $revenue->delete();
        $user->delete();
    }
}
