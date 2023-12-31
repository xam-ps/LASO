<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
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

        $user->delete();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }
}
