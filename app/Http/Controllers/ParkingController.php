<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parking;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
// Pastikan file export ini sudah dibuat
// use App\Exports\ParkingExport; 
use Illuminate\Support\Str;

class ParkingController extends Controller
{
    // --- 1. HALAMAN MASUK (GATE IN) ---
    public function indexMasuk()
    {
        $kendaraanDiDalam = Parking::where('status', 'aktif')->count();
        return view('kasir.masuk', compact('kendaraanDiDalam'));
    }

    // --- 2. HALAMAN KELUAR (GATE OUT) ---
    public function indexKeluar()
    {
        $pendapatanHariIni = Parking::whereDate('waktu_keluar', Carbon::today())
            ->where('status', 'selesai')
            ->sum('total_bayar');

        $kendaraanAktif = Parking::where('status', 'aktif')
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        $riwayat = Parking::where('status', 'selesai')
            ->whereDate('waktu_keluar', Carbon::today())
            ->orderBy('waktu_keluar', 'desc')
            ->take(10)
            ->get();

        return view('kasir.keluar', compact('pendapatanHariIni', 'kendaraanAktif', 'riwayat'));
    }

    // --- 3. DASHBOARD ADMIN (NEW) ---
    public function indexAdmin()
    {
        // Statistik Lanjutan
        $totalPendapatanBulanIni = Parking::where('status', 'selesai')
            ->whereMonth('waktu_keluar', Carbon::now()->month)
            ->sum('total_bayar');

        $totalKendaraanHariIni = Parking::whereDate('waktu_masuk', Carbon::today())->count();
        
        $statsJenis = [
            'mobil' => Parking::where('jenis', 'mobil')->where('status', 'aktif')->count(),
            'motor' => Parking::where('jenis', 'motor')->where('status', 'aktif')->count(),
        ];

        // Semua data untuk tabel management dengan pagination
        $semuaTransaksi = Parking::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.dashboard', compact(
            'totalPendapatanBulanIni', 
            'totalKendaraanHariIni', 
            'statsJenis', 
            'semuaTransaksi'
        ));
    }

    // --- 4. PROSES TRANSAKSI ---
    public function masuk(Request $request)
    {
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

        return redirect()->route('parkir.view.masuk')->with([
            'success' => "Tiket berhasil dibuat! KODE: $kode",
            'last_id' => $parking->id 
        ]);
    }

    public function keluar(Request $request)
    {
        $request->validate([
            'kode_tiket' => 'required',
            'plat_nomor' => 'required|string|max:15',
            'bayar' => 'required'
        ]);

        $parking = Parking::where('kode_tiket', $request->kode_tiket)
            ->where('status', 'aktif')
            ->first();

        if (!$parking) {
            return back()->with('error', 'Tiket tidak ditemukan atau sudah diproses.');
        }

        $waktuMasuk = Carbon::parse($parking->waktu_masuk);
        $waktuKeluar = now();

        $totalMenit = $waktuMasuk->diffInMinutes($waktuKeluar);
        $jamDisplay = floor($totalMenit / 60);
        $menitDisplay = $totalMenit % 60;
        $durasiTeks = ($jamDisplay > 0 ? $jamDisplay . 'j ' : '') . $menitDisplay . 'm';

        $durasiJamBill = ceil($totalMenit / 60);
        if ($durasiJamBill <= 0) $durasiJamBill = 1;

        $tarifPerJam = ($parking->jenis == 'mobil') ? 5000 : 2000;
        $totalTagihan = $durasiJamBill * $tarifPerJam;

        $nominalBayar = (int) preg_replace('/[^0-9]/', '', $request->bayar);
        
        if ($nominalBayar < $totalTagihan) {
            return back()->with('error', 'Uang kurang! Total Tagihan: Rp ' . number_format($totalTagihan, 0, ',', '.'));
        }

        $kembalian = $nominalBayar - $totalTagihan;

        $parking->update([
            'waktu_keluar' => $waktuKeluar,
            'total_bayar' => $totalTagihan,
            'status' => 'selesai',
            'plat_nomor' => strtoupper($request->plat_nomor),
            'durasi' => $durasiTeks 
        ]);

        return redirect()->route('parkir.view.keluar')->with('nota', [
            'id' => $parking->id,
            'kode' => $parking->kode_tiket,
            'total' => $totalTagihan,
            'durasi' => $durasiTeks,
            'bayar' => $nominalBayar,
            'kembalian' => $kembalian
        ]);
    }

    // --- 5. CETAK & EXPORT ---
    public function exportPDF()
    {
        $data = Parking::all();
        $pdf = Pdf::loadView('admin.laporan_pdf', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->download('laporan-parkir-' . date('Y-m-d') . '.pdf');
    }

    public function cetakTiketMasuk($id)
    {
        $data = Parking::findOrFail($id);
        $pdf = Pdf::loadView('kasir.tiket_masuk', compact('data'))->setPaper([0, 0, 226, 350]);
        return $pdf->stream('tiket-' . $data->kode_tiket . '.pdf');
    }

    public function cetakNotaKeluar($id)
    {
        $data = Parking::findOrFail($id);
        if($data->status !== 'selesai') return back()->with('error', 'Transaksi belum selesai.');

        $pdf = Pdf::loadView('kasir.nota_keluar', compact('data'))->setPaper([0, 0, 226, 450]);
        return $pdf->stream('nota-' . $data->kode_tiket . '.pdf');
    }

    // --- 6. MANAGEMENT DATA (CRUD) ---
    public function edit($id)
    {
        $data = Parking::findOrFail($id);
        return view('admin.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'plat_nomor' => 'required',
            'jenis' => 'required'
        ]);

        $parking = Parking::findOrFail($id);
        $parking->update($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $parking = Parking::findOrFail($id);
        $parking->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }
}