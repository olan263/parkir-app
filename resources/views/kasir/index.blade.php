<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir Parkir Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .stat-card { border-left: 6px solid; }
        .table thead { background-color: #212529; color: white; }
        .btn-quick-cash { font-size: 0.85rem; padding: 5px 10px; border-radius: 20px; }
        .nota-container { border: 2px dashed #ffc107; background-color: #fffdf5; border-radius: 8px; }
        code { font-size: 1rem; color: #0d6efd; font-weight: bold; }
        .table-responsive { border-radius: 0 0 12px 12px; }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="fw-bold text-primary"><i class="fas fa-parking"></i> POS Kasir Parkir</h1>
        <p class="text-muted">Manajemen operasional real-time</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card stat-card border-success h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Pendapatan Hari Ini</small>
                        <h2 class="fw-bold mb-0 text-success">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h2>
                    </div>
                    <i class="fas fa-wallet fa-2x text-success opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card border-info h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Kendaraan di Dalam</small>
                        <h2 class="fw-bold mb-0 text-info">{{ $kendaraanDiDalam }} <small class="fs-6">Unit</small></h2>
                    </div>
                    <i class="fas fa-car fa-2x text-info opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5" id="pembayaran-section">
        <div class="col-md-6">
            <div class="card border-top border-primary border-4 h-100">
                <div class="card-header bg-white py-3"><h5 class="mb-0 text-primary fw-bold">Masuk (Ambil Tiket)</h5></div>
                <div class="card-body">
                    <form action="{{ route('parkir.masuk') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Jenis Kendaraan</label>
                            <select name="jenis" class="form-select form-select-lg">
                                <option value="motor">🏍️ Motor (Rp 2.000/jam)</option>
                                <option value="mobil">🚗 Mobil (Rp 5.000/jam)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">CETAK TIKET MASUK</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-top border-danger border-4 h-100">
                <div class="card-header bg-white py-3"><h5 class="mb-0 text-danger fw-bold">Keluar (Pembayaran)</h5></div>
                <div class="card-body">
                    <form action="{{ route('parkir.keluar') }}" method="POST">
                        @csrf
                        <div class="row g-2 mb-2">
                            <div class="col-6"><input type="text" name="kode_tiket" id="inputKode" class="form-control" placeholder="Kode Tiket" required></div>
                            <div class="col-6"><input type="text" name="plat_nomor" id="inputPlat" class="form-control text-uppercase" placeholder="Plat Nomor" required></div>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                <input type="number" name="bayar" id="inputBayar" class="form-control form-control-lg text-danger fw-bold" placeholder="Nominal Bayar" required>
                            </div>
                            <div class="mt-2 d-flex gap-1 flex-wrap">
                                <button type="button" class="btn btn-quick-cash btn-outline-secondary" onclick="setBayar(5000)">5rb</button>
                                <button type="button" class="btn btn-quick-cash btn-outline-secondary" onclick="setBayar(10000)">10rb</button>
                                <button type="button" class="btn btn-quick-cash btn-outline-secondary" onclick="setBayar(20000)">20rb</button>
                                <button type="button" class="btn btn-quick-cash btn-outline-secondary" onclick="setBayar(50000)">50rb</button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger btn-lg w-100 shadow-sm">PROSES PEMBAYARAN</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header bg-info text-white py-3"><h5 class="mb-0 small fw-bold text-uppercase">Kendaraan Masih Parkir</h5></div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Waktu Masuk</th>
                        <th>Kode Tiket</th>
                        <th>Jenis</th>
                        <th>Durasi</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kendaraanAktif as $item)
                    <tr>
                        <td class="ps-3">{{ $item->waktu_masuk->format('H:i') }} <small class="text-muted">({{ $item->waktu_masuk->diffForHumans() }})</small></td>
                        <td><code>{{ $item->kode_tiket }}</code></td>
                        <td><span class="badge {{ $item->jenis == 'mobil' ? 'bg-primary' : 'bg-warning text-dark' }}">{{ strtoupper($item->jenis) }}</span></td>
                        <td class="fw-bold">
                            @php
                                $totalMenit = $item->waktu_masuk->diffInMinutes(now());
                                $jam = floor($totalMenit / 60);
                                $menit = $totalMenit % 60;
                            @endphp
                            {{ $jam > 0 ? $jam.'j ' : '' }}{{ $menit }}m
                        </td>
                        <td class="text-end pe-3">
                            <button class="btn btn-sm btn-danger" onclick="pilihTiket('{{ $item->kode_tiket }}')">Proses Keluar</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4">Tidak ada kendaraan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 small fw-bold">RIWAYAT HARI INI</h5>
            <a href="{{ route('parkir.export.pdf') }}" class="btn btn-sm btn-danger">Export PDF</a>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Kode</th>
                        <th>Plat</th>
                        <th>Jenis</th>
                        <th>Total Bayar</th>
                        <th>Jam Keluar</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $data)
                    <tr>
                        <td class="ps-3 text-primary">#{{ $data->kode_tiket }}</td>
                        <td class="fw-bold">{{ strtoupper($data->plat_nomor) }}</td>
                        <td>{{ ucfirst($data->jenis) }}</td>
                        <td class="text-success fw-bold">Rp {{ number_format($data->total_bayar, 0, ',', '.') }}</td>
                        <td>{{ $data->waktu_keluar->format('H:i') }}</td>
                        <td class="text-end pe-3">
                            <a href="{{ route('parkir.cetak.keluar', $data->id) }}" class="btn btn-sm btn-light border" target="_blank"><i class="fas fa-print"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-3">Belum ada transaksi selesai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function setBayar(amount) { document.getElementById('inputBayar').value = amount; }
    function pilihTiket(kode) {
        document.getElementById('inputKode').value = kode;
        document.getElementById('inputPlat').focus();
        document.getElementById('pembayaran-section').scrollIntoView({ behavior: 'smooth' });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
