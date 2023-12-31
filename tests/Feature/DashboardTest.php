<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Revenue;
use App\Models\User;
use Illuminate\Support\Number;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_a_guest_user_should_get_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_dashboard_can_be_loaded_for_specific_year(): void
    {
        $user = User::factory()->create();

        $dashboardPage = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/2019');
        $dashboardPage->assertSee('Einnahmen 2019');
        $dashboardPage->assertSee('Ausgaben 2019');
        $dashboardPage->assertStatus(200);
    }

    public function test_dashboard_revenue_sums_are_adding_up(): void
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
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }
}
