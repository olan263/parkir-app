<?php

use App\Http\Controllers\ParkingController;
use Illuminate\Support\Facades\Route;

// 1. HALAMAN UTAMA (ARAHKAN KE DASHBOARD ADMIN)
Route::get('/', [ParkingController::class, 'indexAdmin'])->name('parkir.index');

// 2. MODUL ADMIN (DASHBOARD & MANAGEMENT)
Route::get('/admin/dashboard', [ParkingController::class, 'indexAdmin'])->name('admin.dashboard');

// 3. MODUL MASUK (GATE IN)
Route::get('/parkir/masuk', [ParkingController::class, 'indexMasuk'])->name('parkir.view.masuk');
Route::post('/masuk', [ParkingController::class, 'masuk'])->name('parkir.masuk');
Route::get('/parkir/cetak-masuk/{id}', [ParkingController::class, 'cetakTiketMasuk'])->name('parkir.cetak.masuk');

// 4. MODUL KELUAR (GATE OUT / KASIR)
Route::get('/parkir/keluar', [ParkingController::class, 'indexKeluar'])->name('parkir.view.keluar');
Route::post('/keluar', [ParkingController::class, 'keluar'])->name('parkir.keluar');
Route::get('/parkir/cetak-keluar/{id}', [ParkingController::class, 'cetakNotaKeluar'])->name('parkir.cetak.keluar');

// 5. LAPORAN & EXPORT
Route::get('/parkir/export/pdf', [ParkingController::class, 'exportPDF'])->name('parkir.export.pdf');
Route::get('/parkir/export/excel', [ParkingController::class, 'exportExcel'])->name('parkir.export.excel');

// 6. MANAGEMENT DATA (CRUD)
Route::get('/parkir/{id}/edit', [ParkingController::class, 'edit'])->name('parkir.edit');
Route::put('/parkir/{id}', [ParkingController::class, 'update'])->name('parkir.update');
Route::delete('/parkir/{id}', [ParkingController::class, 'destroy'])->name('parkir.destroy');