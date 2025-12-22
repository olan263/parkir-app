<?php

/*
|--------------------------------------------------------------------------
| Vercel PHP Setup
|--------------------------------------------------------------------------
*/

// 1. Paksa pembuatan folder cache di /tmp agar writable
$cachePath = '/tmp/bootstrap/cache';
if (!is_dir($cachePath)) {
    mkdir($cachePath, 0755, true);
}

// 2. Load Laravel Autoloader & App
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 3. Konfigurasi jalur folder agar Vercel tidak Error 500
$app->useStoragePath('/tmp');

// Mengarahkan folder bootstrap cache ke /tmp yang bisa ditulis
$app->bind('path.bootstrap', function () {
    return '/tmp/bootstrap';
});

// 4. Jalankan Kernel Laravel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);