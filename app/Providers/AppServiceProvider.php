<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Pengaturan khusus untuk storage Vercel di lingkungan production
        if (config('app.env') === 'production') {
            $this->app->useStoragePath('/tmp');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Menetapkan default string length untuk menghindari error index MySQL
        Schema::defaultStringLength(191);

        // 2. Memaksa HTTPS di lingkungan non-lokal (Vercel/Hosting)
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        // 3. Mengatur Laravel agar menggunakan desain Bootstrap 5 untuk pagination
        Paginator::useBootstrapFive();
    }
}