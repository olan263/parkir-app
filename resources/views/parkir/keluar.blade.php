@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-danger"><i class="fas fa-cash-register"></i> KASIR KELUAR</h2>
        <div class="text-end">
            <small class="text-muted d-block">Pendapatan Hari Ini</small>
            <h4 class="text-success fw-bold">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h4>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-top border-danger border-4 sticky-top" style="top: 20px;">
                <div class="card-header bg-white"><h5 class="mb-0">Proses Keluar</h5></div>
                <div class="card-body">
                    <form action="{{ route('parkir.keluar') }}" method="POST">
                        @csrf
                        <input type="text" name="kode_tiket" id="inputKode" placeholder="Kode Tiket" class="form-control mb-2" required>
                        <input type="text" name="plat_nomor" id="inputPlat" placeholder="Nomor Plat" class="form-control mb-2 text-uppercase" required>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="bayar" id="inputBayar" class="form-control fw-bold text-danger" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 btn-lg">PROSES BAYAR</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white small fw-bold">KENDARAAN DI DALAM</div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 admin-table">
                        <thead class="table-light">
                            <tr>
                                <th>Masuk</th>
                                <th>Tiket</th>
                                <th>Jenis</th>
                                <th>Estimasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kendaraanAktif as $item)
                            <tr>
                                <td>{{ $item->waktu_masuk->format('H:i') }}</td>
                                <td><code>{{ $item->kode_tiket }}</code></td>
                                <td>{{ strtoupper($item->jenis) }}</td>
                                <td>
                                    @php
                                        $totalJam = ceil($item->waktu_masuk->diffInMinutes(now()) / 60);
                                        $tarif = ($item->jenis == 'mobil') ? 5000 : 2000;
                                    @endphp
                                    <strong>Rp {{ number_format($totalJam * $tarif, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-danger" onclick="pilihTiket('{{ $item->kode_tiket }}', {{ $totalJam * $tarif }})">
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

<script>
function pilihTiket(kode, harga) {
    document.getElementById('inputKode').value = kode;
    document.getElementById('inputBayar').value = harga;
    document.getElementById('inputPlat').focus();
}
</script>
@endsection