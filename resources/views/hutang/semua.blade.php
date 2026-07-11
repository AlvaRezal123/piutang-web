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
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm mb-8">

    <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

        <h2 class="text-xl font-bold text-gray-800">
            Data Monitoring Hutang
        </h2>

        <div class="flex flex-wrap gap-3">

            <!-- Bulan -->
            <select id="filterBulan" class="border border-gray-300 rounded-xl px-4 py-2">

                <option value="">Semua Bulan</option>
                <option value="01">Januari</option>
                <option value="02">Februari</option>
                <option value="03">Maret</option>
                <option value="04">April</option>
                <option value="05">Mei</option>
                <option value="06">Juni</option>
                <option value="07">Juli</option>
                <option value="08">Agustus</option>
                <option value="09">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>

            </select>

            <!-- Tahun -->
            <select id="filterTahun" class="border border-gray-300 rounded-xl px-4 py-2">

                <option value="">Semua Tahun</option>

                @for($i = date('Y'); $i >= 2024; $i--)

                    <option value="{{ $i }}">{{ $i }}</option>

                @endfor

            </select>

            <!-- Status -->
            <select id="filterStatus" class="border border-gray-300 rounded-xl px-4 py-2">

                <option value="all">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="disetujui">Disetujui</option>
                <option value="berjalan">Berjalan</option>
                <option value="terlambat">Terlambat</option>
                <option value="lunas">Lunas</option>
                <option value="ditolak">Ditolak</option>

            </select>

            <!-- Cari -->
            <button
                type="button"
                id="btnCariMonitoring"
                class="bg-purple-600 text-white px-4 py-2 rounded-xl">

                Cari

            </button>

            <!-- Reset -->
            <button
                type="button"
                id="resetFilterMonitoring"
                class="border border-gray-300 bg-red-600 text-white px-4 py-2 rounded-xl">

                Reset

            </button>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full table-auto text-sm">

            <thead>
                <tr class="border-b">
                    <th class="text-left py-3">Agen</th>
                    <th class="text-left py-3">Jumlah</th>
                    <th class="text-left py-3">Metode</th>
                    <th class="text-left py-3">Pengajuan</th>
                    <th class="text-left py-3">Jatuh Tempo</th>
                    <th class="text-left py-3">Status</th>
                    <th class="text-left py-3">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($hutang as $h)

                @php
                    $jatuhTempo = \Carbon\Carbon::parse($h->tanggal_jatuh_tempo);
                    $isLewat = $jatuhTempo->isPast() && !in_array($h->status, ['lunas','ditolak']);
                    $sisaHari = now()->diffInDays($jatuhTempo, false);
                @endphp

                <tr
                    class="border-b status-row"
                    data-status="{{ $h->status }}"
                    data-tahun="{{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('Y') }}"
                    data-bulan="{{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('m') }}">

                    <!-- Agen -->
                    <td class="py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 text-[#5628C7] text-xs font-semibold flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($h->agen->username, 0, 2)) }}
                            </div>
                            <span class="font-medium text-gray-800">{{ $h->agen->username }}</span>
                        </div>
                    </td>

                    <!-- Jumlah -->
                    <td class="font-semibold text-gray-800">
                        Rp{{ number_format($h->jumlah_hutang, 0, ',', '.') }}
                    </td>

                    <!-- Metode -->
                    <td class="text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <i class="ti ti-{{ $h->metode == 'cicil' ? 'calendar-repeat' : 'cash' }} text-gray-300 text-sm"></i>
                            @if($h->metode == 'cash')
                                Pembayaran Penuh
                            @else
                                Cicilan {{ ucfirst($h->lama_tempo) }}
                            @endif
                        </span>
                    </td>

                    <!-- Tanggal Pengajuan -->
                    <td class="text-gray-500">
                        {{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('d M Y') }}
                    </td>

                    <!-- Jatuh Tempo -->
                    <td>
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
                    </td>

                    <!-- Status -->
                    <td>
                        @if($h->status == 'pending')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                Pending
                            </span>
                        @elseif($h->status == 'disetujui')
                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">
                                Disetujui
                            </span>
                        @elseif($h->status == 'berjalan')
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                Berjalan
                            </span>
                        @elseif($h->status == 'terlambat')
                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm">
                                Terlambat
                            </span>
                        @elseif($h->status == 'lunas')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                Lunas
                            </span>
                        @elseif($h->status == 'ditolak')
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                Ditolak
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm">
                                {{ ucfirst($h->status) }}
                            </span>
                        @endif
                    </td>

                    <!-- Aksi -->
                    <td>
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

const filterBulan = document.getElementById('filterBulan');
const filterTahun = document.getElementById('filterTahun');
const filterStatus = document.getElementById('filterStatus');
const btnCariMonitoring = document.getElementById('btnCariMonitoring');
const resetFilterMonitoring = document.getElementById('resetFilterMonitoring');

function filterMonitoring() {

    let bulan = filterBulan.value;
    let tahun = filterTahun.value;
    let status = filterStatus.value;

    document.querySelectorAll('.status-row').forEach(function (row) {

        let rowBulan = row.dataset.bulan;
        let rowTahun = row.dataset.tahun;
        let rowStatus = row.dataset.status;

        let cocokBulan = bulan === '' || rowBulan === bulan;
        let cocokTahun = tahun === '' || rowTahun === tahun;
        let cocokStatus = status === 'all' || rowStatus === status;

        row.style.display =
            (cocokBulan && cocokTahun && cocokStatus)
                ? ''
                : 'none';

    });

}

filterBulan.addEventListener('change', filterMonitoring);
filterTahun.addEventListener('change', filterMonitoring);
filterStatus.addEventListener('change', filterMonitoring);
btnCariMonitoring.addEventListener('click', filterMonitoring);

resetFilterMonitoring.addEventListener('click', function () {

    filterBulan.value = '';
    filterTahun.value = '';
    filterStatus.value = 'all';

    filterMonitoring();

});

</script>

@endsection