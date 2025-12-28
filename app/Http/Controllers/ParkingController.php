<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parking;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ParkingController extends Controller
{
    /**
     * GATE MASUK
     */
    public function masuk(Request $request) {
        $request->validate(['jenis' => 'required|in:motor,mobil']);

        do {
            $kode = 'TKT-' . strtoupper(Str::random(6));
        } while (Parking::where('kode_tiket', $kode)->exists());
        
        $parking = Parking::create([
            'kode_tiket' => $kode,
            'jenis' => $request->jenis,
            'waktu_masuk' => now(),
            'status' => 'aktif'
        ]);

        // SUDAH BENAR: Mengirim 'last_id' agar view bisa bikin link cetak PDF
        return redirect()->back()->with([
            'success' => "Tiket $kode berhasil dibuat!",
            'last_id' => $parking->id 
        ]);
    }

    /**
     * GATE KELUAR
     */
    public function keluar(Request $request) {
        $request->validate([
            'kode_tiket' => 'required',
            'plat_nomor' => 'required|string',
            'bayar' => 'required'
        ]);

        $parking = Parking::where('kode_tiket', $request->kode_tiket)->where('status', 'aktif')->first();

        if (!$parking) return back()->with('error', 'Tiket tidak valid atau sudah keluar!');

        $waktuMasuk = Carbon::parse($parking->waktu_masuk);
        $totalMenit = $waktuMasuk->diffInMinutes(now());
        $durasiJam = ceil($totalMenit / 60) ?: 1;
        $tarif = ($parking->jenis == 'mobil') ? 5000 : 2000;
        $totalTagihan = $durasiJam * $tarif;

        $nominalBayar = (int) preg_replace('/[^0-9]/', '', $request->bayar);
        
        if ($nominalBayar < $totalTagihan) {
            return back()->with('error', 'Uang kurang! Total Tagihan: Rp ' . number_format($totalTagihan));
        }

        $parking->update([
            'waktu_keluar' => now(),
            'total_bayar' => $totalTagihan,
            'status' => 'selesai',
            'plat_nomor' => strtoupper($request->plat_nomor),
            'durasi' => floor($totalMenit/60).'j '.($totalMenit%60).'m'
        ]);

        // SUDAH BENAR: Mengirim 'print_nota_id' untuk tombol cetak nota
        return redirect()->back()->with([
            'success' => 'Pembayaran Berhasil! Kembalian: Rp ' . number_format($nominalBayar - $totalTagihan),
            'print_nota_id' => $parking->id
        ]);
    }
}