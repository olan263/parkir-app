<?php

use App\Http\Controllers\ParkingController;
use Illuminate\Support\Facades\Route;

// 1. HALAMAN UTAMA (ROOT)
// Langsung arahkan ke dashboard admin
Route::get('/', [ParkingController::class, 'indexAdmin'])->name('parkir.index');

// 2. MODUL ADMIN (DASHBOARD & MANAGEMENT)
Route::prefix('admin')->group(function () {
    // Dashboard & List Data
    Route::get('/dashboard', [ParkingController::class, 'indexAdmin'])->name('admin.dashboard');
    Route::get('/parkir', [ParkingController::class, 'indexAdmin'])->name('admin.parkir');
    
    // CRUD (Management Data)
    Route::get('/parkir/{id}/edit', [ParkingController::class, 'edit'])->name('admin.edit');
    Route::put('/parkir/{id}', [ParkingController::class, 'update'])->name('admin.update');
    Route::delete('/parkir/{id}', [ParkingController::class, 'destroy'])->name('admin.destroy');

    /** * CATATAN: Fungsi export di bawah ini akan error jika 
     * function exportPDF/exportExcel belum dibuat di Controller.
     * Untuk sementara saya comment agar tidak bikin error 500.
     */
    // Route::get('/export/pdf', [ParkingController::class, 'exportPDF'])->name('parkir.export.pdf');
    // Route::get('/export/excel', [ParkingController::class, 'exportExcel'])->name('parkir.export.excel');
});

// 3. OPERASIONAL GATE MASUK
Route::prefix('parkir/masuk')->group(function () {
    Route::get('/', [ParkingController::class, 'indexMasuk'])->name('parkir.view.masuk');
    Route::post('/proses', [ParkingController::class, 'masuk'])->name('parkir.masuk');
    Route::get('/cetak/{id}', [ParkingController::class, 'cetakTiketMasuk'])->name('parkir.cetak.masuk');
});

// 4. OPERASIONAL GATE KELUAR (KASIR)
Route::prefix('parkir/keluar')->group(function () {
    Route::get('/', [ParkingController::class, 'indexKeluar'])->name('parkir.view.keluar');
    Route::post('/proses', [ParkingController::class, 'keluar'])->name('parkir.keluar');
    Route::get('/cetak/{id}', [ParkingController::class, 'cetakNotaKeluar'])->name('parkir.cetak.keluar');
});