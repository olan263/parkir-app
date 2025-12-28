@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary"><i class="fas fa-user-shield me-2"></i>ADMIN DASHBOARD</h2>
        <div class="btn-group">
            <a href="{{ route('parkir.export.pdf') }}" class="btn btn-danger"><i class="fas fa-file-pdf me-1"></i> Cetak Laporan</a>
            <a href="{{ route('parkir.export.excel') }}" class="btn btn-success"><i class="fas fa-file-excel me-1"></i> Excel</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white p-3">
                <small>Pendapatan Bulan Ini</small>
                <h3 class="fw-bold mb-0">Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white p-3">
                <small>Kendaraan Masuk Hari Ini</small>
                <h3 class="fw-bold mb-0">{{ $totalKendaraanHariIni }} Kendaraan</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info text-white p-3">
                <small>Kapasitas Terpakai (Aktif)</small>
                <h3 class="fw-bold mb-0">{{ $statsJenis['mobil'] + $statsJenis['motor'] }} Unit</h3>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold py-3">
            <i class="fas fa-list me-2"></i>SEMUA RIWAYAT TRANSAKSI
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tiket</th>
                            <th>Plat Nomor</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Total Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($semuaTransaksi as $t)
                        <tr>
                            <td><code>{{ $t->kode_tiket }}</code></td>
                            <td class="text-uppercase fw-bold">{{ $t->plat_nomor ?? '-' }}</td>
                            <td>{{ strtoupper($t->jenis) }}</td>
                            <td>
                                <span class="badge {{ $t->status == 'aktif' ? 'bg-warning' : 'bg-success' }}">
                                    {{ strtoupper($t->status) }}
                                </span>
                            </td>
                            <td class="fw-bold">Rp {{ number_format($t->total_bayar, 0, ',', '.') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('parkir.edit', $t->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('parkir.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus data transaksi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $semuaTransaksi->links() }}
            </div>
        </div>
    </div>
</div>
@endsection