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

    //Create a test, that fills in the create expense form, submits it and checks, if the values are stored in the database.
    public function test_create_expense(): void
    {
        $user = User::factory()->createOne();

        $expense = [
            'billing_date' => '2021-01-01',
            'payment_date' => '2021-01-02',
            'supplier_name' => 'Supplier',
            'product_name' => 'Product',
            'invoice_number' => '123',
            'net' => 100.00,
            'tax' => 19.00,
            'gross' => 119.00,
            'cost_type' => 4,
        ];

        $this->actingAs($user)
            ->post('expense/', $expense);

        $this->assertDatabaseHas('expenses', $expense);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->seed(CostTypeSeeder::class);
    }
}
