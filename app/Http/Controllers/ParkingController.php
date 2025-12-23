<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parking;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParkingExport;

class ParkingController extends Controller
{
    // --- 1. FUNGSI UTAMA (DASHBOARD) ---
    public function index()
    {
        // Optimasi: Gunakan cache atau batasi query agar tidak berat saat loading awal
        $pendapatanHariIni = Parking::whereDate('updated_at', Carbon::today())
            ->where('status', 'selesai')
            ->sum('total_bayar');

        $kendaraanDiDalam = Parking::where('status', 'aktif')->count();

        $riwayat = Parking::where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->take(10) // Membatasi hanya 10 data terakhir agar loading cepat
            ->get();

        $kendaraanAktif = Parking::where('status', 'aktif')
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        return view('kasir.index', compact('pendapatanHariIni', 'kendaraanDiDalam', 'riwayat', 'kendaraanAktif'));
    }

    // --- 2. FUNGSI TRANSAKSI ---
    public function masuk(Request $request)
    {
        // Pastikan kode unik
        $kode = 'TKT-' . strtoupper(str()->random(6));
        
        $parking = Parking::create([
            'kode_tiket' => $kode,
            'jenis' => $request->jenis,
            'waktu_masuk' => now(),
            'status' => 'aktif'
        ]);

        return back()->with([
            'success' => "Tiket berhasil dibuat! KODE: $kode",
            'last_id' => $parking->id 
        ]);
    }

    public function keluar(Request $request)
    {
        $parking = Parking::where('kode_tiket', $request->kode_tiket)
            ->where('status', 'aktif')
            ->first();

        if (!$parking) return back()->with('error', 'Tiket tidak ditemukan atau sudah dibayar.');

        $masuk = Carbon::parse($parking->waktu_masuk);
        $keluar = now();

        $totalMenit = $masuk->diffInMinutes($keluar);
        $jam = floor($totalMenit / 60);
        $menit = $totalMenit % 60;
        
        $durasiTeks = ($jam > 0 ? $jam . 'j ' : '') . $menit . 'm';

        // Bulatkan ke atas untuk tarif per jam
        $durasiJamBulat = ceil($totalMenit / 60);
        if ($durasiJamBulat == 0) $durasiJamBulat = 1;

        $tarif = ($parking->jenis == 'mobil') ? 5000 : 2000;
        $totalTagihan = $durasiJamBulat * $tarif;

        // Hilangkan titik jika input berupa format ribuan
        $nominalBayar = str_replace('.', '', $request->bayar);
        
        if ($nominalBayar < $totalTagihan) {
            return back()->with('error', 'Uang tidak cukup! Kurang Rp ' . number_format($totalTagihan - $nominalBayar, 0, ',', '.'));
        }

        $kembalian = $nominalBayar - $totalTagihan;

        $parking->update([
            'waktu_keluar' => $keluar,
            'total_bayar' => $totalTagihan,
            'status' => 'selesai',
            'plat_nomor' => strtoupper($request->plat_nomor),
            'durasi' => $durasiTeks 
        ]);

        return back()->with('nota', [
            'id' => $parking->id,
            'kode' => $parking->kode_tiket,
            'total' => $totalTagihan,
            'durasi' => $durasiTeks,
            'bayar' => $nominalBayar,
            'kembalian' => $kembalian
        ]);
    }

    // --- 3. FUNGSI CETAK & EXPORT ---
    
    // Perbaikan: Tambahkan penanganan error jika file view tidak ditemukan
    public function cetakTiketMasuk($id)
    {
        $data = Parking::findOrFail($id);
        
        // Jika Vercel lemot saat generate PDF, gunakan return view biasa:
        // return view('kasir.tiket_masuk', compact('data'));

        $pdf = Pdf::loadView('kasir.tiket_masuk', compact('data'))
                  ->setPaper([0, 0, 226, 400]); // Ukuran kertas Thermal 80mm
        return $pdf->stream('tiket-' . $data->kode_tiket . '.pdf');
    }

    public function cetakNotaKeluar($id)
    {
        $data = Parking::findOrFail($id);
        
        $pdf = Pdf::loadView('kasir.nota_keluar', compact('data'))
                  ->setPaper([0, 0, 226, 500]);
        return $pdf->stream('nota-' . $data->kode_tiket . '.pdf');
    }

    public function exportPDF()
    {
        // Batasi data yang di-export agar tidak membebani server Vercel (misal 100 data terakhir)
        $data = Parking::where('status', 'selesai')
                       ->orderBy('updated_at', 'desc')
                       ->take(100)
                       ->get();
                       
        $pdf = Pdf::loadView('kasir.pdf', compact('data'));
        return $pdf->download('laporan-parkir-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ParkingExport, 'laporan-parkir.xlsx');
    }

    // --- 4. FUNGSI CRUD TAMBAHAN ---
    public function edit($id)
    {
        $parking = Parking::findOrFail($id);
        return view('kasir.edit', compact('parking'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:20',
            'jenis' => 'required|in:motor,mobil',
            'status' => 'required|in:aktif,selesai'
        ]);

        $parking = Parking::findOrFail($id);
        $parking->update([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'jenis' => $request->jenis,
            'status' => $request->status,
        ]);

        return redirect()->route('parkir.index')->with('success', 'Data parkir berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $parking = Parking::findOrFail($id);
        $parking->delete();
        return back()->with('success', 'Data transaksi berhasil dihapus.');
    }
}