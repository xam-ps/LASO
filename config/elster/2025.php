<?php

/*
|--------------------------------------------------------------------------
| Zeilennummern der Anlage EÜR 2025
|--------------------------------------------------------------------------
|
| Zuordnung der LASO-Positionen zu den Zeilen des amtlichen Vordrucks.
| Siehe AGENTS.md ("ELSTER-Zeilenzuordnung pflegen") zum Aktualisieren.
|
*/

return [
    'form_year' => 2025,

    'source' => 'https://www.elster.de/eportal/helpGlobal?themaGlobal=help_euer_ufa_77_2025',

    'fetched_at' => '2026-08-12',

    // true = die Zeilennummern wurden gegen den amtlichen Vordruck geprüft.
    'confirmed' => true,

    'lines' => [
        // Betriebseinnahmen
        'revenue_net' => 15,    // euerUStBE       Umsatzsteuerpflichtige Betriebseinnahmen
        'revenue_vat' => 17,    // euerUStUnentg   Vereinnahmte Umsatzsteuer
        'vat_refund' => 18,     // euerUStFA       Vom Finanzamt erstattete Umsatzsteuer

        // Betriebsausgaben (Kostentypen, Schlüssel = cost_types.short_name)
        'BzLg' => 29,           // euerFremdlstg   Bezogene Leistungen
        'AfA' => 33,            // manuell         AfA auf bewegliche Wirtschaftsgüter
        'GWG' => 36,            // euerGWG         Geringwertige Wirtschaftsgüter
        'Tel.5' => 43,          // manuell         Aufwendungen für Telekommunikation
        'ÜnRk' => 44,           // euerReiseneben  Übernachtungs- und Reisenebenkosten
        'Inst' => 48,           // manuell         Erhaltungsaufwendungen
        'EDV' => 50,            // manuell         Laufende EDV-Kosten
        'ArbM' => 51,           // manuell         Arbeitsmittel
        'vorsteuer' => 57,      // euerVoSt        Gezahlte Vorsteuerbeträge
        'F-Ust' => 58,          // euerUSt         An das Finanzamt gezahlte Umsatzsteuer

        // Weitere Positionen
        'travel' => 71,         // euerKfzNutzEinl Fahrtkosten für nicht zum BV gehörende Fahrzeuge
        'entnahmen' => 106,     // euerEntnEinl    Entnahmen
        'einlagen' => 107,      // euerEntnEinl    Einlagen
    ],
];
