<?php

namespace Tests\Feature;

use App\Models\Expense;
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

    public function test_store_expense_is_working(): void
    {
        $user = User::factory()->create();
        $expense = Expense::factory()->makeOne();
        $expense->billing_date = '2021-01-01';
        $expense->payment_date = '2021-01-01';
        $expense->invoice_number = '12345678';
        $formData = $expense->toArray();
        $formData['cost_type'] = 2;

        $response = $this->actingAs($user)
            ->post('/expense', $formData);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('expenses', [
            'supplier_name' => $expense->supplier_name,
        ]);

        $user->delete();
        $expense->delete();
    }

    public function test_edit_expense_page_is_loaded(): void
    {
        $user = User::factory()->createOne();
        $expense = Expense::factory()->create();

        $editExpensePage = $this->actingAs($user)
            ->get('/expense/edit/'.$expense->id);

        $editExpensePage->assertSee('Ausgabe bearbeiten');
        $editExpensePage->assertSee($expense->supplier_name);
        $editExpensePage->assertStatus(200);

        $expense->delete();
        $user->delete();
    }

    public function test_deleting_expense_is_working(): void
    {
        $user = User::factory()->createOne();
        $expense = Expense::factory()->create();

        $response = $this->actingAs($user)
            ->delete('/expense/'.$expense->id);

        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id,
        ]);
        $response->assertRedirect('/');

        $expense->delete();
        $user->delete();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->seed(CostTypeSeeder::class);
    }
}
