@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-body p-5 text-center">
                    <h2 class="fw-bold mb-4">GATE MASUK</h2>
                    <p class="text-muted">Kendaraan di dalam: <strong>{{ $kendaraanDiDalam }} Unit</strong></p>
                    <hr>
                    <form action="{{ route('parkir.masuk') }}" method="POST">
                        @csrf
                        <div class="mb-4 text-start">
                            <label class="form-label fw-bold">Pilih Jenis Kendaraan</label>
                            <select name="jenis" class="form-select form-select-lg border-primary">
                                <option value="motor">🏍️ Motor (Rp 2.000/jam)</option>
                                <option value="mobil">🚗 Mobil (Rp 5.000/jam)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
                            AMBIL TIKET
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection