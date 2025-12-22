<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Mengarahkan folder storage ke /tmp karena Vercel bersifat read-only
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
    }
}