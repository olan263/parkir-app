<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL; // Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (config('app.env') === 'production') {
            $this->app->useStoragePath('/tmp');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Menetapkan default string length untuk menghindari error index mysql
        Schema::defaultStringLength(191);

        // Tambahkan blok ini untuk memaksa HTTPS di Vercel
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}