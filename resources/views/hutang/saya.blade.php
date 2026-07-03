@extends('layouts.agen')

@section('content')

<!-- HEADER -->
<div class="flex justify-between items-center mb-8">

    <div>

        <h1 class="text-3xl font-bold text-gray-800">
            Hutang Saya
        </h1>

        <p class="text-gray-500 mt-2">
            Kelola dan pantau seluruh riwayat hutang Anda.
        </p>

    </div> 

</div>

<!-- CARD RINGKASAN -->
<div class="grid md:grid-cols-4 gap-6 mb-8">

    <!-- TOTAL PINJAMAN -->
    <div class="bg-gradient-to-r from-[#5628C7] to-purple-600 rounded-3xl p-6 shadow-sm text-white">

        <div class="flex items-center gap-3 mb-4">

            <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center text-xl">
                💰
            </div>

            <p class="text-sm font-bold uppercase tracking-wide text-white/80">
                Total Pinjaman
            </p>

        </div>

        <h2 class="text-4xl font-bold">
            Rp{{ number_format(
                $hutang
                    ->whereIn('status', ['disetujui','berjalan','lunas','terlambat'])
                    ->sum('jumlah_hutang'),
                0,
                ',',
                '.'
            ) }}
        </h2>

        <div class="border-t border-white/20 mt-4 pt-4">
            <p class="text-sm text-white/80">
                Akumulasi seluruh pinjaman yang disetujui
            </p>
        </div>

    </div>

    <!-- TOTAL PENGAJUAN -->
   
    <div class="bg-purple-50 rounded-3xl p-6 border border-purple-100 shadow-sm">

        <div class="flex items-center gap-3 mb-4">

            <div class="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center text-xl">
                📄
            </div>

            <p class="text-sm font-bold uppercase tracking-wide text-gray-500">
                Total Pengajuan
            </p>

        </div>

        <h2 class="text-4xl font-bold text-[#5628C7]">
            {{ $hutang->count() }}
        </h2>

        <p class="text-sm text-gray-500 mt-4">
            Total pengajuan yang pernah dibuat
        </p>

    </div>

    <!-- LUNAS -->
    <div class="bg-green-50 rounded-3xl p-6 border border-green-100 shadow-sm">

        <div class="flex items-center gap-3 mb-4">

            <div class="w-12 h-12 rounded-2xl bg-green-100 flex items-center justify-center text-xl">
                ✅
            </div>

            <p class="text-sm font-bold uppercase tracking-wide text-gray-500">
                Lunas
            </p>

        </div>

        <h2 class="text-4xl font-bold text-green-600">
            {{ $hutang->where('status','lunas')->count() }}
        </h2>

        <p class="text-sm text-gray-500 mt-4">
            Total hutang yang telah selesai dibayar
        </p>

    </div>

    <!-- DITOLAK -->
    <div class="bg-red-50 rounded-3xl p-6 border border-red-100 shadow-sm">

        <div class="flex items-center gap-3 mb-4">

            <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center text-xl">
                ❌
            </div>

            <p class="text-sm font-bold uppercase tracking-wide text-gray-500">
                Ditolak
            </p>

        </div>

        <h2 class="text-4xl font-bold text-red-600">
            {{ $hutang->where('status','ditolak')->count() }}
        </h2>

        <p class="text-sm font-bold text-gray-500 mt-4">
            Total pengajuan yang ditolak
        </p>

    </div>

</div>
<!-- HUTANG AKTIF -->

@php
$hutangAktif = $hutang->whereIn('status', [
    'pending',
    'disetujui',
    'berjalan',
    'terlambat'
])->first();
@endphp

@if($hutangAktif)

    @if(in_array($hutangAktif->status,['pending','disetujui']))

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm mb-8">

        <div class="flex justify-between items-center mb-6">

            <div>

                <h2 class="text-xl font-bold text-gray-800">
                    Status Pengajuan
                </h2>

                <p class="text-sm text-gray-500 mt-1">

                    @if($hutangAktif->status=='pending')

                        Menunggu persetujuan owner

                    @else

                        Menunggu pencairan saldo oleh admin

                    @endif

                </p>

            </div>

            <span class="px-4 py-2 rounded-full text-sm font-semibold
                {{ $hutangAktif->status=='pending'
                    ? 'bg-yellow-100 text-yellow-700'
                    : 'bg-blue-100 text-blue-700' }}">

                {{ ucfirst($hutangAktif->status) }}

            </span>

        </div>

        <div class="grid md:grid-cols-3 gap-6">

            <div class="bg-purple-50 rounded-2xl p-5">

                <p class="text-sm text-gray-500">
                    Jumlah Pengajuan
                </p>

                <p class="text-2xl font-bold text-[#5628C7] mt-2">
                    Rp{{ number_format($hutangAktif->jumlah_hutang,0,',','.') }}
                </p>

            </div>

            <div class="bg-blue-50 rounded-2xl p-5">

                <p class="text-sm text-gray-500">
                    Tanggal Pengajuan
                </p>

                <p class="text-2xl font-bold text-blue-600 mt-2">
                    {{ \Carbon\Carbon::parse($hutangAktif->tanggal_pengajuan)->format('d M Y') }}
                </p>

            </div>

            <div class="bg-yellow-50 rounded-2xl p-5">

                <p class="text-sm text-gray-500">
                    Status Saat Ini
                </p>

                <p class="text-2xl font-bold text-yellow-600 mt-2">
                    {{ ucfirst($hutangAktif->status) }}
                </p>

            </div>

        </div>

    </div>

    @else

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm mb-8">

        <div class="flex justify-between items-center mb-6">

            <div>

                <h2 class="text-xl font-bold text-gray-800">
                    Hutang Aktif Saat Ini
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Informasi pinjaman yang sedang berjalan
                </p>

            </div>

            <span class="px-4 py-2 rounded-full text-sm font-semibold
                {{ $hutangAktif->status=='terlambat'
                    ? 'bg-red-100 text-red-700'
                    : 'bg-green-100 text-green-700' }}">

                {{ ucfirst($hutangAktif->status) }}

            </span>

        </div>

        <div class="grid md:grid-cols-3 gap-6">

            <div class="bg-purple-50 rounded-2xl p-5">

                <p class="text-sm text-gray-500">
                    Nominal Hutang
                </p>

                <p class="text-2xl font-bold text-[#5628C7] mt-2">
                    Rp{{ number_format($hutangAktif->jumlah_hutang,0,',','.') }}
                </p>

            </div>

            <div class="bg-orange-50 rounded-2xl p-5">

                <p class="text-sm text-gray-500">
                    Sisa Hutang
                </p>

                <p class="text-2xl font-bold text-orange-600 mt-2">
                    Rp{{ number_format($hutangAktif->sisa_hutang,0,',','.') }}
                </p>

            </div>

            <div class="bg-red-50 rounded-2xl p-5">

                <p class="text-sm text-gray-500">
                    Jatuh Tempo
                </p>

                <p class="text-2xl font-bold text-red-600">
                    {{ \Carbon\Carbon::parse($hutangAktif->tanggal_jatuh_tempo)->format('d M Y') }}
                </p>

            </div>

        </div>

        <div class="flex gap-3 mt-6 pt-6 border-t border-gray-100">

            <a href="/hutang/detail/{{ $hutangAktif->id }}"
               class="px-5 py-3 rounded-xl bg-purple-100 text-[#5628C7] font-semibold hover:bg-purple-200 transition">

                Detail Hutang

            </a>

            @if($hutangAktif->pembayaran_pending)

                <div class="px-5 py-3 rounded-xl bg-yellow-100 text-yellow-700 font-semibold">

                    ⏳ Pembayaran sedang diverifikasi Admin

                </div>

            @else

                <a href="/pembayaran/create/{{ $hutangAktif->id }}"
                   class="px-5 py-3 rounded-xl bg-[#5628C7] text-white font-semibold hover:bg-[#4b22b0] transition">

                    Bayar Sekarang

                </a>

            @endif

        </div>

    </div>

    @endif

@endif

<!-- RIWAYAT -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

   <div class="flex justify-between items-center mb-6">

    <h2 class="text-xl font-bold text-gray-800">
        Riwayat Pengajuan
    </h2>

    <div class="flex flex-col md:flex-row gap-3">

        <input
            type="date"
            id="filterTanggal"
            class="border border-gray-300 rounded-xl px-4 py-2">

        <select
            id="filterStatus"
            class="border border-gray-300 rounded-xl px-4 py-2">

            <option value="all">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="disetujui">Disetujui</option>
            <option value="berjalan">Berjalan</option>
            <option value="lunas">Lunas</option>
            <option value="ditolak">Ditolak</option>

        </select>

    </div>
</div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

            <tr class="border-b">

                <th class="text-left py-3">
                    Tanggal
                </th>

                <th class="text-left py-3">
                    Nominal
                </th>

                <th class="text-left py-3">
                    Sisa
                </th>

                <th class="text-left py-3">
                    Status
                </th>

                <th class="text-left py-3">
                    Jatuh Tempo
                </th>

                <th class="text-left py-3">
                    Aksi
                </th>

            </tr>

            </thead>

            <tbody>

            @foreach($hutang as $h)

           <tr
    class="border-b status-row"
    data-status="{{ $h->status }}"
    data-tanggal="{{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('Y-m-d') }}">

                <td class="py-4">
                    {{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('d M Y') }}
                </td>

                <td>
                    Rp{{ number_format($h->jumlah_hutang,0,',','.') }}
                </td>

               <td>

                @if(in_array($h->status,['berjalan','terlambat','lunas']))

                    Rp{{ number_format($h->sisa_hutang,0,',','.') }}

                @else

                    -

                @endif

                </td>
                <td>

                   <span class="
    px-3 py-1 rounded-full text-sm font-semibold

    @if($h->status == 'lunas')
        bg-green-100 text-green-700
    @elseif($h->status == 'terlambat')
        bg-red-100 text-red-700
    @elseif($h->status == 'berjalan')
        bg-blue-100 text-blue-700
    @elseif($h->status == 'pending')
        bg-yellow-100 text-yellow-700
    @elseif($h->status == 'disetujui')
        bg-indigo-100 text-indigo-700
    @elseif($h->status == 'ditolak')
        bg-red-100 text-red-700
    @endif
">
    {{ ucfirst($h->status) }}
</span>
                </td>

              <td>

@if(in_array($h->status,['berjalan','terlambat','lunas']))

    <span class="font-bold text-red-600 text-base">
        {{ \Carbon\Carbon::parse($h->tanggal_jatuh_tempo)->format('d M Y') }}
    </span>

@else

    <span class="text-gray-400">
        -
    </span>

@endif

</td>
                <td>

@if($h->status == 'ditolak')

    <button
        type="button"
        onclick="openTolakModal({{ $h->id }})"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-100 text-red-600 text-sm font-semibold hover:bg-red-200 transition">

        Lihat Alasan

    </button>

@else

<a
    href="/hutang/detail/{{ $h->id }}"
    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-[#5628C7] text-[#5628C7] text-sm font-semibold hover:bg-[#5628C7] hover:text-white transition">

    Detail →
</a>

@endif

                </td>

            </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

<!-- MODAL ALASAN PENOLAKAN, SATU MODAL PER PENGAJUAN YANG DITOLAK -->
@foreach($hutang as $h)

@if($h->status == 'ditolak')

<div
    id="tolakModal{{ $h->id }}"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl p-6 w-full max-w-lg">

        <h2 class="text-xl font-bold text-red-600 mb-4">
            Alasan Penolakan
        </h2>

        <div class="bg-gray-50 rounded-2xl p-4 mb-4">

            <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">
                Tanggal Pengajuan
            </p>

            <p class="font-semibold text-gray-800 mt-1">
                {{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('d M Y') }}
            </p>

        </div>

        <div class="bg-red-50 rounded-2xl p-4">

            <p class="text-xs text-red-600 uppercase font-bold tracking-wide">
                Alasan Penolakan
            </p>

            <p class="font-semibold text-gray-800 mt-1">
                {{ $h->alasan_penolakan ?? 'Tidak ada keterangan dari admin.' }}
            </p>

        </div>

        <div class="mt-6 text-right">

            <button
                type="button"
                onclick="closeTolakModal({{ $h->id }})"
                class="px-4 py-2 bg-gray-100 rounded-xl hover:bg-gray-200">

                Tutup

            </button>

        </div>

    </div>

</div>

@endif

@endforeach

<script>



const filterStatus =
    document.getElementById('filterStatus');

const filterTanggal =
    document.getElementById('filterTanggal');

function filterData() {

    let status =
        filterStatus.value;

    let tanggal =
        filterTanggal.value;

    document.querySelectorAll('.status-row')
    .forEach(function(row){

        let cocokStatus =
            status === 'all' ||
            row.dataset.status === status;

        let cocokTanggal =
            tanggal === '' ||
            row.dataset.tanggal === tanggal;

        row.style.display =
            cocokStatus && cocokTanggal
            ? ''
            : 'none';

    });
}

filterStatus.addEventListener(
    'change',
    filterData
);

filterTanggal.addEventListener(
    'change',
    filterData
);

function openTolakModal(id)
{
    document
        .getElementById('tolakModal' + id)
        .classList
        .remove('hidden');
}

function closeTolakModal(id)
{
    document
        .getElementById('tolakModal' + id)
        .classList
        .add('hidden');
}

</script>

@endsection