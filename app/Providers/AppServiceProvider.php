<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set the default locale for Carbon
        Carbon::setLocale(config('app.locale'));

        // Set the default locale for NumberFormatter
        setlocale(LC_MONETARY, config('app.locale'));
    }
}
