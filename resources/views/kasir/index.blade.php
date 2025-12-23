<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir Parkir Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
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
        .btn-loading { pointer-events: none; opacity: 0.7; }
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
                    <form action="{{ route('parkir.masuk') }}" method="POST" onsubmit="showLoading(this)">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Jenis Kendaraan</label>
                            <select name="jenis" class="form-select form-select-lg shadow-sm border-primary">
                                <option value="motor">🏍️ Motor (Rp 2.000/jam)</option>
                                <option value="mobil">🚗 Mobil (Rp 5.000/jam)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow btn-submit">
                            <i class="fas fa-print"></i> PROSES MASUK
                        </button>
                    </form>

                    @if(session('last_id'))
                        <div class="alert alert-light mt-3 border text-center shadow-sm">
                            <p class="mb-2 small fw-bold text-muted">TIKET BERHASIL DIBUAT!</p>
                            <button onclick="cetakTiketMasuk('{{ session('last_kode') }}', '{{ session('last_jenis') }}', '{{ date('d/m/Y H:i') }}')" class="btn btn-dark btn-sm shadow-sm">
                                <i class="fas fa-file-pdf"></i> SIMPAN PDF TIKET
                            </button>
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
                    <form action="{{ route('parkir.keluar') }}" method="POST" onsubmit="showLoading(this)">
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
                        </div>
                        <button type="submit" class="btn btn-danger btn-lg w-100 shadow btn-submit">
                            <i class="fas fa-calculator"></i> PROSES BAYAR
                        </button>
                    </form>

                    @if(session('nota'))
                        <div class="alert nota-container mt-4 shadow-sm p-3">
                            <h6 class="fw-bold border-bottom pb-2 text-center text-dark"><i class="fas fa-receipt"></i> BUKTI PEMBAYARAN</h6>
                            <div class="d-flex justify-content-between mb-1 small">
                                <span>Kode:</span> <strong>{{ session('nota')['kode'] }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1 text-success">
                                <span>Kembalian:</span> <strong class="fs-5">Rp {{ number_format(session('nota')['kembalian'], 0, ',', '.') }}</strong>
                            </div>
                            <div class="mt-3">
                                <button onclick="cetakStrukUlang('{{ session('nota')['kode'] }}', '{{ session('nota')['plat'] }}', '{{ session('nota')['jenis'] }}', '{{ session('nota')['total'] }}')" class="btn btn-dark w-100 shadow-sm">
                                    <i class="fas fa-file-pdf"></i> SIMPAN PDF STRUK
                                </button>
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
                    <h5 class="mb-0"><i class="fas fa-hourglass-half"></i> Kendaraan Masih Parkir</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Waktu Masuk</th>
                                    <th>Kode Tiket</th>
                                    <th>Jenis</th>
                                    <th class="pe-4 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kendaraanAktif as $item)
                                <tr>
                                    <td class="ps-4">{{ $item->waktu_masuk->format('H:i') }}</td>
                                    <td><code class="fw-bold text-primary">{{ $item->kode_tiket }}</code></td>
                                    <td>{{ strtoupper($item->jenis) }}</td>
                                    <td class="pe-4 text-end">
                                        <button class="btn btn-sm btn-info text-white" 
                                                onclick="cetakTiketMasuk('{{ $item->kode_tiket }}', '{{ $item->jenis }}', '{{ $item->waktu_masuk->format('d/m/Y H:i') }}')">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="pilihTiket('{{ $item->kode_tiket }}', '5000')">Bayar</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // FUNGSI UTAMA: GENERATE PDF TIKET MASUK
    function cetakTiketMasuk(kode, jenis, waktu) {
        const element = document.createElement('div');
        element.style.padding = "20px";
        element.innerHTML = `
            <div style="font-family: 'Courier New', monospace; width: 160px; text-align: center; color: black;">
                <strong style="font-size: 16px;">TIKET PARKIR</strong><br>
                <span>POS DIGITAL</span>
                <hr style="border-top: 1px dashed #000;">
                <div style="font-size: 20px; font-weight: bold; margin: 10px 0; border: 1px solid #000; padding: 5px;">
                    ${kode}
                </div>
                <hr style="border-top: 1px dashed #000;">
                <div style="font-size: 12px; text-align: left;">
                    Jenis : ${jenis.toUpperCase()}<br>
                    Masuk : ${waktu}
                </div>
                <hr style="border-top: 1px dashed #000;">
                <p style="font-size: 10px;">Simpan tiket ini untuk<br>pembayaran saat keluar</p>
            </div>
        `;

        const opt = {
            margin: 0,
            filename: 'Tiket-' + kode + '.pdf',
            jsPDF: { unit: 'mm', format: [58, 80], orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    }

    // FUNGSI UTAMA: GENERATE PDF STRUK PEMBAYARAN
    function cetakStrukUlang(kode, plat, jenis, total) {
        const element = document.createElement('div');
        element.style.padding = "20px";
        element.innerHTML = `
            <div style="font-family: 'Courier New', monospace; width: 160px; text-align: center; color: black;">
                <strong style="font-size: 14px;">POS PARKIR DIGITAL</strong><br>
                <span style="font-size: 10px;">Struk Pembayaran</span>
                <hr style="border-top: 1px dashed #000;">
                <div style="font-size: 11px; text-align: left;">
                    Kode  : ${kode}<br>
                    Plat  : ${plat.toUpperCase()}<br>
                    Jenis : ${jenis.toUpperCase()}
                </div>
                <hr style="border-top: 1px dashed #000;">
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 12px;">
                    <span>TOTAL:</span>
                    <span>Rp ${Number(total).toLocaleString('id-ID')}</span>
                </div>
                <hr style="border-top: 1px dashed #000;">
                <div style="font-size: 10px; margin-top: 10px;">Terima Kasih</div>
            </div>
        `;

        const opt = {
            margin: 0,
            filename: 'Struk-' + kode + '.pdf',
            jsPDF: { unit: 'mm', format: [58, 100], orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    }

    function setBayar(amount) { document.getElementById('inputBayar').value = amount; }
    
    function pilihTiket(kode, tagihan) {
        document.getElementById('inputKode').value = kode;
        document.getElementById('inputBayar').value = tagihan;
        document.getElementById('inputPlat').focus();
    }

    function showLoading(form) {
        const btn = form.querySelector('.btn-submit');
        if(btn) {
            btn.classList.add('btn-loading');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>