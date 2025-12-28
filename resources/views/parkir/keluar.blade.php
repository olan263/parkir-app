@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm border-start border-danger border-4">
        <div>
            <h2 class="fw-bold text-danger mb-0"><i class="fas fa-cash-register"></i> KASIR KELUAR</h2>
            <p class="text-muted mb-0 small">Input kode tiket untuk proses pembayaran</p>
        </div>
        <div class="text-end">
            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Pendapatan Hari Ini</small>
            <h4 class="text-success fw-bold mb-0">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h4>
        </div>
    </div>

    <div class="row g-4">
        {{-- KOLOM INPUT PEMBAYARAN --}}
        <div class="col-md-4">
            {{-- NOTIFIKASI & TOMBOL CETAK NOTA --}}
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    <div class="text-center">
                        <i class="fas fa-check-circle fa-3x mb-2 text-success"></i>
                        <h5 class="fw-bold text-success">Berhasil!</h5>
                        <p class="small">{{ session('success') }}</p>
                        <hr>
                        {{-- INI TOMBOL CETAK NOTANYA --}}
                        <a href="{{ route('parkir.cetak.keluar', session('print_nota_id')) }}" 
                           target="_blank" class="btn btn-success w-100 fw-bold shadow-sm py-2">
                            <i class="fas fa-print me-2"></i> CETAK NOTA SEKARANG
                        </a>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger shadow-sm border-0 mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                </div>
            @endif

            <div class="card shadow-sm border-0 rounded-3 sticky-top" style="top: 20px;">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-file-invoice-dollar me-2"></i> Form Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('parkir.keluar') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Kode Tiket</label>
                            <input type="text" name="kode_tiket" id="inputKode" class="form-control form-control-lg border-2 border-danger" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nomor Plat</label>
                            <input type="text" name="plat_nomor" id="inputPlat" class="form-control form-control-lg text-uppercase" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nominal Bayar</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-danger text-white border-danger">Rp</span>
                                <input type="number" name="bayar" id="inputBayar" class="form-control fw-bold text-danger border-danger" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 btn-lg py-3 shadow fw-bold">
                            BAYAR & KELUAR <i class="fas fa-arrow-circle-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- DAFTAR KENDARAAN AKTIF --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-info text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold small text-uppercase">Kendaraan Masih Di Dalam</h5>
                    <span class="badge bg-white text-info">{{ $kendaraanAktif->count() }} Unit</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th class="ps-3">Waktu Masuk</th>
                                <th>Kode Tiket</th>
                                <th>Jenis</th>
                                <th>Estimasi Biaya</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kendaraanAktif as $item)
                            <tr>
                                <td class="ps-3 fw-bold text-secondary">{{ $item->waktu_masuk->format('H:i') }}</td>
                                <td><span class="badge bg-light text-dark border p-2">{{ $item->kode_tiket }}</span></td>
                                <td>{{ strtoupper($item->jenis) }}</td>
                                <td>
                                    @php
                                        $totalJam = ceil($item->waktu_masuk->diffInMinutes(now()) / 60);
                                        $tarif = ($item->jenis == 'mobil') ? 5000 : 2000;
                                        $biaya = ($totalJam <= 0 ? 1 : $totalJam) * $tarif;
                                    @endphp
                                    <span class="fw-bold text-danger">Rp {{ number_format($biaya, 0, ',', '.') }}</span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger px-3 rounded-pill" 
                                            onclick="pilihTiket('{{ $item->kode_tiket }}', {{ $biaya }})">
                                        Pilih <i class="fas fa-chevron-right ms-1"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted fst-italic">Kosong.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function pilihTiket(kode, harga) {
        document.getElementById('inputKode').value = kode;
        document.getElementById('inputBayar').value = harga;
        document.getElementById('inputPlat').focus();
    }
</script>
@endsection