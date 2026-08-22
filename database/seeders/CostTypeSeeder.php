<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostTypeSeeder extends Seeder
{
    /**
     * The cost types LASO ships with.
     *
     * `short_name` is not just a label: it is the key the ELSTER line mapping
     * in config/elster/{year}.php is looked up by, and it is unique. Renaming
     * one means renaming it in every mapping file too - the test suite fails
     * until you do. `sort_order` determines the row order on the statement.
     *
     * @var array<int, array<string, mixed>>
     */
    private const COST_TYPES = [
        [
            'id' => 1,
            'short_name' => 'EDV',
            'full_name' => 'Laufende EDV-Kosten',
            'sort_order' => 70,
            'color_code' => 'c6ffc2',
            'description' => 'Laufende EDV-Kosten (zum Beispiel Beratung, Wartung, Reparatur)',
            'ratio' => 1.0,
        ],
        [
            'id' => 2,
            'short_name' => 'GWG',
            'full_name' => 'Geringwertige Wirtschaftsgüter',
            'sort_order' => 30,
            'color_code' => 'e4f0ff',
            'description' => 'Aufwendungen für geringwertige Wirtschaftsgüter nach § 6 Absatz 2 EStG',
            'ratio' => 1.0,
        ],
        [
            'id' => 3,
            'short_name' => 'Inst',
            'full_name' => 'Erhaltungsaufwendungen',
            'sort_order' => 60,
            'color_code' => 'fffecc',
            'description' => 'Erhaltungsaufwendungen (zum Beispiel Kosten für Instandhaltung, Wartung oder Reparaturen; ohne solche für Gebäude und Kraftfahrzeuge)',
            'ratio' => 1.0,
        ],
        [
            'id' => 4,
            'short_name' => 'BzLg',
            'full_name' => 'Bezogene Leistungen',
            'sort_order' => 10,
            'color_code' => 'efefef',
            'description' => 'Bezogene Leistungen (zum Beispiel Fremdleistungen)',
            'ratio' => 1.0,
        ],
        [
            'id' => 5,
            'short_name' => 'ArbM',
            'full_name' => 'Arbeitsmittel',
            'sort_order' => 80,
            'color_code' => 'fce4d6',
            'description' => 'Arbeitsmittel (zum Beispiel Bürobedarf, Porto, Fachliteratur)',
            'ratio' => 1.0,
        ],
        [
            'id' => 6,
            'short_name' => 'AfA',
            'full_name' => 'Absetzung für Abnutzung',
            'sort_order' => 20,
            'color_code' => 'd8e4f2',
            'description' => 'AfA auf bewegliche Wirtschaftsgüter (Übertrag aus Zeile 13 der Anlage AVEÜR)',
            'ratio' => 1.0,
        ],
        [
            'id' => 7,
            'short_name' => 'ÜnRk',
            'full_name' => 'Übernachtungs- und Reisekosten',
            'sort_order' => 50,
            'color_code' => 'ff978c',
            'description' => 'Übernachtungs- und Reisenebenkosten bei Geschäftsreisen des Steuerpflichtigen',
            'ratio' => 1.0,
        ],
        [
            'id' => 8,
            'short_name' => 'Tel.5',
            'full_name' => 'Aufwendungen für Telekommunikation (50 %)',
            'sort_order' => 40,
            'color_code' => 'ff7dff',
            'description' => 'Aufwendungen für Telekommunikation (zum Beispiel Telefon, Internet)',
            'ratio' => 0.5,
        ],
        [
            'id' => 9,
            'short_name' => 'F-Ust',
            'full_name' => 'An Finanzamt gezahlte Umsatzsteuer',
            'sort_order' => 90,
            'color_code' => '91b2ff',
            'description' => 'An das Finanzamt gezahlte und gegebenenfalls verrechnete Umsatzsteuer',
            'ratio' => 1.0,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::COST_TYPES as $costType) {
            if (! DB::table('cost_types')->where('id', $costType['id'])->exists()) {
                DB::table('cost_types')->insert($costType);
            }
        }
    }
}
