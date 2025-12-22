<?php

namespace App\Exports;

use App\Models\Parking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParkingExport implements FromCollection, WithHeadings
{
    public function collection() {
        return Parking::where('status', 'selesai')->select('kode_tiket', 'plat_nomor', 'jenis', 'waktu_masuk', 'waktu_keluar', 'total_bayar')->get();
    }

    public function headings(): array {
        return ["Kode Tiket", "Plat Nomor", "Jenis", "Masuk", "Keluar", "Total Bayar"];
    }
}