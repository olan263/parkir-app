<?php

use App\Http\Controllers\ParkingController;
use Illuminate\Support\Facades\Route; // Pastikan ini ada

// Tambahkan ->name('parkir.index') di baris bawah ini
Route::get('/', [ParkingController::class, 'index'])->name('parkir.index');

Route::post('/masuk', [ParkingController::class, 'masuk'])->name('parkir.masuk');
Route::post('/keluar', [ParkingController::class, 'keluar'])->name('parkir.keluar');
Route::get('/parkir/export/pdf', [ParkingController::class, 'exportPDF'])->name('parkir.export.pdf');
Route::get('/parkir/export/excel', [ParkingController::class, 'exportExcel'])->name('parkir.export.excel');
Route::get('/parkir/cetak-masuk/{id}', [ParkingController::class, 'cetakTiketMasuk'])->name('parkir.cetak.masuk');
Route::get('/parkir/cetak-keluar/{kode}', [ParkingController::class, 'cetakNotaKeluar'])->name('parkir.cetak.keluar');
Route::get('/parkir/{id}/edit', [ParkingController::class, 'edit'])->name('parkir.edit');
Route::put('/parkir/{id}', [ParkingController::class, 'update'])->name('parkir.update');
Route::delete('/parkir/{id}', [ParkingController::class, 'destroy'])->name('parkir.destroy');