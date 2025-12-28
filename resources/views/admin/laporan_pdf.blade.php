<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Parkir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: center;
        }
        td {
            padding: 8px;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Data Parkir</h2>
        <p>Tanggal Cetak: {{ date('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Tiket</th>
                <th>Plat Nomor</th>
                <th>Jenis Kendaraan</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
                <th>Total Biaya</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($data as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item->kode_tiket }}</td>
                    <td>{{ $item->plat_nomor }}</td>
                    <td>{{ $item->jenis_kendaraan }}</td>
                    <td>{{ $item->waktu_masuk }}</td>
                    <td>{{ $item->waktu_keluar ?? '-' }}</td>
                    <td>Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</td>
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
        <p>Dicetak oleh Sistem Aplikasi Parkir</p>
    </div>

</body>
</html>