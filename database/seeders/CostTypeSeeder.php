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
        DB::table('cost_types')->insert([
            'short_name' => 'EDV',
            'full_name' => 'Laufende EDV-Kosten',
            'elster_id' => 56,
            'color_code' => 'efffe8',
            'description' => 'Laufende EDV-Kosten (zum Beispiel Beratung, Wartung, Reparatur)',
        ]);

        DB::table('cost_types')->insert([
            'short_name' => 'GWG',
            'full_name' => 'Geringwertige Wirtschaftsgüter',
            'elster_id' => 43,
            'color_code' => 'e4f0ff',
            'description' => 'Aufwendungen für geringwertige Wirtschaftsgüter nach § 6 Absatz 2 EStG',
        ]);

        DB::table('cost_types')->insert([
            'short_name' => 'Inst',
            'full_name' => 'Erhaltungsaufwendungen',
            'elster_id' => 54,
            'color_code' => 'fffecc',
            'description' => 'Erhaltungsaufwendungen (zum Beispiel Kosten für Instandhaltung, Wartung oder Reparaturen; ohne solche für Gebäude und Kraftfahrzeuge)',
        ]);

        DB::table('cost_types')->insert([
            'short_name' => 'BzLg',
            'full_name' => 'Bezogene Leistungen',
            'elster_id' => 27,
            'color_code' => 'efefef',
            'description' => 'Bezogene Leistungen (zum Beispiel Fremdleistungen)',
        ]);

        DB::table('cost_types')->insert([
            'short_name' => 'ArbM',
            'full_name' => 'Arbeitsmittel',
            'elster_id' => 57,
            'color_code' => 'fce4d6',
            'description' => 'Arbeitsmittel (zum Beispiel Bürobedarf, Porto, Fachliteratur)',
        ]);

        DB::table('cost_types')->insert([
            'short_name' => 'AfA',
            'full_name' => 'Absetzung für Abnutzung',
            'elster_id' => 31,
            'color_code' => 'd8e4f2',
            'description' => 'AfA auf bewegliche Wirtschaftsgüter (Übertrag aus Zeile 13 der Anlage AVEÜR)',
        ]);

        DB::table('cost_types')->insert([
            'short_name' => 'ÜnRk',
            'full_name' => 'Übernachtungs- und Reisekosten',
            'elster_id' => 50,
            'color_code' => 'fd91ff',
            'description' => 'Übernachtungs- und Reisenebenkosten bei Geschäftsreisen des Steuerpflichtigen',
        ]);
    }
}
