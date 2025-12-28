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
     * 1. DASHBOARD ADMIN
     */
    public function indexAdmin()
    {
        $totalPendapatanBulanIni = Parking::where('status', 'selesai')
            ->whereMonth('waktu_keluar', Carbon::now()->month)
            ->sum('total_bayar');

        $totalKendaraanHariIni = Parking::whereDate('waktu_masuk', Carbon::today())->count();
        
        $statsJenis = [
            'mobil' => Parking::where('jenis', 'mobil')->where('status', 'aktif')->count(),
            'motor' => Parking::where('jenis', 'motor')->where('status', 'aktif')->count(),
        ];

        // Pakai paginate agar tampilan nomor halaman muncul rapi
        $semuaTransaksi = Parking::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.dashboard', compact(
            'totalPendapatanBulanIni', 
            'totalKendaraanHariIni', 
            'statsJenis', 
            'semuaTransaksi'
        ));
    }

    /**
     * 2. GATE MASUK
     */
    public function indexMasuk() {
        $kendaraanDiDalam = Parking::where('status', 'aktif')->count();
        return view('kasir.masuk', compact('kendaraanDiDalam'));
    }

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

        return redirect()->back()->with([
            'success' => "Tiket $kode berhasil dibuat!",
            'last_id' => $parking->id 
        ]);
    }

    /**
     * 3. GATE KELUAR
     */
    public function indexKeluar() {
        $pendapatanHariIni = Parking::whereDate('waktu_keluar', Carbon::today())->sum('total_bayar');
        $kendaraanAktif = Parking::where('status', 'aktif')->orderBy('waktu_masuk', 'desc')->get();
        return view('kasir.keluar', compact('pendapatanHariIni', 'kendaraanAktif'));
    }

    public function keluar(Request $request) {
        $request->validate([
            'kode_tiket' => 'required',
            'plat_nomor' => 'required|string',
            'bayar' => 'required'
        ]);

        $parking = Parking::where('kode_tiket', $request->kode_tiket)->where('status', 'aktif')->first();

        if (!$parking) return back()->with('error', 'Tiket tidak valid!');

        $waktuMasuk = Carbon::parse($parking->waktu_masuk);
        $totalMenit = $waktuMasuk->diffInMinutes(now());
        $durasiJam = ceil($totalMenit / 60) ?: 1;
        $tarif = ($parking->jenis == 'mobil') ? 5000 : 2000;
        $totalTagihan = $durasiJam * $tarif;

        $nominalBayar = (int) preg_replace('/[^0-9]/', '', $request->bayar);
        
        if ($nominalBayar < $totalTagihan) {
            return back()->with('error', 'Uang kurang! Total: Rp ' . number_format($totalTagihan));
        }

        $parking->update([
            'waktu_keluar' => now(),
            'total_bayar' => $totalTagihan,
            'status' => 'selesai',
            'plat_nomor' => strtoupper($request->plat_nomor),
            'durasi' => floor($totalMenit/60).'j '.($totalMenit%60).'m'
        ]);

        return redirect()->back()->with([
            'success' => 'Pembayaran Berhasil!',
            'print_nota_id' => $parking->id
        ]);
    }

    /**
     * 4. PRINTING & EXPORT
     */
    public function cetakTiketMasuk($id) {
        $data = Parking::findOrFail($id);
        $pdf = Pdf::loadView('kasir.tiket_masuk', compact('data'))->setPaper([0, 0, 226, 350]);
        return $pdf->stream('tiket-'.$data->kode_tiket.'.pdf');
    }

    public function cetakNotaKeluar($id) {
        $data = Parking::findOrFail($id);
        $pdf = Pdf::loadView('kasir.nota_keluar', compact('data'))->setPaper([0, 0, 226, 450]);
        return $pdf->stream('nota-'.$data->kode_tiket.'.pdf');
    }

    public function exportPDF() {
        $semuaTransaksi = Parking::all();
        // Pastikan view 'admin.laporan_pdf' sudah kamu buat filenya
        $pdf = Pdf::loadView('admin.laporan_pdf', compact('semuaTransaksi'))->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-parkir.pdf');
    }

    public function exportExcel() {
        return back()->with('error', 'Fitur Excel sedang dikembangkan!');
    }

    /**
     * 5. CRUD MANAGEMENT
     */
    public function destroy($id) {
        Parking::findOrFail($id)->delete();
        return back()->with('success', 'Data parkir berhasil dihapus!');
    }

    public function edit($id) {
        $parking = Parking::findOrFail($id);
        return view('admin.edit', compact('parking'));
    }

    public function update(Request $request, $id) {
        $parking = Parking::findOrFail($id);
        $parking->update($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Data diperbarui!');
    }
} // AKHIR DARI CLASS - Pastikan kurung ini yang paling bawah!