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
        .badge-active { animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .nota-container {
            border: 2px dashed #ffc107;
            background-color: #fffdf5;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-primary"><i class="fas fa-parking"></i> POS Kasir Parkir</h1>
        <p class="text-muted">Manajemen operasional parkir real-time</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i><strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card stat-card border-success shadow-sm bg-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1">Pendapatan Hari Ini</h6>
                        <h2 class="fw-bold mb-0 text-success">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h2>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-wallet fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card border-info shadow-sm bg-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1">Kendaraan di Dalam</h6>
                        <h2 class="fw-bold mb-0 text-info">{{ $kendaraanDiDalam }} <small class="text-muted fs-6">Unit</small></h2>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-car fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4" id="pembayaran-section">
        <div class="col-md-6">
            <div class="card shadow-sm h-100 border-top border-primary border-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 text-primary fw-bold"><i class="fas fa-sign-in-alt"></i> Masuk (Ambil Tiket)</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('parkir.masuk') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Jenis Kendaraan</label>
                            <select name="jenis" class="form-select form-select-lg shadow-sm border-primary">
                                <option value="motor">🏍️ Motor (Rp 2.000/jam)</option>
                                <option value="mobil">🚗 Mobil (Rp 5.000/jam)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow">
                            <i class="fas fa-print"></i> PROSES MASUK
                        </button>
                    </form>

                    @if(session('last_id'))
                        <div class="alert alert-light mt-3 border text-center shadow-sm">
                            <p class="mb-2 small fw-bold text-muted">TIKET TERBARU:</p>
                            <a href="{{ route('parkir.cetak.masuk', session('last_id')) }}" target="_blank" class="btn btn-dark btn-sm shadow-sm">
                                <i class="fas fa-print"></i> CETAK TIKET FISIK
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100 border-top border-danger border-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 text-danger fw-bold"><i class="fas fa-cash-register"></i> Keluar (Pembayaran)</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('parkir.keluar') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <input type="text" name="kode_tiket" id="inputKode" placeholder="Kode Tiket (TKT-XXXX)" class="form-control form-control-lg shadow-sm" required>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="plat_nomor" id="inputPlat" placeholder="Nomor Plat (B 1234 ABC)" class="form-control form-control-lg shadow-sm text-uppercase" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nominal Bayar</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                <input type="number" name="bayar" id="inputBayar" placeholder="0" class="form-control form-control-lg shadow-sm text-danger fw-bold" required>
                            </div>
                            <div class="mt-2 d-flex gap-1 flex-wrap">
                                <button type="button" class="btn btn-quick-cash btn-outline-secondary" onclick="setBayar(2000)">2rb</button>
                                <button type="button" class="btn btn-quick-cash btn-outline-secondary" onclick="setBayar(5000)">5rb</button>
                                <button type="button" class="btn btn-quick-cash btn-outline-secondary" onclick="setBayar(10000)">10rb</button>
                                <button type="button" class="btn btn-quick-cash btn-outline-secondary" onclick="setBayar(20000)">20rb</button>
                                <button type="button" class="btn btn-quick-cash btn-outline-secondary" onclick="setBayar(50000)">50rb</button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger btn-lg w-100 shadow">
                            <i class="fas fa-calculator"></i> PROSES BAYAR
                        </button>
                    </form>

                    @if(session('nota'))
                        <div class="alert nota-container mt-4 shadow-sm p-3">
                            <h6 class="fw-bold border-bottom pb-2 text-center text-dark"><i class="fas fa-receipt"></i> BUKTI PEMBAYARAN</h6>
                            <div class="d-flex justify-content-between mb-1 small">
                                <span>Kode:</span> <strong>{{ session('nota')['kode'] }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1 small">
                                <span>Durasi:</span> <strong>{{ session('nota')['durasi'] }}</strong>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between mb-1 text-success">
                                <span>Kembalian:</span> <strong class="fs-5">Rp {{ number_format(session('nota')['kembalian'], 0, ',', '.') }}</strong>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('parkir.cetak.keluar', session('nota')['id']) }}" target="_blank" class="btn btn-dark w-100 shadow-sm">
                                    <i class="fas fa-print"></i> CETAK NOTA KELUAR
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-hourglass-half"></i> Kendaraan Masih Parkir (Belum Bayar)</h5>
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
                                    <th class="pe-4 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kendaraanAktif as $item)
                                <tr>
                                    <td class="ps-4">
                                        {{ $item->waktu_masuk->format('H:i') }} 
                                        <small class="text-muted">({{ $item->waktu_masuk->diffForHumans() }})</small>
                                    </td>
                                    <td><code class="fw-bold text-primary">{{ $item->kode_tiket }}</code></td>
                                    <td>
                                        <span class="badge {{ $item->jenis == 'mobil' ? 'bg-primary' : 'bg-warning text-dark' }} px-3">
                                            {{ strtoupper($item->jenis) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $totalMenit = $item->waktu_masuk->diffInMinutes(now());
                                            $jam = floor($totalMenit / 60);
                                            $menit = $totalMenit % 60;
                                        @endphp
                                        <span class="fw-bold">{{ $jam > 0 ? $jam . 'j ' : '' }}{{ $menit }}m</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group shadow-sm">
                                            <a href="{{ route('parkir.edit', $item->id) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i></a>
                                            <button class="btn btn-sm btn-danger" onclick="pilihTiket('{{ $item->kode_tiket }}')">
                                                Keluar <i class="fas fa-arrow-up"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">Area parkir kosong.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Transaksi Hari Ini</h5>
                    <div class="btn-group">
                        <a href="{{ route('parkir.export.pdf') }}" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-file-pdf text-danger"></i> PDF
                        </a>
                        <a href="{{ route('parkir.export.excel') }}" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-file-excel text-success"></i> Excel
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Kode</th>
                                    <th>Plat Nomor</th>
                                    <th>Jenis</th>
                                    <th>Durasi</th>
                                    <th>Total Bayar</th>
                                    <th>Selesai</th>
                                    <th class="pe-4 text-end">Aksi</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $data)
                                <tr>
                                    <td class="ps-4"><span class="badge bg-light text-primary border">#{{ $data->kode_tiket }}</span></td>
                                    <td class="fw-bold text-uppercase">{{ $data->plat_nomor }}</td>
                                    <td>{{ ucfirst($data->jenis) }}</td>
                                    <td>
                                        @php
                                            $totalMenit = $data->waktu_masuk->diffInMinutes($data->waktu_keluar);
                                            $jam = floor($totalMenit / 60);
                                            $menit = $totalMenit % 60;
                                        @endphp
                                        {{ $jam > 0 ? $jam . 'j ' : '' }}{{ $menit }}m
                                    </td>
                                    <td class="text-success fw-bold">Rp {{ number_format($data->total_bayar, 0, ',', '.') }}</td>
                                    <td class="text-muted small">{{ $data->waktu_keluar->format('H:i') }}</td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group">
                                            <a href="{{ route('parkir.edit', $data->id) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('parkir.destroy', $data->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                            <a href="{{ route('parkir.cetak.keluar', $data->id) }}" target="_blank" class="btn btn-sm btn-dark"><i class="fas fa-receipt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada transaksi selesai.</td></tr>
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
    function setBayar(amount) {
        document.getElementById('inputBayar').value = amount;
    }

    function pilihTiket(kode) {
        document.getElementById('inputKode').value = kode;
        document.getElementById('inputPlat').focus();
        document.getElementById('pembayaran-section').scrollIntoView({ behavior: 'smooth' });
    }

    // Menghilangkan alert otomatis setelah 4 detik
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 4000);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>