@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
        <p class="text-gray-500 text-sm uppercase font-bold">Total Parkir</p>
        <h3 class="text-3xl font-bold">{{ $data->total() }}</h3>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
        <p class="text-gray-500 text-sm uppercase font-bold">Pendapatan</p>
        <h3 class="text-3xl font-bold">Rp {{ number_format($data->sum('total_biaya'), 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
        <p class="text-gray-500 text-sm uppercase font-bold">Status Aktif</p>
        <h3 class="text-3xl font-bold">{{ $data->where('status', 'masuk')->count() }}</h3>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800">Daftar Kendaraan</h2>
        <a href="/parkir/export/pdf" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition">
            <i class="fas fa-file-pdf mr-1"></i> Cetak Laporan PDF
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Tiket</th>
                    <th class="px-6 py-4">Plat Nomor</th>
                    <th class="px-6 py-4">Kendaraan</th>
                    <th class="px-6 py-4">Waktu Masuk</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($data as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-6 py-4 font-mono font-bold text-blue-600">{{ $item->kode_tiket }}</td>
                    <td class="px-6 py-4 font-semibold uppercase">{{ $item->plat_nomor }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $item->jenis_kendaraan }}</td>
                    <td class="px-6 py-4 text-sm">{{ $item->waktu_masuk }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item->status == 'masuk' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ strtoupper($item->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="p-6 bg-gray-50 border-t custom-pagination">
        {{ $data->links() }}
    </div>
</div>

<style>
    /* Paksa ikon panah pagination menjadi kecil */
    .custom-pagination svg {
        width: 20px !important;
        height: 20px !important;
        display: inline-block;
        vertical-align: middle;
    }

    /* Merapikan layout info pagination */
    .custom-pagination nav > div:first-child {
        display: none; /* Sembunyikan teks "Showing X to Y" di mobile jika terlalu penuh */
    }

    @media (min-width: 768px) {
        .custom-pagination nav > div:first-child {
            display: flex;
        }
    }

    /* Memberi jarak antar nomor halaman */
    .custom-pagination span[aria-current="page"] span,
    .custom-pagination a {
        padding: 8px 14px !important;
        border-radius: 6px;
    }
</style>
@endsection