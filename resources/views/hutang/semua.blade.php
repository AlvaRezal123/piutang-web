@extends('layouts.owner')

@section('content')

<!-- HEADER -->
<div class="flex items-center justify-between mb-8">

    <div>
        <h1 class="text-3xl font-bold text-gray-800">Monitoring Hutang</h1>
        <p class="text-gray-400 text-sm mt-1">Pantau seluruh pengajuan dan status hutang agen Partner Pulsa.</p>
    </div>

</div>

<!-- STATISTIK -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    <!-- Card 1: Total Piutang Dicairkan -->
    <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center mb-3">
            <i class="ti ti-cash text-[#5628C7] text-base"></i>
        </div>
        <p class="text-xs text-gray-400 mb-1">Total Piutang</p>
        <p class="text-lg font-bold text-[#5628C7] leading-tight">
            Rp{{ number_format($totalPiutang, 0, ',', '.') }}
        </p>
        <p class="text-xs text-gray-400 mt-2">Sudah dicairkan ke agen</p>
    </div>

    <!-- Card 2: Sudah Kembali -->
    <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center mb-3">
            <i class="ti ti-circle-check text-green-600 text-base"></i>
        </div>
        <p class="text-xs text-gray-400 mb-1">Sudah Kembali</p>
        <p class="text-lg font-bold text-green-600 leading-tight">
            Rp{{ number_format($sudahKembali, 0, ',', '.') }}
        </p>
        <p class="text-xs text-green-500 mt-2 flex items-center gap-1">
            <i class="ti ti-trending-up text-xs"></i> Lunas dibayar agen
        </p>
    </div>

    <!-- Card 3: Belum Kembali -->
    <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mb-3">
            <i class="ti ti-clock-dollar text-blue-600 text-base"></i>
        </div>
        <p class="text-xs text-gray-400 mb-1">Belum Kembali</p>
        <p class="text-lg font-bold text-blue-600 leading-tight">
            Rp{{ number_format($belumKembali, 0, ',', '.') }}
        </p>
        <p class="text-xs text-blue-400 mt-2 flex items-center gap-1">
            <i class="ti ti-loader text-xs"></i> Masih berjalan / terlambat
        </p>
    </div>

    <!-- Card 4: Terlambat -->
    <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center mb-3">
            <i class="ti ti-alert-triangle text-yellow-600 text-base"></i>
        </div>
        <p class="text-xs text-gray-400 mb-1">Terlambat</p>
        <p class="text-3xl font-bold text-yellow-600">{{ $jumlahTerlambat }}</p>
        <p class="text-xs text-yellow-500 mt-2 flex items-center gap-1">
            <i class="ti ti-alert-circle text-xs"></i> Perlu perhatian
        </p>
    </div>

</div>
<!-- TABEL -->
<div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">

    <!-- Toolbar -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">

        <h2 class="text-sm font-semibold text-gray-800">Data Monitoring Hutang</h2>

        <div class="flex items-center gap-3">

            <span class="text-xs text-gray-400">{{ $hutang->count() }} data</span>

            <select id="filterStatus" class="text-sm border border-gray-200 rounded-xl px-3 py-2 text-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-300">
                <option value="all">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="disetujui">Disetujui</option>
                <option value="berjalan">Berjalan</option>
                <option value="terlambat">Terlambat</option>
                <option value="lunas">Lunas</option>
                <option value="ditolak">Ditolak</option>
            </select>

        </div>

    </div>

    <!-- Table -->
    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide ">Agen</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Jumlah</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Metode</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Pengajuan</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Jatuh Tempo</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($hutang as $h)

                @php
                    $jatuhTempo = \Carbon\Carbon::parse($h->tanggal_jatuh_tempo);
                    $isLewat = $jatuhTempo->isPast() && !in_array($h->status, ['lunas','ditolak']);
                    $sisaHari = now()->diffInDays($jatuhTempo, false);
                @endphp

                <tr class="status-row border-b border-gray-50 hover:bg-gray-50 transition-colors" data-status="{{ $h->status }}">

                    <!-- Agen -->
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 text-[#5628C7] text-xs font-semibold flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($h->agen->username, 0, 2)) }}
                            </div>
                            <span class="font-medium text-gray-800">{{ $h->agen->username }}</span>
                        </div>
                    </td>

                    <!-- Jumlah -->
                    <td class="px-6 py-4 font-semibold text-gray-800">
                        Rp{{ number_format($h->jumlah_hutang, 0, ',', '.') }}
                    </td>

                    <!-- Metode -->
                    <td class="px-6 py-4 text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <i class="ti ti-{{ $h->metode == 'cicil' ? 'calendar-repeat' : 'cash' }} text-gray-300 text-sm"></i>
                            {{ ucfirst($h->metode) }}
                        </span>
                    </td>

                    <!-- Tanggal Pengajuan -->
                    <td class="px-6 py-4 text-gray-500">
                        {{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('d M Y') }}
                    </td>

                    <!-- Jatuh Tempo -->
                    <td class="px-6 py-4">
                        <div>
                            <p class="{{ $isLewat ? 'text-red-500 font-semibold' : 'text-gray-500' }}">
                                {{ $jatuhTempo->format('d M Y') }}
                            </p>
                            @if(!in_array($h->status, ['lunas','ditolak']))
                                @if($isLewat)
                                    <p class="text-xs text-red-400 mt-0.5">Lewat {{ abs($sisaHari) }} hari</p>
                                @elseif($sisaHari <= 7)
                                    <p class="text-xs text-orange-400 mt-0.5">{{ $sisaHari }} hari lagi</p>
                                @else
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $sisaHari }} hari lagi</p>
                                @endif
                            @endif
                        </div>
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4">
                        @if($h->status == 'pending')
                            <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-clock text-xs"></i> Pending
                            </span>
                        @elseif($h->status == 'disetujui')
                            <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-check text-xs"></i> Disetujui
                            </span>
                        @elseif($h->status == 'berjalan')
                            <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-loader text-xs"></i> Berjalan
                            </span>
                        @elseif($h->status == 'terlambat')
                            <span class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-alert-triangle text-xs"></i> Terlambat
                            </span>
                        @elseif($h->status == 'lunas')
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-circle-check text-xs"></i> Lunas
                            </span>
                        @elseif($h->status == 'ditolak')
                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-x text-xs"></i> Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                                {{ ucfirst($h->status) }}
                            </span>
                        @endif
                    </td>

                    <!-- Aksi -->
                    <td class="px-6 py-4">
                      <a href="/owner/hutang/detail/{{ $h->id }}"

                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-semibold transition-colors">
                            <i class="ti ti-eye text-xs"></i> Detail
                        </a>
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="7" class="text-center py-16 text-gray-400">
                        <i class="ti ti-inbox text-4xl block mb-3 text-gray-200"></i>
                        Belum ada data hutang
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<script>
document.getElementById('filterStatus').addEventListener('change', function () {
    const status = this.value;
    document.querySelectorAll('.status-row').forEach(function (row) {
        row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
    });
});
</script>

@endsection