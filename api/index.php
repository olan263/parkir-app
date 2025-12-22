<?php
// Paksa agar error tampil di layar
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load path yang benar untuk Vercel
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../public/index.php';