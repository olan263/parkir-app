<h2 style="text-align: center;">Laporan Transaksi Parkir</h2>
<table border="1" cellspacing="0" cellpadding="5" width="100%" style="border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>Kode</th>
            <th>Plat</th>
            <th>Jenis</th>
            <th>Durasi</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td style="text-align: center;">{{ $item->kode_tiket }}</td>
            <td style="text-align: center; text-transform: uppercase;">{{ $item->plat_nomor }}</td>
            <td style="text-align: center;">{{ ucfirst($item->jenis) }}</td>
            <td style="text-align: center;">
                @php
                    // Hitung selisih menit total
                    $totalMenit = $item->waktu_masuk->diffInMinutes($item->waktu_keluar);
                    $jam = floor($totalMenit / 60);
                    $menit = $totalMenit % 60;
                @endphp
                
                {{-- Menampilkan format bersih: "1j 15m" --}}
                {{ $jam > 0 ? $jam . 'j ' : '' }}{{ $menit }}m
            </td>
            <td style="text-align: right;">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>