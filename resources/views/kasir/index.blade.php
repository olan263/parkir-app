<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir Parkir Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
        }
        body { background-color: #f8f9fa; font-family: 'Inter', -apple-system, sans-serif; }
        .card { border: none; border-radius: 16px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        .stat-card { border-left: 6px solid; }
        .table thead { background-color: #f8f9fa; }
        .btn-quick-cash { 
            font-size: 0.85rem; 
            padding: 6px 12px; 
            border-radius: 8px;
            transition: all 0.2s;
        }
        .btn-quick-cash:hover { background-color: var(--primary-color); color: white; }
        .nota-container {
            border: 2px dashed #ffc107;
            background-color: #fffdf5;
            border-radius: 12px;
        }
        .btn-loading { pointer-events: none; filter: grayscale(1); }
        
        /* Layout Optimization */
        .sticky-stats { position: sticky; top: 20px; z-index: 100; }
        .form-control-lg, .form-select-lg { border-radius: 10px; font-size: 1rem; }
        
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
        <p class="text-muted">Kelola kendaraan masuk dan keluar dengan efisien</p>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-6">
            <div class="card stat-card border-success h-100 bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Pendapatan</p>
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
                            <p class="text-muted small text-uppercase fw-bold mb-1">Di Dalam</p>
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
                    <h5 class="card-title mb-0 text-primary fw-bold"><i class="fas fa-ticket-alt"></i> Kendaraan Masuk</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('parkir.masuk') }}" method="POST" onsubmit="showLoading(this)">
                        @csrf
                        <div class="mb-3">
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
                            <button onclick="cetakTiketMasuk('{{ session('last_kode') }}', '{{ session('last_jenis') }}', '{{ date('H:i:s') }}')" class="btn btn-dark btn-sm w-100">
                                <i class="fas fa-print"></i> CETAK ULANG
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
                    <h5 class="card-title mb-0 text-danger fw-bold"><i class="fas fa-cash-register"></i> Pembayaran Keluar</h5>
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
                                <button type="button" class="btn btn-quick-cash btn-outline-secondary" onclick="setBayar(2000)">2k</button>
                                <button type="button" class="btn btn-quick-cash btn-outline-secondary" onclick="setBayar(5000)">5k</button>
                                <button type="button" class="btn btn-quick-cash btn-outline-secondary" onclick="setBayar(10000)">10k</button>
                                <button type="button" class="btn btn-quick-cash btn-outline-secondary" onclick="setBayar(20000)">20k</button>
                                <button type="button" class="btn btn-quick-cash btn-outline-secondary" onclick="setBayar(50000)">50k</button>
                                <button type="button" class="btn btn-quick-cash btn-outline-info" onclick="hitungPas()">Uang Pas</button>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-danger btn-lg w-100 shadow-sm fw-bold btn-submit">
                            <i class="fas fa-check-circle me-2"></i>PROSES PEMBAYARAN
                        </button>
                    </form>

                    @if(session('nota'))
                        <div class="nota-container mt-3 p-3 text-center animate__animated animate__fadeIn">
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
            <h5 class="mb-0 fw-bold"><i class="fas fa-list-ul me-2"></i>Kendaraan Aktif</h5>
            <span class="badge bg-primary">{{ count($kendaraanAktif) }} Unit</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Masuk</th>
                        <th>Kode Tiket</th>
                        <th>Jenis</th>
                        <th>Durasi</th>
                        <th>Estimasi Tagihan</th>
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
                        $totalTagihan = max(1, ceil($totalMenit / 60)) * $tarif;
                    @endphp
                    <tr>
                        <td class="ps-4 small">
                            <strong>{{ $item->waktu_masuk->format('H:i') }}</strong><br>
                            <span class="text-muted text-xs">{{ $item->waktu_masuk->diffForHumans() }}</span>
                        </td>
                        <td><code class="fw-bold fs-6">{{ $item->kode_tiket }}</code></td>
                        <td>
                            <span class="badge {{ $item->jenis == 'mobil' ? 'bg-primary' : 'bg-warning text-dark' }} rounded-pill px-3">
                                {{ strtoupper($item->jenis) }}
                            </span>
                        </td>
                        <td><span class="fw-bold">{{ $jam }}j {{ $menit }}m</span></td>
                        <td><strong class="text-danger">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</strong></td>
                        <td class="pe-4 text-end">
                            <button class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3" onclick="pilihTiket('{{ $item->kode_tiket }}', '{{ $totalTagihan }}')">
                                Keluar <i class="fas fa-external-link-alt ms-1"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada kendaraan di area parkir.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let currentTagihan = 0;

    function setBayar(amount) {
        document.getElementById('inputBayar').value = amount;
    }

    function hitungPas() {
        if(currentTagihan > 0) {
            document.getElementById('inputBayar').value = currentTagihan;
        }
    }

    function pilihTiket(kode, tagihan) {
        currentTagihan = tagihan;
        document.getElementById('inputKode').value = kode;
        document.getElementById('inputBayar').value = tagihan; 
        document.getElementById('inputPlat').focus();
        document.getElementById('pembayaran-section').scrollIntoView({ behavior: 'smooth' });
        
        // Animasi highlight input
        const inputKode = document.getElementById('inputKode');
        inputKode.classList.add('is-valid');
        setTimeout(() => inputKode.classList.remove('is-valid'), 2000);
    }

    function showLoading(form) {
        const btn = form.querySelector('.btn-submit');
        btn.classList.add('btn-loading');
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Memproses...`;
    }

    // Fungsi Cetak disempurnakan (Thermal 58mm)
    function cetakTiketMasuk(kode, jenis, waktu) {
        const win = window.open('', '_blank', 'width=300,height=450');
        win.document.write(`
            <html><head><style>
                body { font-family: 'Courier New', monospace; width: 50mm; padding: 5mm; font-size: 12px; }
                .center { text-align: center; }
                .hr { border-top: 1px dashed #000; margin: 5px 0; }
                .kode { font-size: 18px; font-weight: bold; margin: 10px 0; display: block; }
            </style></head>
            <body onload="window.print(); window.close();">
                <div class="center">
                    <strong style="font-size: 14px;">E-PARKING SYSTEM</strong><br>
                    TIKET MASUK KENDARAAN
                    <div class="hr"></div>
                    <span class="kode">${kode}</span>
                    <div class="hr"></div>
                    <div style="text-align: left">
                        Jenis: ${jenis.toUpperCase()}<br>
                        Waktu: ${waktu}<br>
                    </div>
                    <div class="hr"></div>
                    <p style="font-size: 10px">Simpan tiket ini untuk<br>proses pembayaran keluar</p>
                </div>
            </body></html>
        `);
        win.document.close();
    }

    function cetakStrukUlang(kode, plat, jenis, total) {
        const win = window.open('', '_blank', 'width=300,height=500');
        win.document.write(`
            <html><head><style>
                body { font-family: 'Courier New', monospace; width: 50mm; padding: 5mm; font-size: 11px; }
                .center { text-align: center; }
                .hr { border-top: 1px dashed #000; margin: 5px 0; }
                .item { display: flex; justify-content: space-between; margin-bottom: 3px; }
            </style></head>
            <body onload="window.print(); window.close();">
                <div class="center">
                    <strong>BUKTI PEMBAYARAN PARKIR</strong><br>
                    <div class="hr"></div>
                    <div class="item"><span>Kode:</span> <span>${kode}</span></div>
                    <div class="item"><span>Plat:</span> <span>${plat.toUpperCase()}</span></div>
                    <div class="item"><span>Jenis:</span> <span>${jenis.toUpperCase()}</span></div>
                    <div class="hr"></div>
                    <div class="item" style="font-weight:bold; font-size: 14px;">
                        <span>TOTAL:</span> <span>Rp ${Number(total).toLocaleString('id-ID')}</span>
                    </div>
                    <div class="hr"></div>
                    <p>Terima Kasih Atas Kunjungan Anda</p>
                </div>
            </body></html>
        `);
        win.document.close();
    }
</script>

</body>
</html>