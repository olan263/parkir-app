<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Keluar - {{ $data->kode_tiket }}</title>
    <style>
        @page { size: 80mm auto; margin: 0; }
        body { 
            width: 70mm; /* Margin aman agar tidak terpotong */
            margin: 0 auto; 
            padding: 10px 0; 
            font-family: 'Courier New', Courier, monospace; 
            font-size: 13px;
        }
        .text-center { text-align: center; }
        .divider-double { border-top: 2px double #000; margin: 8px 0; }
        .divider-dashed { border-top: 1px dashed #000; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 3px 0; vertical-align: top; }
        .total-row { font-size: 16px; font-weight: bold; border-top: 1px solid #000; border-bottom: 1px solid #000; }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <h3 style="margin: 0;">STRUK PEMBAYARAN</h3>
        <p style="margin: 2px 0; font-size: 11px;">PARKIR DIGITAL CENTER</p>
        <div class="divider-double"></div>
    </div>

    <table>
        <tr>
            <td width="40%">No. Tiket</td>
            <td align="right">{{ $data->kode_tiket }}</td>
        </tr>
        <tr>
            <td>Plat Nomor</td>
            <td align="right"><strong>{{ strtoupper($data->plat_nomor) }}</strong></td>
        </tr>
        <tr>
            <td>Jenis</td>
            <td align="right">{{ ucfirst($data->jenis) }}</td>
        </tr>
        <tr>
            <td>Waktu Masuk</td>
            <td align="right">{{ $data->waktu_masuk->format('d/m/y H:i') }}</td>
        </tr>
        <tr>
            <td>Waktu Keluar</td>
            <td align="right">{{ $data->waktu_keluar->format('d/m/y H:i') }}</td>
        </tr>
        <tr>
            <td>Durasi</td>
            <td align="right">
                @php
                    // Logika perbaikan jam agar tidak muncul desimal seperti di image_07a0e4.png
                    $totalMenit = $data->waktu_masuk->diffInMinutes($data->waktu_keluar);
                    $jam = floor($totalMenit / 60);
                    $menit = $totalMenit % 60;
                @endphp
                {{-- Menampilkan format yang bersih: "1 Jam 15 Menit" atau hanya "15 Menit" --}}
                {{ $jam > 0 ? $jam . ' Jam ' : '' }}{{ $menit }} Menit
            </td>
        </tr>
    </table>

    <div class="divider-dashed"></div>

    <table class="total-row">
        <tr>
            <td style="padding: 5px 0;">TOTAL</td>
            <td align="right" style="padding: 5px 0;">Rp {{ number_format($data->total_bayar, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider-double"></div>
    
    <div class="text-center">
        <p style="margin: 5px 0;">Terima kasih atas kunjungan Anda</p>
        <p style="font-size: 11px; margin-top: 10px;">{{ $data->waktu_keluar->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>