<?php

namespace Tests\Feature;

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
}
