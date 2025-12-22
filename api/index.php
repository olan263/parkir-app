<?php

// PAKSA TAMPILKAN ERROR
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Pastikan autoload terbaca
require __DIR__ . '/../vendor/autoload.php';

// Jalankan aplikasi
require __DIR__ . '/../public/index.php';