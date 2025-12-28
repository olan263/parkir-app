<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Parkir</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #1a5276; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #1a5276; color: white; padding: 10px; border: 1px solid #ddd; }
        td { padding: 8px; border: 1px solid #ddd; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: right; font-style: italic; font-size: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN DATA PARKIR</h2>
        <p>Tanggal Cetak: {{ date('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Tiket</th>
                <th>Plat Nomor</th>
                <th>Jenis</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
                <th>Total Bayar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($semuaTransaksi as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center"><strong>{{ $item->kode_tiket }}</strong></td>
                    <td>{{ $item->plat_nomor ?? '-' }}</td>
                    <td class="text-center">{{ ucfirst($item->jenis) }}</td>
                    <td>{{ $item->waktu_masuk }}</td>
                    <td>{{ $item->waktu_keluar ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                    <td class="text-center">{{ ucfirst($item->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak secara otomatis oleh Sistem Aplikasi Parkir pada {{ date('d/m/Y') }}</p>
    </div>

</body>
</html>