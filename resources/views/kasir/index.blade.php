<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir Parkir Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #2ecc71;
            --info-color: #0dcaf0;
        }
        body { 
            background-color: #f8f9fa; 
            font-family: 'Inter', sans-serif; 
            color: #333;
        }
        .card { 
            border: none; 
            border-radius: 16px; 
            transition: all 0.3s ease; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
        }
        .card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        .stat-card { border-left: 6px solid; }
        
        /* Table Styling */
        .table thead { background-color: #f8f9fa; }
        .table-hover tbody tr:hover { background-color: #f1f4ff; }
        
        /* Button Styling */
        .btn-quick-cash { 
            font-size: 0.85rem; 
            padding: 6px 12px; 
            border-radius: 8px;
            border: 1px solid #dee2e6;
            background: white;
            transition: all 0.2s;
        }
        .btn-quick-cash:hover { 
            background-color: var(--primary-color); 
            color: white; 
            border-color: var(--primary-color);
        }
        
        .nota-container {
            border: 2px dashed #ffc107;
            background-color: #fffdf5;
            border-radius: 12px;
        }
        .btn-loading { pointer-events: none; filter: grayscale(1); opacity: 0.8; }
        
        .badge-jenis {
            padding: 0.5em 1em;
            border-radius: 8px;
            font-weight: 600;
        }

        @media print {
            body * { visibility: hidden; }
            .print-section, .print-section * { visibility: visible; }
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="display-6 fw-bold text-primary"><i class="fas fa-parking"></i> POS Kasir Parkir</h1>
        <p class="text-muted">Manajemen operasional real-time & efisien</p>
    </div>

    {{-- Alert Notification --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-6">
            <div class="card stat-card border-success h-100 bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Pendapatan Hari Ini</p>
                            <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <i class="fas fa-wallet fa-2x text-success opacity-25 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="card stat-card border-info h-100 bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Kendaraan Di Dalam</p>
                            <h3 class="fw-bold mb-0 text-info">{{ $kendaraanDiDalam ?? 0 }} <small class="fs-6">Unit</small></h3>
                        </div>
                        <i class="fas fa-car fa-2x text-info opacity-25 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Action Section --}}
    <div class="row g-4" id="pembayaran-section">
        {{-- Masuk Section --}}
        <div class="col-md-5">
            <div class="card h-100 border-top border-primary border-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 text-primary fw-bold"><i class="fas fa-ticket-alt me-2"></i>Kendaraan Masuk</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('parkir.masuk') }}" method="POST" onsubmit="showLoading(this)">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Pilih Jenis Kendaraan</label>
                            <select name="jenis" class="form-select form-select-lg shadow-sm border-primary">
                                <option value="motor">🏍️ Motor (Rp 2.000/jam)</option>
                                <option value="mobil">🚗 Mobil (Rp 5.000/jam)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm fw-bold btn-submit">
                            <i class="fas fa-print me-2"></i>AMBIL TIKET
                        </button>
                    </form>

                    @if(session('last_kode'))
                        <div class="alert alert-primary mt-3 text-center border-0 shadow-sm">
                            <p class="mb-2 small fw-bold">TIKET BARU: {{ session('last_kode') }}</p>
                            <button onclick="cetakTiketMasuk('{{ session('last_kode') }}', '{{ session('last_jenis') }}', '{{ date('d/m/Y H:i') }}')" class="btn btn-dark btn-sm w-100">
                                <i class="fas fa-print me-2"></i>CETAK ULANG
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Keluar Section --}}
        <div class="col-md-7">
            <div class="card h-100 border-top border-danger border-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 text-danger fw-bold"><i class="fas fa-cash-register me-2"></i>Pembayaran Keluar</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('parkir.keluar') }}" method="POST" onsubmit="showLoading(this)">
                        @csrf
                        <div class="row g-2 mb-3">
                            <div class="col-md-5">
                                <label class="form-label small fw-bold text-muted">KODE TIKET</label>
                                <input type="text" name="kode_tiket" id="inputKode" placeholder="TKT-XXXX" class="form-control form-control-lg fw-bold" required>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label small fw-bold text-muted">NOMOR PLAT</label>
                                <input type="text" name="plat_nomor" id="inputPlat" placeholder="B 1234 ABC" class="form-control form-control-lg text-uppercase fw-bold" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">NOMINAL BAYAR (CASH)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                <input type="number" name="bayar" id="inputBayar" placeholder="0" class="form-control form-control-lg text-danger fw-bold" required>
                            </div>
                            <div class="mt-2 d-flex gap-1 flex-wrap">
                                <button type="button" class="btn btn-quick-cash" onclick="setBayar(2000)">2k</button>
                                <button type="button" class="btn btn-quick-cash" onclick="setBayar(5000)">5k</button>
                                <button type="button" class="btn btn-quick-cash" onclick="setBayar(10000)">10k</button>
                                <button type="button" class="btn btn-quick-cash" onclick="setBayar(20000)">20k</button>
                                <button type="button" class="btn btn-quick-cash" onclick="setBayar(50000)">50k</button>
                                <button type="button" class="btn btn-quick-cash btn-outline-info" onclick="hitungPas()">Uang Pas</button>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-danger btn-lg w-100 shadow-sm fw-bold btn-submit">
                            <i class="fas fa-check-circle me-2"></i>PROSES PEMBAYARAN
                        </button>
                    </form>

                    @if(session('nota'))
                        <div class="nota-container mt-3 p-3 text-center">
                            <p class="mb-1 small text-muted text-uppercase">Kembalian Anda:</p>
                            <h2 class="fw-bold text-success mb-3">Rp {{ number_format(session('nota')['kembalian'], 0, ',', '.') }}</h2>
                            <button onclick="cetakStrukUlang('{{ session('nota')['kode'] }}', '{{ session('nota')['plat'] }}', '{{ session('nota')['jenis'] }}', '{{ session('nota')['total'] }}')" class="btn btn-dark w-100">
                                <i class="fas fa-print me-2"></i>CETAK STRUK PEMBAYARAN
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Active Parking List --}}
    <div class="card mt-5 overflow-hidden">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-hourglass-half me-2"></i>Kendaraan Aktif (Belum Bayar)</h5>
            <span class="badge bg-primary px-3">{{ count($kendaraanAktif) }} Unit</span>
        </div>
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
                        $durasiJamBulat = max(1, ceil($totalMenit / 60));
                        $tarif = ($item->jenis == 'mobil') ? 5000 : 2000;
                        $totalTagihan = $durasiJamBulat * $tarif;
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <span class="fw-semibold">{{ $item->waktu_masuk->format('H:i') }}</span>
                            <div class="text-muted small">{{ $item->waktu_masuk->diffForHumans() }}</div>
                        </td>
                        <td><code class="fw-bold text-primary">{{ $item->kode_tiket }}</code></td>
                        <td>
                            <span class="badge badge-jenis {{ $item->jenis == 'mobil' ? 'bg-primary-subtle text-primary border border-primary' : 'bg-warning-subtle text-dark border border-warning' }}">
                                {{ strtoupper($item->jenis) }}
                            </span>
                        </td>
                        <td><span class="fw-bold text-dark">{{ $jam > 0 ? $jam . 'j ' : '' }}{{ $menit }}m</span></td>
                        <td><strong class="text-danger">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</strong></td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cetakTiketMasuk('{{ $item->kode_tiket }}', '{{ $item->jenis }}', '{{ $item->waktu_masuk->format('d/m/Y H:i') }}')">
                                    <i class="fas fa-print"></i>
                                </button>
                                <button class="btn btn-sm btn-danger px-3" onclick="pilihTiket('{{ $item->kode_tiket }}', '{{ $totalTagihan }}')">
                                    Bayar <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">Area parkir kosong saat ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Transaction History --}}
    <div class="card mt-5 overflow-hidden">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2"></i>Riwayat Transaksi Hari Ini</h5>
            <div class="btn-group shadow-sm">
                <a href="{{ route('parkir.export.pdf') }}" class="btn btn-sm btn-outline-danger"><i class="fas fa-file-pdf me-1"></i>PDF</a>
                <a href="{{ route('parkir.export.excel') }}" class="btn btn-sm btn-outline-success"><i class="fas fa-file-excel me-1"></i>Excel</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Plat Nomor</th>
                        <th>Jenis</th>
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
                        <td class="text-success fw-bold">Rp {{ number_format($data->total_bayar, 0, ',', '.') }}</td>
                        <td class="text-muted small">{{ $data->waktu_keluar->format('H:i') }}</td>
                        <td class="pe-4 text-end">
                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                    onclick="cetakStrukUlang('{{ $data->kode_tiket }}', '{{ $data->plat_nomor }}', '{{ $data->jenis }}', '{{ $data->total_bayar }}')">
                                <i class="fas fa-print"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi selesai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function setBayar(amount) {
        document.getElementById('inputBayar').value = amount;
    }

    function hitungPas() {
        // Logika untuk mengambil tagihan dari input atau variabel jika ada
        const totalTagihan = document.getElementById('inputBayar').getAttribute('data-tagihan');
        if(totalTagihan) document.getElementById('inputBayar').value = totalTagihan;
    }

    function pilihTiket(kode, tagihan) {
        document.getElementById('inputKode').value = kode;
        document.getElementById('inputBayar').value = tagihan; 
        document.getElementById('inputBayar').setAttribute('data-tagihan', tagihan);
        document.getElementById('inputPlat').focus();
        document.getElementById('pembayaran-section').scrollIntoView({ behavior: 'smooth' });
    }

    function showLoading(form) {
        const btn = form.querySelector('.btn-submit');
        if(btn) {
            btn.classList.add('btn-loading');
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin me-2"></i>Memproses...';
        }
    }

    function cetakTiketMasuk(kode, jenis, waktu) {
        const printWindow = window.open('', '_blank', 'width=300,height=450');
        printWindow.document.write(`
            <html>
            <head>
                <style>
                    body { font-family: 'Courier New', monospace; width: 58mm; padding: 10px; font-size: 12px; }
                    .text-center { text-align: center; }
                    .line { border-top: 1px dashed #000; margin: 5px 0; }
                    .barcode { font-size: 16px; margin: 10px 0; font-weight: bold; border: 1px solid #000; padding: 5px; display: inline-block; }
                </style>
            </head>
            <body onload="window.print(); setTimeout(() => { window.close(); }, 500);">
                <div class="text-center">
                    <div style="font-weight:bold; font-size: 14px;">TIKET PARKIR</div>
                    <div class="line"></div>
                    <div class="barcode">${kode}</div>
                    <div class="line"></div>
                    <div style="text-align: left;">
                        Jenis : ${jenis.toUpperCase()}<br>
                        Masuk : ${waktu}
                    </div>
                    <div class="line"></div>
                    <p style="font-size: 10px;">Simpan tiket untuk pembayaran</p>
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    function cetakStrukUlang(kode, plat, jenis, total) {
        const printWindow = window.open('', '_blank', 'width=300,height=600');
        printWindow.document.write(`
            <html>
            <head>
                <style>
                    body { font-family: 'Courier New', monospace; padding: 15px; width: 58mm; font-size: 12px; }
                    .text-center { text-align: center; }
                    .line { border-top: 1px dashed black; margin: 8px 0; }
                    .item { display: flex; justify-content: space-between; margin: 3px 0; }
                </style>
            </head>
            <body onload="window.print(); setTimeout(() => { window.close(); }, 500);">
                <div class="text-center" style="font-weight:bold; font-size: 14px;">POS PARKIR DIGITAL</div>
                <div class="text-center">Struk Pembayaran</div>
                <div class="line"></div>
                <div class="item"><span>Kode:</span> <span>${kode}</span></div>
                <div class="item"><span>Plat:</span> <span>${plat.toUpperCase()}</span></div>
                <div class="item"><span>Jenis:</span> <span>${jenis.toUpperCase()}</span></div>
                <div class="line"></div>
                <div class="item" style="font-weight:bold"><span>TOTAL:</span> <span>Rp ${Number(total).toLocaleString('id-ID')}</span></div>
                <div class="line"></div>
                <div class="text-center" style="margin-top: 10px;">Terima Kasih</div>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    // Auto-close alert
    setTimeout(() => {
        document.querySelectorAll('.alert-dismissible').forEach(el => {
            const alert = bootstrap.Alert.getOrCreateInstance(el);
            alert.close();
        });
    }, 4000);
</script>

</body>
</html>