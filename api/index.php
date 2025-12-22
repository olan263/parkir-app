<?php

/*
|--------------------------------------------------------------------------
| Vercel PHP Setup with View Cache Fix
|--------------------------------------------------------------------------
*/

// 1. Buat daftar folder yang wajib ada di /tmp (karena Vercel Read-Only)
$directories = [
    '/tmp/bootstrap/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
}

// 2. Load Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 3. Paksa Laravel menggunakan /tmp untuk semua hal yang berbau "menulis"
$app->useStoragePath('/tmp/storage');

// Re-bind path bootstrap agar writable
$app->bind('path.bootstrap', function () {
    return '/tmp/bootstrap';
});

// 4. Jalankan Kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);