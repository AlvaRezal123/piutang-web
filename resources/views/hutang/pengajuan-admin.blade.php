@extends('layouts.admin')

@section('content')

<!-- HEADER -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Pengajuan Hutang</h1>
        <p class="text-gray-500 mt-2">Daftar semua pengajuan hutang dari agen.</p>
    </div>
    <a href="/dashboard-admin"
        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Kembali ke Dashboard
    </a>
</div>

<!-- TABEL -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-bold text-gray-800">Data Pengajuan</h2>
        <div class="flex items-center gap-2 border border-gray-300 rounded-xl px-4 py-2 bg-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari agen..." class="outline-none text-sm w-40">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-3 px-2 text-xs uppercase tracking-wide text-gray-400 font-semibold">Agen</th>
                    <th class="text-left py-3 px-2 text-xs uppercase tracking-wide text-gray-400 font-semibold">Jumlah</th>
                    <th class="text-left py-3 px-2 text-xs uppercase tracking-wide text-gray-400 font-semibold">Metode</th>
                    <th class="text-left py-3 px-2 text-xs uppercase tracking-wide text-gray-400 font-semibold">Tanggal Pengajuan</th>
                    <th class="text-left py-3 px-2 text-xs uppercase tracking-wide text-gray-400 font-semibold">Status</th>
<th class="text-left py-3 px-2 text-xs uppercase tracking-wide text-gray-400 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hutang as $h)
                <tr class="border-b hover:bg-gray-50 transition hutang-row" data-agen="{{ strtolower($h->agen->username) }}">

                    <td class="py-4 px-2">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#5628C7] flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($h->agen->username, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $h->agen->username }}</p>
                                <p class="text-xs text-gray-400">{{ $h->agen->nama_usaha ?? '-' }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="py-4 px-2">
                        <p class="text-sm font-semibold text-gray-800">Rp{{ number_format($h->jumlah_hutang, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400">Sisa: Rp{{ number_format($h->sisa_hutang, 0, ',', '.') }}</p>
                    </td>

                    <td class="py-4 px-2">
                        @if($h->metode == 'cash')
                            <span class="inline-flex items-center bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                Bayar Penuh
                            </span>
                        @else
                            <span class="inline-flex items-center bg-purple-50 text-purple-700 border border-purple-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                Cicilan {{ $h->lama_tempo }}
                            </span>
                        @endif
                    </td>

                    <td class="py-4 px-2">
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->diffForHumans() }}</p>
                    </td>

                    <td class="py-4 px-2">
                        @if($h->status == 'pending')
                            <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 border border-yellow-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 inline-block"></span> Pending
                            </span>
                        @elseif($h->status == 'disetujui')
                            <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span> Disetujui
                            </span>
                        @elseif($h->status == 'berjalan')
                            <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 border border-green-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Berjalan
                            </span>
                        @elseif($h->status == 'lunas')
                            <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 border border-gray-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span> Lunas
                            </span>
                        @elseif($h->status == 'terlambat')
                            <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 border border-red-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span> Terlambat
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 border border-red-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span> Ditolak
                            </span>
                        @endif
        
                    </td>

                    <td class="py-4 px-2">
                        <a href="/admin/hutang/detail/{{ $h->id }}"
                            class="inline-flex items-center gap-1.5 bg-purple-50 hover:bg-purple-100 text-[#5628C7] px-3 py-1.5 rounded-xl text-xs font-semibold transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Detail
                        </a>
                    </td>

                </tr>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-gray-400 text-sm">Belum ada pengajuan hutang</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    const keyword = this.value.toLowerCase();
    document.querySelectorAll('.hutang-row').forEach(function(row) {
        row.style.display = row.dataset.agen.includes(keyword) ? '' : 'none';
    });
});
</script>

@endsection