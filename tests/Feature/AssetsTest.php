<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Tests\TestCase;

class AssetsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_asset_page_shows_assets(): void
    {
        $user = User::factory()->create();

        $exp1 = Expense::factory()->specificTypeYearsBack(6, 0)->create();

        $assetPage = $this->actingAs($user)
            ->get('/asset');

        $assetPage->assertSee($exp1->product_name);
        $assetPage->assertStatus(200);
    }
}
