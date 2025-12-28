<?php

use App\Http\Controllers\ParkingController;
use Illuminate\Support\Facades\Route;

// 1. HALAMAN UTAMA (ROOT)
Route::get('/', [ParkingController::class, 'indexAdmin'])->name('parkir.index');

// 2. MODUL ADMIN (DASHBOARD)
Route::get('/admin/dashboard', [ParkingController::class, 'indexAdmin'])->name('admin.dashboard');

// 3. OPERASIONAL GATE (LINK SIDEBAR BARU)
// Halaman Gate Masuk (Form Input & Cetak Tiket)
Route::get('/parkir/masuk', [ParkingController::class, 'indexMasuk'])->name('parkir.view.masuk');
Route::post('/masuk', [ParkingController::class, 'masuk'])->name('parkir.masuk');
Route::get('/parkir/cetak-masuk/{id}', [ParkingController::class, 'cetakTiketMasuk'])->name('parkir.cetak.masuk');

// Halaman Gate Keluar (Input Tiket & Pembayaran)
Route::get('/parkir/keluar', [ParkingController::class, 'indexKeluar'])->name('parkir.view.keluar');
Route::post('/keluar', [ParkingController::class, 'keluar'])->name('parkir.keluar');
Route::get('/parkir/cetak-keluar/{id}', [ParkingController::class, 'cetakNotaKeluar'])->name('parkir.cetak.keluar');

// 4. DATA & LAPORAN
// Data Parkir (Link Sidebar)
Route::get('/admin/parkir', [ParkingController::class, 'indexAdmin'])->name('admin.parkir');

// Export Laporan
Route::get('/parkir/export/pdf', [ParkingController::class, 'exportPDF'])->name('parkir.export.pdf');
Route::get('/parkir/export/excel', [ParkingController::class, 'exportExcel'])->name('parkir.export.excel');

// 5. MANAGEMENT DATA (CRUD)
// Digunakan oleh tombol edit dan hapus di tabel dashboard
Route::get('/admin/parkir/{id}/edit', [ParkingController::class, 'edit'])->name('admin.edit');
Route::put('/admin/parkir/{id}', [ParkingController::class, 'update'])->name('admin.update');
Route::delete('/admin/parkir/{id}', [ParkingController::class, 'destroy'])->name('admin.destroy');