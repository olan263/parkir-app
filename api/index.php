<?php

// Tampilkan error ke layar agar kita tahu penyebabnya
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load composer
require __DIR__ . '/../vendor/autoload.php';

// Paksa Laravel menggunakan folder /tmp untuk cache/logs
// Ini sangat penting karena Vercel tidak mengizinkan penulisan di folder project
putenv('APP_STORAGE=/tmp');
putenv('VIEW_COMPILED_PATH=/tmp');
putenv('SESSION_DRIVER=cookie'); // Simpan session di browser saja
putenv('LOG_CHANNEL=stderr');    // Kirim log ke console Vercel

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);