<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The ELSTER line numbers of the Anlage EÜR change between form editions, so
 * they move out of the database into config/elster/{year}.php, keyed by the
 * cost type's short_name.
 *
 * What elster_id also did - order the rows on the statement - becomes an
 * explicit sort_order, and short_name becomes unique so it can serve as a
 * lookup key.
 */
return new class extends Migration
{
    /**
     * Maps the elster_id values shipped up to v1.2.2 (Anlage EÜR 2023) to the
     * row order of the statement, so existing installations keep the ordering
     * they had.
     */
    private const SORT_ORDER_BY_ELSTER_ID = [
        27 => 10,  // BzLg   Bezogene Leistungen
        32 => 20,  // AfA    Absetzung für Abnutzung
        35 => 30,  // GWG    Geringwertige Wirtschaftsgüter
        41 => 40,  // Tel.5  Aufwendungen für Telekommunikation
        42 => 50,  // ÜnRk   Übernachtungs- und Reisekosten
        46 => 60,  // Inst   Erhaltungsaufwendungen
        48 => 70,  // EDV    Laufende EDV-Kosten
        49 => 80,  // ArbM   Arbeitsmittel
        64 => 90,  // F-Ust  An Finanzamt gezahlte Umsatzsteuer
    ];

    public function up(): void
    {
        $this->guardAgainstDuplicateShortNames();

        Schema::table('cost_types', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('full_name');
        });

        foreach (self::SORT_ORDER_BY_ELSTER_ID as $elsterId => $sortOrder) {
            DB::table('cost_types')->where('elster_id', $elsterId)->update(['sort_order' => $sortOrder]);
        }

        // Cost types added by hand keep working and sort after the shipped ones.
        DB::table('cost_types')->where('sort_order', 0)->update(['sort_order' => 100]);

        Schema::table('cost_types', function (Blueprint $table) {
            $table->unique('short_name');
            $table->dropColumn('elster_id');
        });
    }

    public function down(): void
    {
        Schema::table('cost_types', function (Blueprint $table) {
            $table->integer('elster_id')->default(0)->after('full_name');
            $table->dropUnique(['short_name']);
        });

        foreach (self::SORT_ORDER_BY_ELSTER_ID as $elsterId => $sortOrder) {
            DB::table('cost_types')->where('sort_order', $sortOrder)->update(['elster_id' => $elsterId]);
        }

        Schema::table('cost_types', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }

    /**
     * short_name becomes the key the ELSTER line mapping is looked up by, so it
     * has to be unique. Fail with something readable instead of letting MySQL
     * throw a duplicate key error halfway through a deployment.
     */
    private function guardAgainstDuplicateShortNames(): void
    {
        $duplicates = DB::table('cost_types')
            ->select('short_name')
            ->groupBy('short_name')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('short_name');

        if ($duplicates->isNotEmpty()) {
            throw new RuntimeException(
                'cost_types.short_name must be unique before this migration can run. '.
                'Duplicate short names found: '.$duplicates->implode(', ').'. '.
                'Rename them in the database, then migrate again.'
            );
        }
    }
};
