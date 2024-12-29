<?php

namespace Tests\Feature;

use App\Models\Revenue;
use App\Models\User;
use Tests\TestCase;

class RevenueTest extends TestCase
{
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
            ->get('/revenue/edit/'.$revenue->id);

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
            ->delete('/revenue/'.$revenue->id);

        $this->assertDatabaseMissing('revenues', [
            'id' => $revenue->id,
        ]);
        $response->assertRedirect('/');

        $user->delete();
        $revenue->delete();
    }
}
