<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Maintainer tool. Drafts config/elster/{year}.php from the official ELSTER
 * "Ausfüllhilfe zur EÜR", which is published per form year and keyed by stable
 * anchor ids (for example id="euerGWG" -> "... (Zeile 36)").
 *
 * It never runs on a user installation: the mapping ships with the release so
 * that every instance on the same version shows the same numbers, and so that
 * `confirmed => true` keeps meaning "a human compared this against the form".
 *
 * See AGENTS.md, "ELSTER-Zeilenzuordnung pflegen".
 */
class ElsterSync extends Command
{
    protected $signature = 'laso:elster-sync {year : Tax year of the Anlage EÜR} {--force : Overwrite an existing mapping}';

    protected $description = 'Maintainer only: draft the ELSTER line mapping for a tax year';

    protected $hidden = true;

    private const URL = 'https://www.elster.de/eportal/helpGlobal?themaGlobal=help_euer_ufa_77_';

    /**
     * Positions that can be read from an anchor in the Ausfüllhilfe. Keys that
     * name a cost type are its short_name; the rest are statement rows that
     * have no cost type behind them.
     *
     * The second element picks the n-th line number in the anchor title, for
     * anchors that cover a range ("Zeilen 106 und 107").
     *
     * @var array<string, array{string, int}>
     */
    private const ANCHORS = [
        'revenue_net' => ['euerUStBE', 0],
        'revenue_vat' => ['euerUStUnentg', 0],
        'vat_refund' => ['euerUStFA', 0],
        'BzLg' => ['euerFremdlstg', 0],
        'GWG' => ['euerGWG', 0],
        'ÜnRk' => ['euerReiseneben', 0],
        'vorsteuer' => ['euerVoSt', 0],
        'F-Ust' => ['euerUSt', 0],
        'travel' => ['euerKfzNutzEinl', 0],
        'entnahmen' => ['euerEntnEinl', 0],
        'einlagen' => ['euerEntnEinl', 1],
    ];

    /**
     * Positions the Ausfüllhilfe does not give a line number for - it folds
     * them into "Übrige unbeschränkt abziehbare Betriebsausgaben" prose, and
     * the AfA anchor only covers the whole 31-38 block. Read these off the
     * form by hand.
     *
     * @var array<string, string>
     */
    private const MANUAL = [
        'AfA' => 'AfA auf bewegliche Wirtschaftsgüter',
        'Tel.5' => 'Aufwendungen für Telekommunikation',
        'Inst' => 'Erhaltungsaufwendungen',
        'EDV' => 'Laufende EDV-Kosten',
        'ArbM' => 'Arbeitsmittel',
    ];

    /**
     * Order the keys are written to the config file in.
     *
     * @var array<int, string>
     */
    private const ORDER = [
        'revenue_net', 'revenue_vat', 'vat_refund',
        'BzLg', 'AfA', 'GWG', 'Tel.5', 'ÜnRk', 'Inst', 'EDV', 'ArbM', 'vorsteuer', 'F-Ust',
        'travel', 'entnahmen', 'einlagen',
    ];

    public function handle(): int
    {
        if (! app()->environment('local')) {
            $this->error('laso:elster-sync is a maintainer command and only runs in the local environment.');

            return self::FAILURE;
        }

        $year = (int) $this->argument('year');
        if ($year < 2000 || $year > 2100) {
            $this->error('Please pass a plausible tax year.');

            return self::FAILURE;
        }

        $path = config_path("elster/{$year}.php");
        if (file_exists($path) && ! $this->option('force')) {
            $this->error("config/elster/{$year}.php already exists. Pass --force to overwrite.");

            return self::FAILURE;
        }

        $url = self::URL.$year;
        $this->line("Fetching {$url}");

        $response = Http::timeout(30)->get($url);
        if (! $response->successful()) {
            $this->error("ELSTER returned HTTP {$response->status()}.");

            return self::FAILURE;
        }

        $anchors = $this->parseAnchors($response->body());

        if ($anchors === []) {
            $this->error('No "euer*" anchors found. The form for this year is probably not published yet,');
            $this->error('or ELSTER changed the page markup - check the page before trusting this command.');

            return self::FAILURE;
        }

        $this->line(sprintf('Found %d anchors.', count($anchors)));

        $lines = [];
        $missing = [];
        foreach (self::ANCHORS as $key => [$anchor, $index]) {
            $line = $anchors[$anchor][$index] ?? null;
            $lines[$key] = $line;
            if ($line === null) {
                $missing[] = "{$key} ({$anchor})";
            }
        }
        foreach (self::MANUAL as $key => $label) {
            $lines[$key] = null;
        }

        if ($missing !== []) {
            $this->warn('Anchors not found: '.implode(', ', $missing));
        }

        $this->renderDiff($year, $lines);

        file_put_contents($path, $this->render($year, $url, $lines));

        $this->newLine();
        $this->info("Wrote config/elster/{$year}.php with confirmed => false.");
        $this->line('Next: fill in the manual positions from the form, verify every number,');
        $this->line('then set confirmed => true and commit.');

        return self::SUCCESS;
    }

    /**
     * @return array<string, array<int, int>> anchor id => line numbers in its title
     */
    private function parseAnchors(string $html): array
    {
        // Anchor the match on the toggleBox head that must directly follow the
        // id, so a section without its own heading cannot pick up the title of
        // the next one. Anchors missing from an edition must surface as gaps.
        $pattern = '/id="(euer[^"]*)"\s*><h[2-6] class="toggleBox__head".*?<span class="toggleBox__title">(.*?)<\/span>/s';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);

        $anchors = [];
        foreach ($matches as [, $id, $rawTitle]) {
            $title = html_entity_decode(strip_tags($rawTitle));
            if (preg_match('/\(Zeilen?\s+(\d+)(?:\s+(?:bis|und)\s+(\d+))?\)/u', $title, $found)) {
                $anchors[$id] = array_map('intval', array_slice($found, 1));
            }
        }

        return $anchors;
    }

    /**
     * @param  array<string, int|null>  $lines
     */
    private function renderDiff(int $year, array $lines): void
    {
        $previous = config('elster.'.($year - 1).'.lines');

        $rows = [];
        foreach (self::ORDER as $key) {
            $old = is_array($previous) ? ($previous[$key] ?? null) : null;
            $new = $lines[$key] ?? null;
            $note = match (true) {
                array_key_exists($key, self::MANUAL) => 'manual - fill in by hand',
                $old === null || $new === null => '',
                $old !== $new => 'MOVED',
                default => '',
            };
            $rows[] = [$key, $old ?? '-', $new ?? '-', $note];
        }

        $this->newLine();
        $this->table([' key', ($year - 1), $year, ''], $rows);
    }

    /**
     * @param  array<string, int|null>  $lines
     */
    private function render(int $year, string $url, array $lines): string
    {
        $entries = '';
        foreach (self::ORDER as $key) {
            $value = $lines[$key] ?? null;
            $entry = sprintf("        '%s' => %s,", $key, $value === null ? 'null' : $value);

            if (array_key_exists($key, self::MANUAL)) {
                // pad on characters, not bytes - keys like 'ÜnRk' are multibyte
                $entry .= str_repeat(' ', max(1, 32 - mb_strlen($entry)))
                    .'// TODO manuell aus dem Vordruck: '.self::MANUAL[$key];
            }

            $entries .= $entry."\n";
        }

        return <<<PHP
        <?php

        /*
        |--------------------------------------------------------------------------
        | Zeilennummern der Anlage EÜR {$year}
        |--------------------------------------------------------------------------
        |
        | Entwurf von "php artisan laso:elster-sync {$year}".
        | Siehe AGENTS.md ("ELSTER-Zeilenzuordnung pflegen") zum Aktualisieren.
        |
        */

        return [
            'form_year' => {$year},

            'source' => '{$url}',

            'fetched_at' => '{$this->today()}',

            // true = die Zeilennummern wurden gegen den amtlichen Vordruck geprüft.
            'confirmed' => false,

            'lines' => [
        {$entries}    ],
        ];

        PHP;
    }

    private function today(): string
    {
        return now()->toDateString();
    }
}
