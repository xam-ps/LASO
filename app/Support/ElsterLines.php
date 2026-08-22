<?php

namespace App\Support;

/**
 * Resolves the ELSTER "Zeilennummern" of the Anlage EÜR for a given tax year.
 *
 * The numbers move between form editions, so every year has its own mapping in
 * config/elster/{year}.php. A year without a mapping resolves to null on
 * purpose - the statement then prints no line numbers and shows a notice
 * instead of a number that looks authoritative but is out of date.
 */
class ElsterLines
{
    /**
     * @param  array<string, int|null>  $lines
     */
    private function __construct(
        public readonly int $formYear,
        public readonly bool $confirmed,
        private readonly array $lines,
    ) {}

    /**
     * Mapping for the given tax year, or null if none has been published yet.
     */
    public static function for(mixed $year): ?self
    {
        if (! is_numeric($year)) {
            return null;
        }

        $config = config('elster.'.(int) $year);

        if (! is_array($config) || ! is_array($config['lines'] ?? null)) {
            return null;
        }

        return new self(
            (int) ($config['form_year'] ?? $year),
            (bool) ($config['confirmed'] ?? false),
            $config['lines'],
        );
    }

    /**
     * Line number for a position, or null if this edition has no mapping for it.
     *
     * $key is either a cost type's short_name or one of the fixed statement
     * positions (revenue_net, vorsteuer, travel, ...). Short names may contain
     * a dot ('Tel.5'), so read them with direct array access as done here -
     * config('elster.2025.lines.Tel.5') and data_get() split on the dot and
     * would silently return null.
     */
    public function line(string $key): ?int
    {
        $line = $this->lines[$key] ?? null;

        return is_int($line) ? $line : null;
    }

    /**
     * @return array<string, int|null>
     */
    public function all(): array
    {
        return $this->lines;
    }

    /**
     * All tax years that ship a mapping, ascending.
     *
     * @return array<int, int>
     */
    public static function availableYears(): array
    {
        $years = array_map('intval', array_keys(config('elster', [])));
        sort($years);

        return $years;
    }
}
