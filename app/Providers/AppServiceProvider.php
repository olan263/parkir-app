<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // Wajib ada
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (config('app.env') === 'production') {
            $this->app->useStoragePath('/tmp');
        }
    }

    public function boot(): void
    {
        // INI KUNCI BIAR GAK VERTIKAL KE BAWAH
        Paginator::useBootstrapFive(); 

        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}