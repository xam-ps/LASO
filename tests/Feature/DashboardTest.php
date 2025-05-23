<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Expense;
use App\Models\Revenue;
use App\Models\User;
use Database\Seeders\CostTypeSeeder;
use Illuminate\Support\Number;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_a_guest_user_should_get_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_dashboard_can_be_loaded_for_specific_year(): void
    {
        $user = User::factory()->create();

        $dashboardPage = $this->actingAs($user)
            ->get('/2019');
        $dashboardPage->assertSee('Einnahmen 2019');
        $dashboardPage->assertSee('Ausgaben 2019');
        $dashboardPage->assertStatus(200);
    }

    public function test_dashboard_revenues_sums_are_adding_up(): void
    {
        $user = User::factory()->create();

        $rev1 = Revenue::factory()->create();
        $rev2 = Revenue::factory()->create();
        $netSum = $rev1['net'] + $rev2['net'];
        $taxSum = $rev1['tax'] + $rev2['tax'];
        $grossSum = $rev1['gross'] + $rev2['gross'];

        $dashboardPage = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/');
        $dashboardPage->assertSee(Number::currency($netSum, in: 'EUR', locale: 'de'));
        $dashboardPage->assertSee(Number::currency($taxSum, in: 'EUR', locale: 'de'));
        $dashboardPage->assertSee(Number::currency($grossSum, in: 'EUR', locale: 'de'));
        $dashboardPage->assertStatus(200);

        $rev1->delete();
        $rev2->delete();
    }

    public function test_dashboard_expenses_sums_are_adding_up(): void
    {
        $user = User::factory()->create();

        $exp1 = Expense::factory()->create();
        $exp2 = Expense::factory()->create();
        $netSum = $exp1['net'] + $exp2['net'];
        $taxSum = $exp1['tax'] + $exp2['tax'];
        $grossSum = $exp1['gross'] + $exp2['gross'];

        $dashboardPage = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/');
        $dashboardPage->assertSee('<span>'.Number::currency($netSum, in: 'EUR', locale: 'de').'</span>', false);
        $dashboardPage->assertSee(Number::currency($taxSum, in: 'EUR', locale: 'de'));
        $dashboardPage->assertSee(Number::currency($grossSum, in: 'EUR', locale: 'de'));
        $dashboardPage->assertStatus(200);

        $exp1->delete();
        $exp2->delete();
    }

    public function test_revenue_is_shown_on_dashboard(): void
    {
        $user = User::factory()->createOne();
        $rev = Revenue::factory()->createOne();

        $dashboardPage = $this->actingAs($user)
            ->get('/');

        $dashboardPage->assertSee($rev->company_name);
        $dashboardPage->assertStatus(200);

        $rev->delete();
    }

    public function test_expense_is_shown_on_dashboard(): void
    {
        $user = User::factory()->createOne();
        $exp = Expense::factory()->createOne();

        $dashboardPage = $this->actingAs($user)
            ->get('/');

        $dashboardPage->assertSee($exp->product_name);
        $dashboardPage->assertStatus(200);

        $exp->delete();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->seed(CostTypeSeeder::class);
    }
}
