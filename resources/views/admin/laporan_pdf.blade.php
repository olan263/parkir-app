<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Parkir - {{ date('d/m/Y') }}</title>
    <style>
        @page { margin: 1cm; } /* Margin standar kertas A4 */
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; line-height: 1.4; }
        
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1a5276; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #1a5276; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #555; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #1a5276; color: white; padding: 8px; border: 1px solid #ddd; text-transform: uppercase; }
        td { padding: 7px; border: 1px solid #ddd; vertical-align: middle; }
        
        .bg-light { background-color: #f9f9f9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        
        .badge { padding: 3px 6px; border-radius: 4px; color: white; font-size: 9px; }
        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; color: #000; }

        .summary-box { margin-top: 15px; width: 300px; float: right; }
        .footer { clear: both; margin-top: 50px; text-align: right; font-style: italic; font-size: 9px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN RIWAYAT PARKIR</h2>
        <p>Dicetak Pada: {{ date('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">Kode Tiket</th>
                <th width="12%">Plat Nomor</th>
                <th width="8%">Jenis</th>
                <th width="18%">Waktu Masuk</th>
                <th width="18%">Waktu Keluar</th>
                <th width="12%">Total Bayar</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($semuaTransaksi as $index => $item)
                @php $grandTotal += $item->total_bayar; @endphp
                <tr class="{{ $index % 2 == 0 ? '' : 'bg-light' }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center fw-bold">{{ $item->kode_tiket }}</td>
                    <td class="text-center">{{ $item->plat_nomor ?? '-' }}</td>
                    <td class="text-center">{{ ucfirst($item->jenis) }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->waktu_masuk)->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->waktu_keluar ? \Carbon\Carbon::parse($item->waktu_keluar)->format('d/m/Y H:i') : '-' }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="badge {{ $item->status == 'selesai' ? 'bg-success' : 'bg-warning' }}">
                            {{ strtoupper($item->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">Data transaksi tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="6" class="text-right">TOTAL PENDAPATAN</td>
                <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh Sistem Parkir Digital Center.</p>
        <p>Halaman 1 dari 1</p>
    </div>

</body>
</html>