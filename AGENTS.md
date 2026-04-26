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

If you use Sail or a devcontainer, run commands inside that environment so the expected MySQL service is available.

## Coding Style & Naming Conventions
Follow PSR-12 and Laravel conventions: 4-space indentation for PHP, StudlyCase class names, camelCase methods, and snake_case database columns. Keep controllers thin where practical and put repeated business logic into reusable methods or services. Blade component and view names should stay kebab-case, for example `travel-allowance/index.blade.php`. Use Pint for PHP formatting; keep JS and CSS changes minimal and consistent with the existing Vite/Tailwind setup.

## Testing Guidelines
Tests use PHPUnit 11 with Laravel feature tests for web flows and unit tests for isolated calculations. Name tests descriptively, for example `test_store_revenue_is_working`. Add or update tests when changing statement calculations, VAT handling, depreciation, or form validation. Prefer feature tests for controller/view behavior and unit tests for pure business logic.

## Commit & Pull Request Guidelines
Recent history uses short imperative subjects such as `Update dependencies` and `Update CHANGELOG`. Keep commit messages concise, capitalized, and focused on one change. For pull requests, include a brief summary, note any schema or seed changes, list test coverage (`php artisan test`), and attach screenshots when UI output changes.

## Security & Configuration Tips
The default seeded login is for first-time setup only; replace it immediately in non-local environments. Keep secrets in `.env`, never commit credentials, and document any new environment variables in the README or PR description.
