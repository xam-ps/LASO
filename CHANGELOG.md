# Release Notes

## [Unreleased](https://github.com/laravel/laravel/compare/v1.0.1...main)

## [v1.0.1](https://github.com/laravel/laravel/compare/v1.0.0...v1.0.1) - 2025-10-27

### Full list of changes

- Upgrade to Laravel 12

After pulling the latest codebase please run
`composer install --no-dev`
`php artisan cache:clear`
`php artisan config:clear`
`php artisan view:clear`

### For a fresh installation please have a look at the [README](https://github.com/xam-ps/LASO?tab=readme-ov-file#deployment-manually).

**Full Changelog**: https://github.com/xam-ps/LASO/compare/v1.0.0...v1.0.1

## [v1.0.0](https://github.com/laravel/laravel/compare/v0.6.4...v1.0.0) - 2025-10-04

### Finally decided to release version 1.0.0

I'm using LASO now for more than two years - so far without any problems. Also the VAT-Notice module worked without problems so far (at the moment only with hard coded 19% VAT tough).

#### Full list of changes

- Update php + js dependencies
- Add tests (testcoverage is now at 76.2%)
- Fix a smaller UI bug (not showing an error message when same invoice number is being used)
- Adjust UI for VAT-Notice to make it more obvious, which values are needed for ELSTER

After pulling the latest codebase please run
`composer install --no-dev`
`php artisan cache:clear`
`php artisan config:clear`
`php artisan view:clear`
and
`npm install`
`npm run build`
to build the frontend

#### For a fresh installation please have a look at the [README](https://github.com/xam-ps/LASO?tab=readme-ov-file#deployment-manually).

**Full Changelog**: https://github.com/xam-ps/LASO/compare/v0.6.4...v1.0.0

## [v0.6.4](https://github.com/laravel/laravel/compare/v0.6.3...v0.6.4) - 2025-07-31

### Full list of changes

- Update php + js dependencies

After pulling the latest codebase please run
`composer install --no-dev`
`php artisan cache:clear`
`php artisan config:clear`
`php artisan view:clear`
and
`npm install`
`npm run build`
to build the frontend

### For a fresh installation please have a look at the [README](https://github.com/xam-ps/LASO?tab=readme-ov-file#deployment-manually).

**Full Changelog**: https://github.com/xam-ps/LASO/compare/v0.6.3...v0.6.4

## [v0.6.3](https://github.com/laravel/laravel/compare/v0.6.2...v0.6.3) - 2025-04-12

### Full list of changes

- Update php + js dependencies
- Add remaining net revenue to vat notice as this needs to be reported via elster after all

After pulling the latest codebase please run
`composer install --no-dev`
`php artisan cache:clear`
`php artisan config:clear`
`php artisan view:clear`
and
`npm install`
`npm run build`
to build the frontend

### For a fresh installation please have a look at the [README](https://github.com/xam-ps/LASO?tab=readme-ov-file#deployment-manually).

## [v0.6.2](https://github.com/laravel/laravel/compare/v0.6.1...v0.6.2) - 2025-02-15

### Full list of changes

- Update laravel dependencies

### Upgrade instruction (only when you are upgrading from v0.5.x or older)

⚠️You need to add a `DEFAULT_TAX_RATE` property to the `.env` file, e.g. `DEFAULT_TAX_RATE=19` for 19%. Have a look at the `.env.example` file and place the property at the same location if possible.

After pulling the latest codebase please run
`composer install --no-dev`
`php artisan migrate` (for the vat notice a new table is necessary)
`php artisan cache:clear`
`php artisan config:clear`
`php artisan view:clear`

### For a fresh installation please have a look at the [README](https://github.com/xam-ps/LASO?tab=readme-ov-file#deployment-manually).

## [v0.6.1](https://github.com/laravel/laravel/compare/v0.6.0...v0.6.1) - 2025-01-03

### Full list of changes

- Smaller UI fixes
- Update version number in composer.json

### Upgrade instruction (only when you are upgrading from v0.5.x or older)

⚠️You need to add a `DEFAULT_TAX_RATE` property to the `.env` file, e.g. `DEFAULT_TAX_RATE=19` for 19%. Have a look at the `.env.example` file and place the property at the same location if possible.

After pulling the latest codebase please run
`composer install --no-dev`
`php artisan migrate` (for the vat notice a new table is necessary)
`php artisan cache:clear`
`php artisan config:clear`
`php artisan view:clear`
and
`npm install`
`npm run build`
to build the frontend

### For a fresh installation please have a look at the [README](https://github.com/xam-ps/LASO?tab=readme-ov-file#deployment-manually).

## [v0.6.0](https://github.com/laravel/laravel/compare/v10.2.9...v0.6.0) - 2024-12-28

### Full list of changes

- Add [module ](https://github.com/xam-ps/LASO?tab=readme-ov-file#vat-notice) to track vat payments to/from the financial office during the year
- Extract tax rate to .env file to make LASO more flexible also for other countries
- Update Readme

### Upgrade instruction

⚠️You need to add a `DEFAULT_TAX_RATE` property to the `.env` file, e.g. `DEFAULT_TAX_RATE=19` for 19%. Have a look at the `.env.example` file and place the property at the same location if possible.

After pulling the latest codebase please run
`composer install --no-dev`
`php artisan migrate` (for the vat notice a new table is necessary)
`php artisan cache:clear`
`php artisan config:clear`
`php artisan view:clear`
and
`npm install`
`npm run build`
to build the frontend

### For a fresh installation please have a look at the [README](https://github.com/xam-ps/LASO?tab=readme-ov-file#deployment-manually).

## [v10.2.9](https://github.com/laravel/laravel/compare/v10.2.8...v10.2.9) - 2023-11-13

- Update axios to latest version by [@emargareten](https://github.com/emargareten) in https://github.com/laravel/laravel/pull/6272

## [v10.2.8](https://github.com/laravel/laravel/compare/v10.2.7...v10.2.8) - 2023-11-02

- Revert "[10.x] Let database handle default collation" by [@taylorotwell](https://github.com/taylorotwell) in https://github.com/laravel/laravel/pull/6266

## [v10.2.7](https://github.com/laravel/laravel/compare/v10.2.6...v10.2.7) - 2023-10-31

- Postmark mailer configuration update by [@ninjaparade](https://github.com/ninjaparade) in https://github.com/laravel/laravel/pull/6228
- [10.x] Update sanctum config file by [@ahmed-aliraqi](https://github.com/ahmed-aliraqi) in https://github.com/laravel/laravel/pull/6234
- [10.x] Let database handle default collation by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/laravel/pull/6241
- [10.x] Increase bcrypt rounds to 12 by [@valorin](https://github.com/valorin) in https://github.com/laravel/laravel/pull/6245
- [10.x] Use 12 bcrypt rounds for password in UserFactory by [@Jubeki](https://github.com/Jubeki) in https://github.com/laravel/laravel/pull/6247
- [10.x] Fix typo in the comment for token prefix (sanctum config) by [@yuters](https://github.com/yuters) in https://github.com/laravel/laravel/pull/6248
- [10.x] Update fixture hash to match testing cost by [@timacdonald](https://github.com/timacdonald) in https://github.com/laravel/laravel/pull/6259
- [10.x] Update minimum `laravel/sanctum` by [@crynobone](https://github.com/crynobone) in https://github.com/laravel/laravel/pull/6261
- [10.x] Hash improvements by [@timacdonald](https://github.com/timacdonald) in https://github.com/laravel/laravel/pull/6258
- Redis maintenance store config example contains an excess space by [@hedge-freek](https://github.com/hedge-freek) in https://github.com/laravel/laravel/pull/6264

## [v10.2.6](https://github.com/laravel/laravel/compare/v10.2.5...v10.2.6) - 2023-08-10

- Bump `laravel-vite-plugin` to latest version by [@adevade](https://github.com/adevade) in https://github.com/laravel/laravel/pull/6224

## [v10.2.5](https://github.com/laravel/laravel/compare/v10.2.4...v10.2.5) - 2023-06-30

- Allow accessing APP_NAME in Vite scope by [@domnantas](https://github.com/domnantas) in https://github.com/laravel/laravel/pull/6204
- Omit default values for suffix in phpunit.xml by [@spawnia](https://github.com/spawnia) in https://github.com/laravel/laravel/pull/6210

## [v10.2.4](https://github.com/laravel/laravel/compare/v10.2.3...v10.2.4) - 2023-06-07

- Add `precognitive` key to $middlewareAliases by @emargareten in https://github.com/laravel/laravel/pull/6193

## [v10.2.3](https://github.com/laravel/laravel/compare/v10.2.2...v10.2.3) - 2023-06-01

- Update description by @taylorotwell in https://github.com/laravel/laravel/commit/85203d687ebba72b2805b89bba7d18dfae8f95c8

## [v10.2.2](https://github.com/laravel/laravel/compare/v10.2.1...v10.2.2) - 2023-05-23

- Add lock path by @taylorotwell in https://github.com/laravel/laravel/commit/a6bfbc7f90e33fd6cae3cb23f106c9689858c3b5

## [v10.2.1](https://github.com/laravel/laravel/compare/v10.2.0...v10.2.1) - 2023-05-12

- Add hashed cast to user password by @emargareten in https://github.com/laravel/laravel/pull/6171
- Bring back pusher cluster config option by @jesseleite in https://github.com/laravel/laravel/pull/6174

## [v10.2.0](https://github.com/laravel/laravel/compare/v10.1.1...v10.2.0) - 2023-05-05

- Update welcome.blade.php by @aymanatmeh in https://github.com/laravel/laravel/pull/6163
- Sets package.json type to module by @timacdonald in https://github.com/laravel/laravel/pull/6090
- Add url support for mail config by @chu121su12 in https://github.com/laravel/laravel/pull/6170

## [v10.1.1](https://github.com/laravel/laravel/compare/v10.0.7...v10.1.1) - 2023-04-18

- Fix laravel/framework constraints for Default Service Providers by @Jubeki in https://github.com/laravel/laravel/pull/6160

## [v10.0.7](https://github.com/laravel/laravel/compare/v10.1.0...v10.0.7) - 2023-04-14

- Adds `phpunit/phpunit@10.1` support by @nunomaduro in https://github.com/laravel/laravel/pull/6155

## [v10.1.0](https://github.com/laravel/laravel/compare/v10.0.6...v10.1.0) - 2023-04-15

- Minor skeleton slimming by @taylorotwell in https://github.com/laravel/laravel/pull/6159

## [v10.0.6](https://github.com/laravel/laravel/compare/v10.0.5...v10.0.6) - 2023-04-05

- Add job batching options to Queue configuration file by @AnOlsen in https://github.com/laravel/laravel/pull/6149

## [v10.0.5](https://github.com/laravel/laravel/compare/v10.0.4...v10.0.5) - 2023-03-08

- Add replace_placeholders to log channels by @alanpoulain in https://github.com/laravel/laravel/pull/6139

## [v10.0.4](https://github.com/laravel/laravel/compare/v10.0.3...v10.0.4) - 2023-02-27

- Fix typo by @izzudin96 in https://github.com/laravel/laravel/pull/6128
- Specify facility in the syslog driver config by @nicolus in https://github.com/laravel/laravel/pull/6130

## [v10.0.3](https://github.com/laravel/laravel/compare/v10.0.2...v10.0.3) - 2023-02-21

- Remove redundant `@return` docblock in UserFactory by @datlechin in https://github.com/laravel/laravel/pull/6119
- Reverts change in asset helper by @timacdonald in https://github.com/laravel/laravel/pull/6122

## [v10.0.2](https://github.com/laravel/laravel/compare/v10.0.1...v10.0.2) - 2023-02-16

- Remove unneeded call by @taylorotwell in https://github.com/laravel/laravel/commit/3986d4c54041fd27af36f96cf11bd79ce7b1ee4e

## [v10.0.1](https://github.com/laravel/laravel/compare/v10.0.0...v10.0.1) - 2023-02-15

- Add PHPUnit result cache to gitignore by @itxshakil in https://github.com/laravel/laravel/pull/6105
- Allow php-http/discovery as a composer plugin by @nicolas-grekas in https://github.com/laravel/laravel/pull/6106

## [v10.0.0 (2022-02-14)](https://github.com/laravel/laravel/compare/v9.5.2...v10.0.0)

Laravel 10 includes a variety of changes to the application skeleton. Please consult the diff to see what's new.
