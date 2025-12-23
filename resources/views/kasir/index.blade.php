<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir Parkir Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border: none; border-radius: 12px; transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); }
        .stat-card { border-left: 5px solid; }
        .table thead { background-color: #212529; color: white; }
        .btn-quick-cash { font-size: 0.8rem; padding: 2px 8px; }
        .nota-container { border: 2px dashed #ffc107; background-color: #fffdf5; border-radius: 8px; }
        .btn-loading { pointer-events: none; opacity: 0.7; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-primary"><i class="fas fa-parking"></i> POS Kasir Parkir</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4" id="pembayaran-section">
        <div class="col-md-6">
            <div class="card shadow-sm h-100 border-top border-primary border-4">
                <div class="card-header bg-white py-3"><h5 class="mb-0 text-primary fw-bold">Masuk</h5></div>
                <div class="card-body">
                    <form action="{{ route('parkir.masuk') }}" method="POST" onsubmit="showLoading(this)">
                        @csrf
                        <select name="jenis" class="form-select mb-3">
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                        </select>
                        <button type="submit" class="btn btn-primary w-100 btn-submit">PROSES MASUK</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100 border-top border-danger border-4">
                <div class="card-header bg-white py-3"><h5 class="mb-0 text-danger fw-bold">Keluar</h5></div>
                <div class="card-body">
                    <form action="{{ route('parkir.keluar') }}" method="POST" onsubmit="showLoading(this)">
                        @csrf
                        <input type="text" name="kode_tiket" id="inputKode" placeholder="Kode Tiket" class="form-control mb-2" required>
                        <input type="number" name="bayar" id="inputBayar" placeholder="Nominal Bayar" class="form-control mb-3" required>
                        <button type="submit" class="btn btn-danger w-100 btn-submit">PROSES BAYAR</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-hourglass-half"></i> Kendaraan Masih Parkir</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Waktu Masuk</th>
                                    <th>Kode Tiket</th>
                                    <th>Jenis</th>
                                    <th>Durasi</th>
                                    <th>Tagihan</th>
                                    <th class="pe-4 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kendaraanAktif as $item)
                                @php
                                    $totalMenit = $item->waktu_masuk->diffInMinutes(now());
                                    $jam = floor($totalMenit / 60);
                                    $menit = $totalMenit % 60;
                                    $tarif = ($item->jenis == 'mobil') ? 5000 : 2000;
                                    $totalTagihan = ceil($totalMenit / 60) * $tarif;
                                    if($totalTagihan == 0) $totalTagihan = $tarif;
                                @endphp
                                <tr>
                                    <td class="ps-4">{{ $item->waktu_masuk->format('H:i') }}</td>
                                    <td><code class="fw-bold text-primary">{{ $item->kode_tiket }}</code></td>
                                    <td><span class="badge {{ $item->jenis == 'mobil' ? 'bg-primary' : 'bg-warning text-dark' }}">{{ strtoupper($item->jenis) }}</span></td>
                                    <td>{{ $jam }}j {{ $menit }}m</td>
                                    <td><strong class="text-danger">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</strong></td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group shadow-sm">
                                            <button onclick="cetakTiketMasuk('${{ $item->kode_tiket }}', '{{ $item->jenis }}', '{{ $item->waktu_masuk }}')" class="btn btn-sm btn-info text-white" title="Cetak Ulang Tiket">
                                                <i class="fas fa-print"></i>
                                            </button>
                                            
                                            <button class="btn btn-sm btn-danger" onclick="pilihTiket('{{ $item->kode_tiket }}', '{{ $totalTagihan }}')">
                                                Keluar <i class="fas fa-arrow-up"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-4 text-muted">Area parkir kosong.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // FUNGSI UNTUK CETAK TIKET MASUK
    function cetakTiketMasuk(kode, jenis, waktu) {
        const printWindow = window.open('', '_blank', 'width=300,height=450');
        printWindow.document.write(`
            <html>
            <head>
                <style>
                    body { font-family: 'Courier New', monospace; width: 58mm; padding: 10px; font-size: 12px; }
                    .text-center { text-align: center; }
                    .barcode { font-size: 20px; letter-spacing: 5px; margin: 10px 0; }
                    .line { border-top: 1px dashed #000; margin: 5px 0; }
                </style>
            </head>
            <body onload="window.print(); window.close();">
                <div class="text-center">
                    <h3 style="margin:0">TIKET PARKIR</h3>
                    <div class="line"></div>
                    <div class="barcode">${kode}</div>
                    <div class="line"></div>
                    <p>Jenis: ${jenis.toUpperCase()}<br>
                    Masuk: ${waktu}</p>
                    <div class="line"></div>
                    <p style="font-size: 10px">Simpan tiket ini untuk<br>proses pembayaran keluar</p>
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    function setBayar(amount) { document.getElementById('inputBayar').value = amount; }

    function pilihTiket(kode, tagihan) {
        document.getElementById('inputKode').value = kode;
        document.getElementById('inputBayar').value = tagihan;
        document.getElementById('pembayaran-section').scrollIntoView({ behavior: 'smooth' });
    }

    function showLoading(form) {
        const btn = form.querySelector('.btn-submit');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        btn.classList.add('btn-loading');
    }
</script>

</body>
</html>