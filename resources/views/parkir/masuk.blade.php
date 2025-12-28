@extends('layouts.app')

@section('content')
<div class="container py-5">
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
                <div class="alert alert-success shadow-lg border-start border-4 border-success mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle fa-2xl me-3 text-success"></i>
                        <div>
                            <h5 class="alert-heading mb-1 fw-bold">Transaksi Berhasil!</h5>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                    </div>
                    <hr>
                    {{-- INI TOMBOL CETAKNYA --}}
                    <a href="{{ route('parkir.cetak.masuk', session('last_id')) }}" 
                       target="_blank" 
                       class="btn btn-warning btn-lg w-100 fw-bold shadow">
                        <i class="fas fa-print me-2"></i> KLIK UNTUK CETAK TIKET (PDF)
                    </a>
                </div>
            @endif

            {{-- STATISTIK RINGKAS --}}
            <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div class="small text-uppercase fw-bold opacity-75">Kendaraan Di Dalam:</div>
                    <h3 class="fw-bold mb-0">{{ $kendaraanDiDalam }} <small class="fs-6">Unit</small></h3>
                </div>
            </div>

            {{-- FORM INPUT --}}
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-0">
                    <span class="fw-bold text-secondary"><i class="fas fa-list me-2"></i> FORM TIKET BARU</span>
                </div>
                <div class="card-body p-4 pt-2">
                    <form action="{{ route('parkir.masuk') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Jenis Kendaraan</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-primary text-primary">
                                    <i class="fas fa-car-side"></i>
                                </span>
                                <select name="jenis" class="form-select border-primary fw-bold" required>
                                    <option value="motor">🏍️ MOTOR (Rp 2.000 /jam)</option>
                                    <option value="mobil">🚗 MOBIL (Rp 5.000 /jam)</option>
                                </select>
                            </div>
                            <div class="form-text text-danger">* Pastikan printer thermal sudah menyala.</div>
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
@endsection