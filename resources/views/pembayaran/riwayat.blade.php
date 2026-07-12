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

        <form method="GET" class="flex flex-wrap gap-3">

            <input
                type="date"
                name="tanggal"
                value="{{ request('tanggal') }}"
                class="border border-gray-300 rounded-xl px-4 py-2">

            <select
                name="status"
                class="border border-gray-300 rounded-xl px-4 py-2">

                <option value="all">Semua Status</option>

                <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="disetujui" {{ request('status')=='disetujui' ? 'selected' : '' }}>
                    Disetujui
                </option>

                <option value="ditolak" {{ request('status')=='ditolak' ? 'selected' : '' }}>
                    Ditolak
                </option>

            </select>

            <button
                type="submit"
                class="bg-[#5628C7] text-white px-4 py-2 rounded-xl">
                Cari
            </button>

            <a
                href="{{ url()->current() }}"
                class="bg-red-600 text-white px-4 py-2 rounded-xl">
                Reset
            </a>

        </form>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left py-3">ID Pembayaran</th>
                    <th class="text-left py-3">Tanggal</th>
                    <th class="text-left py-3">Jumlah</th>
                    <th class="text-left py-3">Metode</th>
                    <th class="text-left py-3">Bank Pengirim</th>
                    <th class="text-left py-3">Status</th>
                    <th class="text-left py-3">Bukti</th>
                    <th class="text-left py-3">Detail</th>
                    <th class="text-left py-3">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($pembayaran as $p)

                <tr
                    class="border-b status-row"
                    data-status="{{ $p->status }}"
                    data-tanggal="{{ \Carbon\Carbon::parse($p->tanggal_pembayaran)->format('Y-m-d') }}">

                    <td class="py-4 font-semibold text-gray-600">
                        #{{ $p->id }}
                    </td>

                    <td class="py-4">
                        {{ \Carbon\Carbon::parse($p->tanggal_pembayaran)->format('d M Y') }}
                    </td>

                    <td>
                        Rp{{ number_format($p->jumlah_bayar,0,',','.') }}
                    </td>

                    <td>

                        @if($p->hutang->metode == 'cash')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                Pembayaran Penuh
                            </span>

                        @else

                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                                Cicilan {{ $p->cicilan->cicilan_ke ?? '-' }} dari {{ $p->hutang->lama_tempo }}
                            </span>

                        @endif

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
                            title="Lihat Bukti Pembayaran"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-purple-100 text-[#5628C7] hover:bg-purple-200 transition">

                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>

                        </a>

                    </td>

                    <td>

                        <button
                            type="button"
                            onclick="openDetailModal({{ $p->id }})"
                            class="bg-purple-100 text-[#5628C7] px-3 py-1 rounded-lg text-sm font-semibold hover:bg-purple-200">

                            Detail

                        </button>

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

                    <td colspan="9" class="text-center py-8 text-gray-500">
                        Belum ada data pembayaran
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

        @if($pembayaran->hasPages())
        <div class="flex justify-between items-center mt-6">

            <div class="text-sm text-gray-500">
                Menampilkan
                {{ $pembayaran->firstItem() }}
                -
                {{ $pembayaran->lastItem() }}
                dari
                {{ $pembayaran->total() }}
                data
            </div>

            {{ $pembayaran->links() }}

        </div>
        @endif

    </div>

</div>

<!-- MODAL DETAIL CICILAN -->
<div
    id="detailModal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl p-6 w-full max-w-2xl max-h-[85vh] overflow-y-auto">

        <div class="flex items-start justify-between mb-2">

            <h2 class="text-xl font-bold text-gray-800">
                Detail Pembayaran
            </h2>

            <button
                type="button"
                onclick="closeDetailModal()"
                class="text-gray-400 hover:text-gray-600">

                <i class="ti ti-x text-xl"></i>

            </button>

        </div>

        <div class="flex flex-wrap gap-2 mb-5">

            <span class="bg-[#5628C7] text-white px-3 py-1 rounded-full text-xs font-bold">
                ID Pembayaran: <span id="detailIdPembayaran">-</span>
            </span>

            <span class="bg-purple-100 text-[#5628C7] px-3 py-1 rounded-full text-xs font-bold">
                ID Hutang: <span id="detailIdHutang">-</span>
            </span>

        </div>

        <div class="grid grid-cols-2 gap-3 mb-6">

            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Tanggal Pengajuan</p>
                <p class="font-semibold text-gray-800 mt-1" id="detailTanggalPengajuan">-</p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Metode</p>
                <p class="font-semibold text-gray-800 mt-1" id="detailMetode">-</p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Total Hutang</p>
                <p class="font-semibold text-gray-800 mt-1" id="detailTotalHutang">-</p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Sisa Hutang</p>
                <p class="font-semibold text-gray-800 mt-1" id="detailSisaHutang">-</p>
            </div>

        </div>

        <h3 class="font-bold text-gray-800 mb-3">
            Rincian Cicilan
        </h3>

        <div id="detailCicilanList" class="flex flex-col gap-3">
        </div>

    </div>

</div>

<!-- MODAL ALASAN PENOLAKAN, SATU MODAL PER PEMBAYARAN YANG DITOLAK -->
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

<script>

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

<script>

// Data seluruh pembayaran + cicilan dari hutang terkait, sudah
// disiapkan di PembayaranController@riwayat lalu di-dump satu kali
// di sini supaya modal detail bisa langsung dipopulate tanpa AJAX.
const pembayaranData = @json($pembayaranData);

function openDetailModal(id)
{
    const data = pembayaranData[id];

    if (!data) {
        return;
    }

    // ID Pembayaran diambil dari id yang diklik (parameter fungsi ini),
    // ID Hutang diambil dari data.idHutang yang disiapkan controller.
    document.getElementById('detailIdPembayaran').textContent = '#' + id;
    document.getElementById('detailIdHutang').textContent = data.idHutang ? '#' + data.idHutang : '-';

    document.getElementById('detailTanggalPengajuan').textContent = data.tanggalPengajuan;

    document.getElementById('detailMetode').textContent =
        data.metode === 'cash'
            ? 'Pembayaran Penuh'
            : 'Cicilan ' + (data.lamaTempo ?? '-') ;

    document.getElementById('detailTotalHutang').textContent = 'Rp' + data.totalHutang;
    document.getElementById('detailSisaHutang').textContent = 'Rp' + data.sisaHutang;

    const listEl = document.getElementById('detailCicilanList');
    listEl.innerHTML = '';

    if (!data.cicilan.length) {

        listEl.innerHTML =
            '<p class="text-center text-gray-400 py-6">Tidak ada data cicilan</p>';

    } else {

        data.cicilan.forEach(function (c) {

            const isTerpilih = c.id === data.idCicilanTerpilih;

            // FIX: kalau baris cicilan ini adalah cicilan yang terkait
            // dengan pembayaran yang sedang dilihat (isTerpilih) DAN
            // pembayaran itu berstatus ditolak/pending, tampilkan status
            // pembayaran ini apa adanya. Jangan ikut status cicilan
            // secara global, karena cicilan yang sama bisa saja sudah
            // berstatus "lunas" akibat pengajuan pembayaran LAIN yang
            // disetujui belakangan — itu sebabnya sebelumnya pembayaran
            // yang ditolak tetap tampil "Lunas" di modal detail.
            let statusBadge;

            if (isTerpilih && data.statusPembayaranIni === 'ditolak') {

                statusBadge =
                    '<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">Ditolak</span>';

            } else if (isTerpilih && data.statusPembayaranIni === 'pending') {

                statusBadge =
                    '<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">Menunggu Verifikasi</span>';

            } else {

                statusBadge = c.status === 'lunas'
                    ? '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Lunas</span>'
                    : '<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">Belum Lunas</span>';

            }

            const row = document.createElement('div');

            row.className =
                'flex items-center justify-between gap-4 p-4 rounded-2xl border ' +
                (isTerpilih
                    ? 'border-[#5628C7] bg-[#F5F3FE]'
                    : 'border-gray-100 bg-gray-50');

            row.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-white border border-gray-200 flex items-center justify-center font-bold text-[#5628C7]">
                        ${c.cicilanKe}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Cicilan ke-${c.cicilanKe}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Jatuh Tempo: ${c.jatuhTempo}</p>
                        ${c.tanggalLunas ? '<p class="text-xs text-gray-500">Tanggal Lunas: ' + c.tanggalLunas + '</p>' : ''}
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-800 mb-1">${c.jumlah}</p>
                    ${statusBadge}
                    ${isTerpilih ? '<p class="text-xs font-semibold text-[#5628C7] mt-1">Pembayaran Ini</p>' : ''}
                </div>
            `;

            listEl.appendChild(row);

        });

    }

    document.getElementById('detailModal').classList.remove('hidden');
}

function closeDetailModal()
{
    document
        .getElementById('detailModal')
        .classList.add('hidden');
}

</script>

@endsection