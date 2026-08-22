<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Expense;
use App\Models\User;
use Database\Seeders\CostTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

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

        $this->assertDatabaseHas('expenses', [
            'supplier_name' => $expense->supplier_name,
        ]);
        $response->assertRedirect('/');
    }

    public function test_afa_expenses_require_a_depreciation_period_between_one_and_thirty_years(): void
    {
        $user = User::factory()->create();

        foreach ([null, 0, 31] as $depreciation) {
            $expense = Expense::factory()->makeOne();
            $formData = $expense->toArray();
            $formData['billing_date'] = '2026-07-01';
            $formData['payment_date'] = '2026-07-01';
            $formData['cost_type'] = 6;
            $formData['depreciation'] = $depreciation;

            $this->actingAs($user)
                ->post('/expense', $formData)
                ->assertSessionHasErrors('depreciation');
        }
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
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->seed(CostTypeSeeder::class);
    }
}
