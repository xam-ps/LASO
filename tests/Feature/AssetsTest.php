<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Database\Seeders\CostTypeSeeder;
use Tests\TestCase;

class AssetsTest extends TestCase
{
    public function test_asset_page_shows_assets(): void
    {
        $user = User::factory()->create();
        $exp1 = Expense::factory()->specificTypeYearsBack(6, 0)->create();

        $assetPage = $this->actingAs($user)
            ->get('/asset');

        $assetPage->assertSee($exp1->product_name);
        $assetPage->assertStatus(200);

        $exp1->delete();
        $user->delete();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->seed(CostTypeSeeder::class);
    }
}
