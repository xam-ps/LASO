<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\CostTypeSeeder;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    public function test_create_expense_page_is_loaded(): void
    {
        $user = User::factory()->createOne();

        $expensePage = $this->actingAs($user)
            ->get('/expense/create');

        $expensePage->assertSeeInOrder([
            'Rechnungsdatum',
            'Zahlungseingang',
            'Lieferant',
            'Produkt',
            'Rechnungsnummer',
            'Netto',
            'Steuer',
            'Brutto',
            'Typ',
        ]);
        $expensePage->assertStatus(200);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->seed(CostTypeSeeder::class);
    }
}
