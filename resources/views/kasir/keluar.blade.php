@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">KASIR KELUAR</h2>
        </div>
        <div class="col-md-6 text-end">
            <p class="mb-0 text-muted">Pendapatan Hari Ini</p>
            <h3 class="text-success fw-bold">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white">Proses Pembayaran</div>
                <div class="card-body">
                    <form action="{{ route('parkir.keluar') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="kode_tiket" id="kode_tiket" class="form-control" placeholder="Kode Tiket (TKT-XXXX)" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="plat_nomor" class="form-control text-uppercase" placeholder="Nomor Plat" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="bayar" id="bayar" class="form-control fw-bold text-danger" placeholder="Nominal Bayar" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">BAYAR SEKARANG</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">Kendaraan Masih Terparkir</div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Masuk</th>
                                <th>Tiket</th>
                                <th>Jenis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kendaraanAktif as $k)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($k->waktu_masuk)->format('H:i') }}</td>
                                <td><code>{{ $k->kode_tiket }}</code></td>
                                <td>{{ strtoupper($k->jenis) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-danger" onclick="document.getElementById('kode_tiket').value='{{ $k->kode_tiket }}'; document.getElementById('bayar').focus();">
                                        Pilih
                                    </button>
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
@endsection