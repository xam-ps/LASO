<?php

namespace Tests\Feature;

use App\Models\CostType;
use App\Models\Expense;
use App\Models\Revenue;
use App\Models\TravelAllowance;
use App\Models\User;
use App\Models\VatNotice;
use App\Services\AvailableYears;
use Carbon\Carbon;
use Database\Seeders\CostTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailableYearsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_sorted_global_years_from_bookkeeping_records(): void
    {
        Carbon::setTestNow('2026-07-15');

        Revenue::factory()->create([
            'billing_date' => Carbon::parse('2021-12-20'),
            'payment_date' => Carbon::parse('2022-01-10'),
        ]);
        Revenue::factory()->create([
            'billing_date' => Carbon::parse('2023-03-01'),
            'payment_date' => null,
        ]);
        Expense::factory()->create([
            'billing_date' => '2020-12-20',
            'payment_date' => '2024-01-10',
        ]);
        TravelAllowance::factory()->create(['travel_date' => '2025-06-01']);
        VatNotice::factory()->create(['notice_date' => '2018-01-01']);

        $this->assertSame(
            [2019, 2022, 2023, 2024, 2025, 2026],
            app(AvailableYears::class)->get(2019)->all(),
        );
    }

    public function test_it_includes_only_years_with_positive_depreciation(): void
    {
        Carbon::setTestNow('2030-07-15');
        $afaCostTypeId = CostType::where('short_name', 'AfA')->value('id');

        $januaryAsset = Expense::factory()->create([
            'billing_date' => '2020-01-01',
            'payment_date' => '2020-01-01',
            'cost_type_id' => $afaCostTypeId,
            'depreciation' => 3,
            'net' => 360,
        ]);

        $this->assertSame(
            [2019, 2020, 2021, 2022, 2030],
            app(AvailableYears::class)->get(2019)->all(),
        );

        $januaryAsset->delete();

        Expense::factory()->create([
            'billing_date' => '2021-07-01',
            'payment_date' => '2021-07-01',
            'cost_type_id' => $afaCostTypeId,
            'depreciation' => 2,
            'net' => 240,
        ]);

        Carbon::setTestNow('2022-07-15');

        $this->assertSame(
            [2019, 2021, 2022],
            app(AvailableYears::class)->get(2019)->all(),
        );
    }

    public function test_unpaid_dashboard_records_only_appear_in_their_billing_year(): void
    {
        $user = User::factory()->create();
        $revenue = Revenue::factory()->create([
            'billing_date' => Carbon::parse('2022-06-01'),
            'payment_date' => null,
            'company_name' => 'Unpaid year test customer',
        ]);

        $this->actingAs($user)
            ->get('/2022')
            ->assertOk()
            ->assertSee($revenue->company_name);

        $this->actingAs($user)
            ->get('/2023')
            ->assertOk()
            ->assertDontSee($revenue->company_name);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CostTypeSeeder::class);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }
}
