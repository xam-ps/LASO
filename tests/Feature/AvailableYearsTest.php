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
use PHPUnit\Framework\Attributes\DataProvider;
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
            [2018, 2019, 2022, 2023, 2024, 2025, 2026],
            app(AvailableYears::class)->get(2019)->all(),
        );
    }

    public function test_it_excludes_depreciation_only_years_but_keeps_the_purchase_year(): void
    {
        Carbon::setTestNow('2026-07-15');

        Expense::factory()->create([
            'billing_date' => '2019-07-01',
            'payment_date' => '2019-07-01',
            'cost_type_id' => CostType::where('short_name', 'AfA')->value('id'),
            'depreciation' => 10,
            'net' => 1200,
        ]);

        $this->assertSame(
            [2019, 2026],
            app(AvailableYears::class)->get(2026)->all(),
        );
    }

    public function test_an_empty_installation_keeps_the_current_and_selected_years(): void
    {
        Carbon::setTestNow('2026-07-15');

        $this->assertSame([2026], app(AvailableYears::class)->get(2026)->all());
        $this->assertSame([2021, 2026], app(AvailableYears::class)->get(2021)->all());
    }

    #[DataProvider('yearNavigationLocations')]
    public function test_all_screens_use_record_years_without_depreciation_expansion(string $location): void
    {
        Carbon::setTestNow('2026-07-15');

        Expense::factory()->create([
            'billing_date' => '2019-07-01',
            'payment_date' => '2019-07-01',
            'cost_type_id' => CostType::where('short_name', 'AfA')->value('id'),
            'depreciation' => 10,
            'net' => 1200,
        ]);
        VatNotice::factory()->create(['notice_date' => '2020-01-01']);
        TravelAllowance::factory()->create(['travel_date' => '2021-06-01']);
        Revenue::factory()->create([
            'billing_date' => '2022-06-01',
            'payment_date' => '2022-06-01',
        ]);
        Expense::factory()->create([
            'billing_date' => '2023-06-01',
            'payment_date' => '2023-06-01',
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route($location.'.index'))
            ->assertOk()
            ->assertViewHas('years', fn ($years) => $years->all() === [2019, 2020, 2021, 2022, 2023, 2026])
            ->assertSee('href="'.route($location.'.year', ['year' => 2019]).'"', false)
            ->assertDontSee('href="'.route($location.'.year', ['year' => 2024]).'"', false);
    }

    public static function yearNavigationLocations(): array
    {
        return [
            'dashboard' => ['dashboard'],
            'statement' => ['statement'],
            'VAT notices' => ['vat-notice'],
            'travel allowances' => ['travel-allowance'],
        ];
    }

    public function test_a_directly_selected_depreciation_only_statement_still_includes_afa(): void
    {
        Carbon::setTestNow('2026-07-15');

        Expense::factory()->create([
            'billing_date' => '2019-07-01',
            'payment_date' => '2019-07-01',
            'cost_type_id' => CostType::where('short_name', 'AfA')->value('id'),
            'depreciation' => 10,
            'net' => 1200,
        ]);

        $this->actingAs(User::factory()->create())
            ->get('/statement/2021')
            ->assertOk()
            ->assertViewHas('years', fn ($years) => $years->all() === [2019, 2021, 2026])
            ->assertViewHas('costs', fn ($costs) => $costs->firstWhere('elster_key', 'AfA')->total_net == 120)
            ->assertViewHas('expTotal', 120)
            ->assertViewHas('profit', -120);
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
