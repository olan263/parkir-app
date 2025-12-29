@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- HEADER --}}
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-primary">
            <i class="fas fa-sign-in-alt"></i> GATE MASUK
        </h1>
        <p class="text-muted">Pilih jenis kendaraan untuk mendaftarkan parkir baru</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            
            {{-- NOTIFIKASI & TOMBOL CETAK --}}
            @if(session('success'))
                <div class="alert alert-success shadow-lg border-start border-4 border-success mb-4 animate__animated animate__fadeIn">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle fa-3x me-3 text-success"></i>
                        <div>
                            <h5 class="alert-heading mb-1 fw-bold">Transaksi Berhasil!</h5>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                    </div>
                    <hr>
                    {{-- CEK APAKAH ID TERSEDIA SEBELUM MENAMPILKAN TOMBOL --}}
                    @if(session('last_id'))
                        <a href="{{ route('parkir.cetak.masuk', session('last_id')) }}" 
                           target="_blank" 
                           class="btn btn-warning btn-lg w-100 fw-bold shadow-sm">
                            <i class="fas fa-print me-2"></i> KLIK UNTUK CETAK TIKET (PDF)
                        </a>
                    @else
                        <div class="alert alert-warning small py-2 mb-0">
                            <i class="fas fa-info-circle me-1"></i> Gagal memuat ID tiket. Silakan cek menu Laporan untuk cetak ulang.
                        </div>
                    @endif
                </div>
            @endif

            {{-- STATISTIK RINGKAS --}}
            <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div class="small text-uppercase fw-bold opacity-75">Kendaraan Di Dalam:</div>
                    <h3 class="fw-bold mb-0">{{ $kendaraanDiDalam }} <small class="fs-6 text-white-50">Unit</small></h3>
                </div>
            </div>

            {{-- FORM INPUT --}}
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-0 border-bottom">
                    <span class="fw-bold text-secondary text-uppercase small tracking-wider">
                        <i class="fas fa-list me-2 text-primary"></i> Form Tiket Baru
                    </span>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('parkir.masuk') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small">JENIS KENDARAAN</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-primary text-primary">
                                    <i class="fas fa-motorcycle" id="icon-kendaraan"></i>
                                </span>
                                <select name="jenis" id="select-jenis" class="form-select border-primary fw-bold" required>
                                    <option value="motor" data-icon="fa-motorcycle">🏍️ MOTOR (Rp 2.000 /jam)</option>
                                    <option value="mobil" data-icon="fa-car-side">🚗 MOBIL (Rp 5.000 /jam)</option>
                                </select>
                            </div>
                            <div class="form-text mt-2">
                                <span class="badge bg-soft-danger text-danger border border-danger p-2">
                                    <i class="fas fa-print me-1"></i> Pastikan printer thermal sudah menyala.
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 shadow-sm fw-bold">
                            <i class="fas fa-plus-circle me-2"></i> DAFTARKAN KENDARAAN
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT UNTUK AUTO-OPEN CETAK (OPSIONAL) --}}
@if(session('last_id'))
<script>
    // Membuka tab cetak secara otomatis setelah klik simpan
    window.open("{{ route('parkir.cetak.masuk', session('last_id')) }}", "_blank");
</script>
@endif

@endsection