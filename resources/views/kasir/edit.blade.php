<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi - {{ $parking->kode_tiket }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; border-radius: 15px; }
        .card-header { border-radius: 15px 15px 0 0 !important; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('parkir.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Edit Transaksi</li>
                </ol>
            </nav>

            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Edit Data Parkir</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('parkir.update', $parking->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">KODE TIKET</label>
                            <input type="text" class="form-control bg-light fw-bold text-primary" value="{{ $parking->kode_tiket }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="plat_nomor" class="form-label fw-bold">Plat Nomor</label>
                            <input type="text" name="plat_nomor" id="plat_nomor" 
                                   class="form-control form-control-lg text-uppercase" 
                                   value="{{ $parking->plat_nomor }}" required>
                            <div class="form-text">Contoh: B 1234 ABC</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jenis" class="form-label fw-bold">Jenis</label>
                                <select name="jenis" id="jenis" class="form-select">
                                    <option value="motor" {{ $parking->jenis == 'motor' ? 'selected' : '' }}>Motor</option>
                                    <option value="mobil" {{ $parking->jenis == 'mobil' ? 'selected' : '' }}>Mobil</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="aktif" {{ $parking->status == 'aktif' ? 'selected' : '' }}>Aktif (Parkir)</option>
                                    <option value="selesai" {{ $parking->status == 'selesai' ? 'selected' : '' }}>Selesai (Keluar)</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('parkir.index') }}" class="btn btn-light px-4">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-4 text-center text-muted">
                <small>Terdaftar sejak: {{ $parking->created_at->format('d M Y H:i') }}</small>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>