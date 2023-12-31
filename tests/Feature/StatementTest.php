<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Tests\TestCase;

class StatementTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_statement_page_is_loaded(): void
    {
        $user = User::factory()->create();

        $assetPage = $this->actingAs($user)
            ->get('/statement');

        $assetPage->assertSeeInOrder([
            'Einnahmen',
            'Ausgaben',
            'Fahrtkosten',
            'Jahresergebnis',
            'Entnahmen und Einlagen',
        ]);
        $assetPage->assertStatus(200);
    }

    public function test_statement_page_shows_expenses(): void
    {
        $user = User::factory()->create();
        $exp = Expense::factory()->specificTypeYearsBack(2, 0)->create();

        $assetPage = $this->actingAs($user)
            ->get('/statement');

        $assetPage->assertSee('Geringwertige Wirtschaftsgüter');
        $assetPage->assertStatus(200);

        $exp->delete();
    }
}
