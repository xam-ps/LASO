<?php

/*
|--------------------------------------------------------------------------
| Zeilennummern der Anlage EÜR 2023
|--------------------------------------------------------------------------
|
| Zuordnung der LASO-Positionen zu den Zeilen des amtlichen Vordrucks.
| Siehe AGENTS.md ("ELSTER-Zeilenzuordnung pflegen") zum Aktualisieren.
|
*/

return [
    'form_year' => 2023,

    'source' => 'https://www.elster.de/eportal/helpGlobal?themaGlobal=help_euer_ufa_77_2023',

    'fetched_at' => '2026-08-12',

    // true = die Zeilennummern wurden gegen den amtlichen Vordruck geprüft.
    'confirmed' => true,

    'lines' => [
        // Betriebseinnahmen
        'revenue_net' => 14,    // euerUStBE       Umsatzsteuerpflichtige Betriebseinnahmen
        'revenue_vat' => 16,    // euerUStUnentg   Vereinnahmte Umsatzsteuer
        'vat_refund' => 17,     // euerUStFA       Vom Finanzamt erstattete Umsatzsteuer

        // Betriebsausgaben (Kostentypen, Schlüssel = cost_types.short_name)
        'BzLg' => 27,           // euerFremdlstg   Bezogene Leistungen
        'AfA' => 32,            // manuell         AfA auf bewegliche Wirtschaftsgüter
        'GWG' => 35,            // euerGWG         Geringwertige Wirtschaftsgüter
        'Tel.5' => 41,          // manuell         Aufwendungen für Telekommunikation
        'ÜnRk' => 42,           // euerReiseneben  Übernachtungs- und Reisenebenkosten
        'Inst' => 46,           // manuell         Erhaltungsaufwendungen
        'EDV' => 48,            // manuell         Laufende EDV-Kosten
        'ArbM' => 49,           // manuell         Arbeitsmittel
        'vorsteuer' => 55,      // euerVoSt        Gezahlte Vorsteuerbeträge
        'F-Ust' => 56,          // euerUSt         An das Finanzamt gezahlte Umsatzsteuer

        // Weitere Positionen
        'travel' => 68,         // euerKfzNutzEinl Fahrtkosten für nicht zum BV gehörende Fahrzeuge
        'entnahmen' => 106,     // euerEntnEinl    Entnahmen
        'einlagen' => 107,      // euerEntnEinl    Einlagen
    ],
];
