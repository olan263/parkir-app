@extends('layouts.app') {{-- Atau samakan dengan struktur header Anda --}}
@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-primary"><i class="fas fa-sign-in-alt"></i> GATE MASUK</h1>
        <p class="text-muted">Klik tombol di bawah untuk cetak tiket</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card stat-card border-info shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Slot Terpakai Saat Ini:</h6>
                    <h3 class="fw-bold text-info mb-0">{{ $kendaraanDiDalam }} Unit</h3>
                </div>
            </div>

            <div class="card shadow-lg border-top border-primary border-4">
                <div class="card-body p-5">
                    <form action="{{ route('parkir.masuk') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-5">Jenis Kendaraan</label>
                            <select name="jenis" class="form-select form-select-lg border-primary">
                                <option value="motor">🏍️ MOTOR (Rp 2.000/jam)</option>
                                <option value="mobil">🚗 MOBIL (Rp 5.000/jam)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 shadow">
                            <i class="fas fa-print fa-lg"></i> CETAK TIKET MASUK
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection