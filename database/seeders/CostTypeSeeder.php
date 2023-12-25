<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! DB::table('cost_types')->where('id', 1)->exists()) {
            DB::table('cost_types')->insert([
                'id' => 1,
                'short_name' => 'EDV',
                'full_name' => 'Laufende EDV-Kosten',
                'elster_id' => 56,
                'color_code' => 'c6ffc2',
                'description' => 'Laufende EDV-Kosten (zum Beispiel Beratung, Wartung, Reparatur)',
                'ratio' => 1.0,
            ]);
        }

        if (! DB::table('cost_types')->where('id', 2)->exists()) {
            DB::table('cost_types')->insert([
                'id' => 2,
                'short_name' => 'GWG',
                'full_name' => 'Geringwertige Wirtschaftsgüter',
                'elster_id' => 43,
                'color_code' => 'e4f0ff',
                'description' => 'Aufwendungen für geringwertige Wirtschaftsgüter nach § 6 Absatz 2 EStG',
                'ratio' => 1.0,
            ]);
        }

        if (! DB::table('cost_types')->where('id', 3)->exists()) {
            DB::table('cost_types')->insert([
                'id' => 3,
                'short_name' => 'Inst',
                'full_name' => 'Erhaltungsaufwendungen',
                'elster_id' => 54,
                'color_code' => 'fffecc',
                'description' => 'Erhaltungsaufwendungen (zum Beispiel Kosten für Instandhaltung, Wartung oder Reparaturen; ohne solche für Gebäude und Kraftfahrzeuge)',
                'ratio' => 1.0,
            ]);
        }

        if (! DB::table('cost_types')->where('id', 4)->exists()) {
            DB::table('cost_types')->insert([
                'id' => 4,
                'short_name' => 'BzLg',
                'full_name' => 'Bezogene Leistungen',
                'elster_id' => 27,
                'color_code' => 'efefef',
                'description' => 'Bezogene Leistungen (zum Beispiel Fremdleistungen)',
                'ratio' => 1.0,
            ]);
        }

        if (! DB::table('cost_types')->where('id', 5)->exists()) {
            DB::table('cost_types')->insert([
                'id' => 5,
                'short_name' => 'ArbM',
                'full_name' => 'Arbeitsmittel',
                'elster_id' => 57,
                'color_code' => 'fce4d6',
                'description' => 'Arbeitsmittel (zum Beispiel Bürobedarf, Porto, Fachliteratur)',
                'ratio' => 1.0,
            ]);
        }

        if (! DB::table('cost_types')->where('id', 6)->exists()) {
            DB::table('cost_types')->insert([
                'id' => 6,
                'short_name' => 'AfA',
                'full_name' => 'Absetzung für Abnutzung',
                'elster_id' => 31,
                'color_code' => 'd8e4f2',
                'description' => 'AfA auf bewegliche Wirtschaftsgüter (Übertrag aus Zeile 13 der Anlage AVEÜR)',
                'ratio' => 1.0,
            ]);
        }

        if (! DB::table('cost_types')->where('id', 7)->exists()) {
            DB::table('cost_types')->insert([
                'id' => 7,
                'short_name' => 'ÜnRk',
                'full_name' => 'Übernachtungs- und Reisekosten',
                'elster_id' => 50,
                'color_code' => 'ff978c',
                'description' => 'Übernachtungs- und Reisenebenkosten bei Geschäftsreisen des Steuerpflichtigen',
                'ratio' => 1.0,
            ]);
        }

        if (! DB::table('cost_types')->where('id', 8)->exists()) {
            DB::table('cost_types')->insert([
                'id' => 8,
                'short_name' => 'Tel.5',
                'full_name' => 'Aufwendungen für Telekommunikation (50 %)',
                'elster_id' => 49,
                'color_code' => 'ff7dff',
                'description' => 'Aufwendungen für Telekommunikation (zum Beispiel Telefon, Internet)',
                'ratio' => 0.5,
            ]);
        }

        if (! DB::table('cost_types')->where('id', 9)->exists()) {
            DB::table('cost_types')->insert([
                'id' => 9,
                'short_name' => 'F-Ust',
                'full_name' => 'An Finanzamt gezahlte Umsatzsteuer',
                'elster_id' => 64,
                'color_code' => '91b2ff',
                'description' => 'An das Finanzamt gezahlte und gegebenenfalls verrechnete Umsatzsteuer',
                'ratio' => 1.0,
            ]);
        }
    }
}
