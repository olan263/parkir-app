<?php

// Aktifkan laporan error secara maksimal
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cek apakah file autoload ada
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    die("Error: Folder vendor tidak ditemukan. Pastikan sudah ada .vercelignore berisi /vendor dan Vercel sudah menjalankan composer install.");
}

require $autoload;

// Cek apakah file bootstrap Laravel ada
$appFile = __DIR__ . '/../bootstrap/app.php';
if (!file_exists($appFile)) {
    die("Error: File bootstrap/app.php tidak ditemukan.");
}

$app = require_once $appFile;

// Paksa folder storage ke /tmp agar tidak Error Permission di Vercel
$app->useStoragePath('/tmp');

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);