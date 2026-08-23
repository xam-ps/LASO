# Repository Guidelines

## Project Structure & Module Organization
LASO is a Laravel 12 application for German sole-proprietor bookkeeping and EÜR preparation. Core backend code lives in `app/`, with HTTP controllers under `app/Http/Controllers` and Eloquent models under `app/Models`. Routes are defined in `routes/web.php` and `routes/auth.php`. Blade views, CSS, and small browser scripts live in `resources/views`, `resources/css`, and `resources/js`. Database migrations, factories, and seeders are in `database/`. Feature and unit tests are in `tests/Feature` and `tests/Unit`. Public assets are served from `public/`, while screenshots and README images are stored in `assets/`.

## Build, Test, and Development Commands
- `composer install` installs PHP dependencies.
- `npm install` installs Vite and frontend dependencies.
- `php artisan migrate --seed` creates schema and seeds cost types plus the initial user.
- `php artisan test` runs the PHPUnit suite.
- `npm run dev` starts the Vite dev server.
- `npm run build` builds production frontend assets.
- `./vendor/bin/pint` formats PHP code according to Laravel Pint.

Run commands inside the Sail/Docker environment (for example `docker exec laso-laravel.test-1 php artisan test`), because the expected MySQL service is only reachable from there: the `mysql` hostname in `.env` does not resolve from the host shell. This applies to the test suite in particular — the feature tests use `RefreshDatabase` against the `testing` database, so they will fail with a connection error unless run inside the container.

## Coding Style & Naming Conventions
Follow PSR-12 and Laravel conventions: 4-space indentation for PHP, StudlyCase class names, camelCase methods, and snake_case database columns. Keep controllers thin where practical and put repeated business logic into reusable methods or services. Blade component and view names should stay kebab-case, for example `travel-allowance/index.blade.php`. Use Pint for PHP formatting; keep JS and CSS changes minimal and consistent with the existing Vite/Tailwind setup.

## Testing Guidelines
Tests use PHPUnit 11 with Laravel feature tests for web flows and unit tests for isolated calculations. Name tests descriptively, for example `test_store_revenue_is_working`. Add or update tests when changing statement calculations, VAT handling, depreciation, or form validation. Prefer feature tests for controller/view behavior and unit tests for pure business logic.

## ELSTER-Zeilenzuordnung pflegen

The statement prints the Zeile of the Anlage EÜR next to every figure. Those line numbers move between form editions, so each tax year has its own mapping in `config/elster/{year}.php`, keyed by the cost type's `short_name` (`EDV`, `GWG`, `Tel.5`, …) plus a few fixed positions (`revenue_net`, `vorsteuer`, `travel`, …).

`short_name` is therefore not just a label — it is unique and load-bearing. Renaming one means renaming the key in every `config/elster/*.php`; the test suite fails until you do.

`confirmed => true` means **a human compared the numbers against the official form**. A year without a mapping is not an error: the statement then prints the captions and amounts without line numbers. That is deliberate — no number is better than a stale one.

How the missing mapping is announced depends on the year, because ELSTER publishes the Anlage EÜR for a tax year at the start of the following one:

- **current or future year** — a neutral grey note ("noch nicht veröffentlicht"). Nothing is wrong and nothing can be done, so warning every day of the running year would only teach users to ignore the banner.
- **past year** — an amber warning: the form exists, so a gap means LASO is behind and the user has to look the lines up themselves.
- **`confirmed => false`** — an amber warning plus the numbers set in red. `confirmed` is per form year, so this is all-or-nothing; there is no per-line marker.

Once a year, when the new Anlage EÜR is published:

1. `php artisan laso:elster-sync 2026` — drafts `config/elster/2026.php` from the official ELSTER Ausfüllhilfe and prints a diff against the previous year, so moved lines are obvious.
2. Fill in the five positions the Ausfüllhilfe does not number (`AfA`, `Tel.5`, `Inst`, `EDV`, `ArbM`) from the form by hand.
3. Verify every number against the official form, then set `confirmed => true`.
4. Commit and release. Users get the mapping through `git pull`.

The command is a maintainer tool: it is hidden from `artisan list`, refuses to run outside `local`, and must never be part of a user's upgrade instructions. Mappings ship with the release so that every instance on the same version shows the same numbers.

## Commit & Pull Request Guidelines
Recent history uses short imperative subjects such as `Update dependencies` and `Update CHANGELOG`. Keep commit messages concise, capitalized, and focused on one change. For pull requests, include a brief summary, note any schema or seed changes, list test coverage (`php artisan test`), and attach screenshots when UI output changes.

## Security & Configuration Tips
The default seeded login is for first-time setup only; replace it immediately in non-local environments. Keep secrets in `.env`, never commit credentials, and document any new environment variables in the README or PR description.
