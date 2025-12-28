<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Keluar - {{ $data->kode_tiket }}</title>
    <style>
        @page { 
            margin: 0; 
        }
        body { 
            margin: 0;
            padding: 15px; /* Memberi ruang agar teks tidak menempel ke pinggir kertas */
            font-family: 'Courier', monospace; /* Menggunakan Courier standar PDF */
            font-size: 12px;
            line-height: 1.3;
        }
        .text-center { text-align: center; }
        .divider-double { border-top: 2px double #000; margin: 8px 0; }
        .divider-dashed { border-top: 1px dashed #000; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 4px 0; vertical-align: top; }
        .total-row { 
            font-size: 15px; 
            font-weight: bold; 
            border-top: 1px solid #000; 
            border-bottom: 1px solid #000;
            margin: 5px 0;
        }
        .total-row td { padding: 8px 0; }
    </style>
</head>
<body>
    <div class="text-center">
        <h3 style="margin: 0; font-size: 15px;">STRUK PEMBAYARAN</h3>
        <p style="margin: 2px 0; font-size: 10px;">PARKIR DIGITAL CENTER</p>
        <div class="divider-double"></div>
    </div>

    <table>
        <tr>
            <td width="45%">No. Tiket</td>
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
            <td>Masuk</td>
            <td align="right">{{ $data->waktu_masuk->format('d/m/y H:i') }}</td>
        </tr>
        <tr>
            <td>Keluar</td>
            <td align="right">{{ $data->waktu_keluar->format('d/m/y H:i') }}</td>
        </tr>
        <tr>
            <td>Durasi</td>
            <td align="right">
                @php
                    $totalMenit = $data->waktu_masuk->diffInMinutes($data->waktu_keluar);
                    $jam = floor($totalMenit / 60);
                    $menit = $totalMenit % 60;
                @endphp
                {{ $jam > 0 ? $jam . 'j ' : '' }}{{ $menit }}m
            </td>
        </tr>
    </table>

    <div class="divider-dashed"></div>

    <table class="total-row">
        <tr>
            <td>TOTAL</td>
            <td align="right">Rp {{ number_format($data->total_bayar, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider-double"></div>
    
    <div class="text-center">
        <p style="margin: 5px 0; font-size: 11px;">Terima kasih atas kunjungan Anda</p>
        <p style="font-size: 10px; margin-top: 5px; color: #555;">{{ $data->waktu_keluar->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
