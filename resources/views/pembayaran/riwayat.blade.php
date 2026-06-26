@extends('layouts.agen')

@section('content')

<div class="flex justify-between items-center mb-8">

    <div>

        <h1 class="text-3xl font-bold text-gray-800">
            Riwayat Pembayaran
        </h1>

        <p class="text-gray-500 mt-2">
            Pantau seluruh pembayaran hutang yang pernah Anda lakukan.
        </p>

    </div>

</div>

@php
$totalDibayar = $pembayaran
    ->where('status', 'disetujui')
    ->sum('jumlah_bayar');

$totalPengajuan = $pembayaran->count();

$totalDisetujui = $pembayaran
    ->where('status', 'disetujui')
    ->count();

$totalDitolak = $pembayaran
    ->where('status', 'ditolak')
    ->count();
@endphp

<!-- CARD RINGKASAN -->
<div class="grid md:grid-cols-4 gap-6 mb-8">

    <!-- TOTAL UANG DIBAYARKAN -->
    <div class="bg-gradient-to-r from-[#5628C7] to-purple-600 rounded-3xl p-6 shadow-sm text-white">

        <div class="flex items-center gap-3 mb-4">

            <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center text-xl">
                💰
            </div>

            <p class="text-sm font-bold uppercase tracking-wide text-white/80">
                Total Dibayarkan
            </p>

        </div>

        <h2 class="text-4xl font-bold">
            Rp{{ number_format($totalDibayar,0,',','.') }}
        </h2>

        <div class="border-t border-white/20 mt-4 pt-4">
            <p class="text-sm text-white/80">
                Total pembayaran yang telah disetujui
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
            {{ $totalPengajuan }}
        </h2>

        <p class="text-sm text-gray-500 mt-4">
            Total pengajuan pembayaran yang pernah dibuat
        </p>

    </div>

    <!-- DISETUJUI -->
    <div class="bg-green-50 rounded-3xl p-6 border border-green-100 shadow-sm">

        <div class="flex items-center gap-3 mb-4">

            <div class="w-12 h-12 rounded-2xl bg-green-100 flex items-center justify-center text-xl">
                ✅
            </div>

            <p class="text-sm font-bold uppercase tracking-wide text-gray-500">
                Disetujui
            </p>

        </div>

        <h2 class="text-4xl font-bold text-green-600">
            {{ $totalDisetujui }}
        </h2>

        <p class="text-sm text-gray-500 mt-4">
            Total pembayaran yang berhasil diverifikasi
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
            {{ $totalDitolak }}
        </h2>

        <p class="text-sm font-bold text-gray-500 mt-4">
            Total pengajuan pembayaran yang ditolak
        </p>

    </div>

</div>
<!-- TABEL -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

  <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-6">

    <h2 class="text-xl font-bold text-gray-800">
        Data Pembayaran
    </h2>

 <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-6">

<div class="flex flex-wrap gap-3">

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
        <option value="ditolak">Ditolak</option>

    </select>

</div>
</div>

</div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left py-3">Tanggal</th>
                    <th class="text-left py-3">Jumlah</th>
                    <th class="text-left py-3">Metode</th>
                    <th class="text-left py-3">Status</th>
                    <th class="text-left py-3">Bukti</th>
                    <th class="text-left py-3">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($pembayaran as $p)

              <tr
    class="border-b status-row"
    data-status="{{ $p->status }}"
    data-tanggal="{{ \Carbon\Carbon::parse($p->tanggal_pembayaran)->format('Y-m-d') }}">

                    <td class="py-4">
                        {{ \Carbon\Carbon::parse($p->tanggal_pembayaran)->format('d M Y') }}
                    </td>

                    <td>
                        Rp{{ number_format($p->jumlah_bayar,0,',','.') }}
                    </td>

                    <td>
                        {{ $p->bank_pengirim }}
                    </td>

                    <td>

                        @if($p->status == 'pending')

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                Pending
                            </span>

                        @elseif($p->status == 'disetujui')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                Disetujui
                            </span>

                        @else

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                Ditolak
                            </span>

                        @endif

                    </td>

                    <td>

                        <a
                            href="{{ asset('uploads/'.$p->bukti_pembayaran) }}"
                            target="_blank"
                            class="text-[#5628C7] font-semibold">

                            Lihat Bukti

                        </a>

                    </td>

                    <td>

    @if($p->status == 'ditolak')

        <button
            onclick="openModal({{ $p->id }})"
            class="px-3 py-1 bg-red-100 text-red-600 rounded-lg text-sm font-semibold hover:bg-red-200">

            Lihat Alasan

        </button>

    @else

        <span class="text-gray-400">
            -
        </span>

    @endif

</td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="text-center py-8 text-gray-500">
                        Belum ada data pembayaran
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>
@foreach($pembayaran as $p)

@if($p->status == 'ditolak')

<div
    id="modal{{ $p->id }}"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">

    <div class="bg-white rounded-3xl p-6 w-full max-w-lg">

        <h2 class="text-xl font-bold text-red-600 mb-4">
            Alasan Penolakan
        </h2>

        <p class="text-gray-700">
            {{ $p->alasan_penolakan }}
        </p>

        <div class="mt-6 text-right">

            <button
                onclick="closeModal({{ $p->id }})"
                class="px-4 py-2 bg-gray-100 rounded-xl hover:bg-gray-200">

                Tutup

            </button>

        </div>

    </div>

</div>

@endif

@endforeach
</div>

<script>

const filterStatus =
document.getElementById('filterStatus');

const filterTanggal =
document.getElementById('filterTanggal');

function filterData() {

    let status = filterStatus.value;
    let tanggal = filterTanggal.value;

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

function openModal(id)
{
    document
        .getElementById('modal' + id)
        .classList
        .remove('hidden');
}

function closeModal(id)
{
    document
        .getElementById('modal' + id)
        .classList
        .add('hidden');
}

</script>

@endsection