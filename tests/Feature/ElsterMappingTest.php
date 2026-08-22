<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\CostType;
use App\Models\Expense;
use App\Models\Revenue;
use App\Models\User;
use App\Models\VatNotice;
use App\Support\ElsterLines;
use Carbon\Carbon;
use Database\Seeders\CostTypeSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ElsterMappingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CostTypeSeeder::class);
    }

    /**
     * A mapping that nobody has compared against the official form must never
     * reach a release - otherwise `confirmed` stops meaning anything.
     */
    public function test_every_shipped_mapping_is_confirmed(): void
    {
        $years = ElsterLines::availableYears();

        $this->assertNotEmpty($years, 'No ELSTER mappings are shipped at all.');

        foreach ($years as $year) {
            $this->assertTrue(
                ElsterLines::for($year)->confirmed,
                "config/elster/{$year}.php is still marked confirmed => false. ".
                    'Verify it against the official Anlage EÜR before shipping it.'
            );
        }
    }

    /**
     * Catches "added a cost type, forgot to give it a line number".
     */
    public function test_every_cost_type_has_a_line_in_the_newest_mapping(): void
    {
        $years = ElsterLines::availableYears();
        $newest = ElsterLines::for(end($years));

        foreach (CostType::all() as $costType) {
            $this->assertNotNull(
                $newest->line($costType->short_name),
                "Cost type '{$costType->short_name}' has no line number in ".
                    "config/elster/{$newest->formYear}.php. If you renamed it, rename ".
                    'the key in every mapping file too.'
            );
        }
    }

    /**
     * short_name is the key the mapping is looked up by, so two cost types
     * sharing one would make the lookup ambiguous.
     */
    public function test_cost_type_short_names_are_unique(): void
    {
        $this->expectException(QueryException::class);

        DB::table('cost_types')->insert([
            'short_name' => 'EDV',
            'full_name' => 'Duplicate short name',
            'sort_order' => 100,
            'color_code' => 'ffffff',
            'description' => 'Should be rejected by the unique index.',
            'ratio' => 1.0,
        ]);
    }

    public function test_statement_shows_the_line_numbers_of_the_matching_form_year(): void
    {
        $user = User::factory()->create();

        $statement = $this->actingAs($user)->get('/statement/2025');

        $statement->assertStatus(200);
        $statement->assertSee('Anlage EÜR 2025');

        // Betriebseinnahmen
        $statement->assertSee($this->confirmedCell(15, 2025), false);   // Umsatzsteuerpflichtige Betriebseinnahmen
        $statement->assertSee($this->confirmedCell(17, 2025), false);   // Vereinnahmte Umsatzsteuer
        $statement->assertSee($this->confirmedCell(18, 2025), false);   // Vom Finanzamt erstattete Umsatzsteuer
        // Betriebsausgaben
        $statement->assertSee($this->confirmedCell(57, 2025), false);   // Gezahlte Vorsteuerbeträge
        $statement->assertSee($this->confirmedCell(58, 2025), false);   // An das Finanzamt gezahlte Umsatzsteuer
        $statement->assertSee($this->confirmedCell(71, 2025), false);   // Fahrtkosten, nicht zum Betriebsvermögen
        // Entnahmen und Einlagen
        $statement->assertSee($this->confirmedCell(106, 2025), false);
        $statement->assertSee($this->confirmedCell(107, 2025), false);
    }

    public function test_statement_shows_the_older_line_numbers_for_an_older_year(): void
    {
        $user = User::factory()->create();

        $statement = $this->actingAs($user)->get('/statement/2023');

        $statement->assertStatus(200);
        $statement->assertSee('Anlage EÜR 2023');
        $statement->assertSee($this->confirmedCell(14, 2023), false);   // 2023: Betriebseinnahmen netto
        $statement->assertSee($this->confirmedCell(55, 2023), false);   // 2023: Gezahlte Vorsteuerbeträge
        $statement->assertSee($this->confirmedCell(56, 2023), false);   // 2023: An das Finanzamt gezahlte Umsatzsteuer
        $statement->assertDontSee('>57</td>', false);
    }

    /**
     * A draft mapping straight out of laso:elster-sync must look different from
     * a verified one - both in the table and above it.
     */
    public function test_an_unconfirmed_mapping_marks_every_line_and_warns(): void
    {
        $user = User::factory()->create();
        config(['elster.2025.confirmed' => false]);

        $statement = $this->actingAs($user)->get('/statement/2025');

        $statement->assertStatus(200);
        $statement->assertSee('Zeilennummern für 2025 sind ungeprüft');
        $statement->assertSee('Warnung: ');
        // the tooltip of a confirmed cell must not appear
        $statement->assertDontSee('title="Zeile 15 der Anlage EÜR 2025"', false);
        $statement->assertSee(
            'title="Zeile 15 der Anlage EÜR 2025 &ndash; noch nicht gegen den amtlichen Vordruck geprüft."',
            false
        );
        $statement->assertSee('noch nicht gegen den amtlichen Vordruck geprüft');
        // the numbers are set off in red rather than carrying a per-cell marker:
        // `confirmed` is per form year, so a marker would be on every cell or none
        $statement->assertSee('text-red-700', false);
    }

    /**
     * ELSTER publishes the Anlage EÜR for a tax year at the start of the next
     * one, so during the running year there is nothing to warn about and
     * nothing the user could do. A warning shown every day of the year is a
     * warning nobody reads by December.
     */
    public function test_a_missing_mapping_for_the_current_year_is_stated_without_a_warning(): void
    {
        Carbon::setTestNow('2026-06-15');
        $user = User::factory()->create();
        $this->assertNull(ElsterLines::for(2026));

        $statement = $this->actingAs($user)->get('/statement/2026');

        $statement->assertStatus(200);
        $statement->assertSee('noch nicht veröffentlicht');
        $statement->assertSee('zu Beginn des Jahres 2027');
        $statement->assertDontSee('Warnung: ');
        $statement->assertDontSee('>57</td>', false);
    }

    /**
     * For a year that is over, the form does exist - a missing mapping means
     * LASO is behind and the user has to look the lines up themselves.
     */
    public function test_a_missing_mapping_for_a_past_year_warns(): void
    {
        Carbon::setTestNow('2026-06-15');
        $user = User::factory()->create();
        $this->assertNull(ElsterLines::for(2022));

        $statement = $this->actingAs($user)->get('/statement/2022');

        $statement->assertStatus(200);
        $statement->assertSee('Keine Zeilennummern für 2022 hinterlegt');
        $statement->assertSee('Warnung: ');
        $statement->assertDontSee('>57</td>', false);
    }

    /**
     * The VAT paid to the Finanzamt is added to the matching cost type when the
     * user booked expenses on it. Guards the lookup that used to match on the
     * (now removed) elster_id 64 in two places.
     */
    public function test_vat_paid_row_is_not_duplicated_when_expenses_use_that_cost_type(): void
    {
        $user = User::factory()->create();
        $fust = CostType::where('short_name', 'F-Ust')->firstOrFail();

        Expense::factory()->create([
            'cost_type_id' => $fust->id,
            'payment_date' => '2025-03-10',
            'billing_date' => '2025-03-01',
            'net' => 100.00,
            'tax' => 0.00,
            'gross' => 100.00,
            'depreciation' => null,
        ]);

        VatNotice::factory()->create([
            'notice_date' => '2025-04-10',
            'vat_received' => 500.00,
            'vat_paid' => 200.00,
        ]);

        $content = $this->actingAs($user)->get('/statement/2025')->getContent();

        $this->assertSame(
            1,
            substr_count($content, $fust->full_name),
            'The "an Finanzamt gezahlte Umsatzsteuer" row must appear exactly once.'
        );
        // 100.00 booked on the cost type + 300.00 net balance of the notice
        $this->assertStringContainsString('400,00', $content);
    }

    public function test_statement_still_works_without_any_vat_notices(): void
    {
        $user = User::factory()->create();
        Revenue::factory()->create([
            'billing_date' => '2025-05-01',
            'payment_date' => '2025-05-01',
        ]);

        $statement = $this->actingAs($user)->get('/statement/2025');

        $statement->assertStatus(200);
        $statement->assertSee('An Finanzamt gezahlte Umsatzsteuer');
    }

    private function confirmedCell(int $line, int $formYear): string
    {
        return '<td title="Zeile '.$line.' der Anlage EÜR '.$formYear.'">'.$line.'</td>';
    }
}
