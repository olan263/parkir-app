@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
        <p class="text-gray-500 text-xs uppercase font-bold">Pendapatan Bulan Ini</p>
        <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
        <p class="text-gray-500 text-xs uppercase font-bold">Kendaraan Hari Ini</p>
        <h3 class="text-2xl font-bold text-gray-800">{{ $totalKendaraanHariIni }}</h3>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500">
        <p class="text-gray-500 text-xs uppercase font-bold">Mobil (Aktif)</p>
        <h3 class="text-2xl font-bold text-gray-800">{{ $statsJenis['mobil'] }}</h3>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-orange-500">
        <p class="text-gray-500 text-xs uppercase font-bold">Motor (Aktif)</p>
        <h3 class="text-2xl font-bold text-gray-800">{{ $statsJenis['motor'] }}</h3>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b flex flex-col md:flex-row justify-between items-center gap-4">
        <h2 class="text-lg font-bold text-gray-800">Management Transaksi Parkir</h2>
        <div class="flex gap-2">
            <a href="/parkir/export/pdf" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition font-semibold">
                <i class="fas fa-file-pdf mr-1"></i> Laporan PDF
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold">
                <tr>
                    <th class="px-6 py-4 border-b">No</th>
                    <th class="px-6 py-4 border-b">Kode Tiket</th>
                    <th class="px-6 py-4 border-b">Plat Nomor</th>
                    <th class="px-6 py-4 border-b">Jenis</th>
                    <th class="px-6 py-4 border-b">Masuk</th>
                    <th class="px-6 py-4 border-b">Bayar</th>
                    <th class="px-6 py-4 border-b text-center">Status</th>
                    <th class="px-6 py-4 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($semuaTransaksi as $item)
                <tr class="hover:bg-blue-50/50 transition">
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ ($semuaTransaksi->currentPage() - 1) * $semuaTransaksi->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-6 py-4 font-mono font-bold text-blue-600 text-sm">{{ $item->kode_tiket }}</td>
                    <td class="px-6 py-4 font-semibold uppercase text-gray-700">{{ $item->plat_nomor ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600 border">
                            {{ strtoupper($item->jenis) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($item->waktu_masuk)->format('d/m H:i') }}
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-700 text-sm">
                        Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black tracking-widest {{ $item->status == 'aktif' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                            {{ strtoupper($item->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <form action="{{ route('admin.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 p-2">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="p-6 bg-gray-50 border-t pagination-custom">
        {{ $semuaTransaksi->links() }}
    </div>
</div>

<style>
    /* Mengatasi ikon pagination yang membesar */
    .pagination-custom svg {
        width: 20px !important;
        height: 20px !important;
        display: inline;
    }
    .pagination-custom nav > div:first-child {
        display: none;
    }
    @media (min-width: 768px) {
        .pagination-custom nav > div:first-child { display: flex; }
    }
</style>
@endsection