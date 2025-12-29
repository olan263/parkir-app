@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark"><i class="fas fa-cash-register me-2"></i>KASIR KELUAR</h2>
            <p class="text-muted">Kelola pembayaran dan kendaraan keluar</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="bg-white p-3 rounded shadow-sm d-inline-block border-start border-success border-4">
                <p class="mb-0 text-muted small">Total Pendapatan Hari Ini</p>
                <h3 class="text-success fw-bold mb-0">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-danger text-white fw-bold py-3">
                    <i class="fas fa-file-invoice-dollar me-2"></i>PROSES PEMBAYARAN
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('parkir.keluar') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">KODE TIKET</label>
                            <input type="text" name="kode_tiket" id="kode_tiket" class="form-control form-control-lg border-2" placeholder="TKT-XXXXXX" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">NOMOR PLAT</label>
                            <input type="text" name="plat_nomor" id="plat_nomor" class="form-control form-control-lg border-2 text-uppercase" placeholder="B 1234 ABC" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">NOMINAL BAYAR (RP)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                <input type="number" name="bayar" id="bayar" class="form-control form-control-lg border-2 fw-bold text-danger" placeholder="0" required>
                            </div>
                            <div id="info-tarif" class="form-text text-primary fw-bold mt-2"></div>
                        </div>
                        <button type="submit" class="btn btn-danger btn-lg w-100 shadow">
                            <i class="fas fa-check-circle me-2"></i>PROSES KELUAR
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white fw-bold py-3">
                    <i class="fas fa-car me-2"></i>KENDARAAN MASIH TERPARKIR
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">JAM MASUK</th>
                                    <th>KODE TIKET</th>
                                    <th>JENIS</th>
                                    <th class="text-end pe-4">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kendaraanAktif as $k)
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge bg-light text-dark border"><i class="far fa-clock me-1 text-primary"></i> {{ \Carbon\Carbon::parse($k->waktu_masuk)->format('H:i') }}</span>
                                    </td>
                                    <td><strong class="text-primary">{{ $k->kode_tiket }}</strong></td>
                                    <td>
                                        @if($k->jenis == 'mobil')
                                            <span class="badge bg-info text-dark"><i class="fas fa-car me-1"></i> MOBIL</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="fas fa-motorcycle me-1"></i> MOTOR</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-primary px-3 rounded-pill" 
                                            onclick="isiOtomatis('{{ $k->kode_tiket }}', '{{ $k->jenis }}', '{{ $k->waktu_masuk }}')">
                                            PILIH <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">Tidak ada kendaraan di dalam.</td>
                                </tr>
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
function isiOtomatis(kode, jenis, waktuMasuk) {
    // 1. Masukkan kode ke form
    document.getElementById('kode_tiket').value = kode;
    
    // 2. Hitung durasi & tarif sederhana
    let tglMasuk = new Date(waktuMasuk);
    let tglSekarang = new Date();
    
    // Selisih dalam jam (bulatkan ke atas)
    let selisihMs = tglSekarang - tglMasuk;
    let selisihJam = Math.ceil(selisihMs / (1000 * 60 * 60));
    
    if (selisihJam <= 0) selisihJam = 1;

    let tarifPerJam = (jenis === 'mobil') ? 5000 : 2000;
    let totalTagihan = selisihJam * tarifPerJam;

    // 3. Masukkan ke input bayar & tampilkan info
    document.getElementById('bayar').value = totalTagihan;
    document.getElementById('info-tarif').innerText = "Estimasi: " + selisihJam + " Jam x Rp " + tarifPerJam.toLocaleString('id-ID');
    
    // 4. Fokus ke input plat nomor agar kasir cepat mengetik
    document.getElementById('plat_nomor').focus();
}
</script>
@endsection