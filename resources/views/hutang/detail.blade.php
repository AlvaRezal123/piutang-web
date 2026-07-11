@extends('layouts.owner')

@section('content')

<!-- HEADER -->
<div class="flex items-center justify-between mb-8">

    <div>
        <h1 class="text-3xl font-bold text-gray-800">Detail Pengajuan Hutang</h1>
        <p class="text-gray-400 text-sm mt-1">Informasi lengkap pengajuan dan riwayat hutang agen.</p>
    </div>

    <span class="px-4 py-2 rounded-full text-sm font-semibold
        @if($hutang->status == 'pending') bg-yellow-100 text-yellow-700
        @elseif($hutang->status == 'disetujui') bg-purple-100 text-purple-700
        @elseif($hutang->status == 'berjalan') bg-blue-100 text-blue-700
        @elseif($hutang->status == 'lunas') bg-green-100 text-green-700
        @elseif($hutang->status == 'ditolak') bg-red-100 text-red-700
        @else bg-gray-100 text-gray-600
        @endif">
        {{ ucfirst($hutang->status) }}
    </span>

</div>

<!-- HERO NOMINAL -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#5628C7] to-[#7B52E0] p-8 text-white mb-8">

    <div class="relative z-10 flex items-center justify-between">

        <div>
            <p class="text-xs uppercase tracking-widest text-white/70 mb-1">Jumlah Pengajuan</p>
            <h2 class="text-5xl font-bold">Rp{{ number_format($hutang->jumlah_hutang, 0, ',', '.') }}</h2>
            <div class="flex items-center gap-4 mt-4">
                <span class="flex items-center gap-1.5 text-sm text-white/80">
                    <i class="ti ti-{{ $hutang->metode == 'cicil' ? 'calendar-repeat' : 'cash' }}"></i>
                    @if($hutang->metode == 'cash')
                        Pembayaran Penuh
                    @else
                        Cicilan {{ ucfirst($hutang->lama_tempo) }}
                    @endif
                </span>
                <span class="text-white/40">·</span>
                <span class="flex items-center gap-1.5 text-sm text-white/80">
                    <i class="ti ti-calendar"></i>
                    {{ \Carbon\Carbon::parse($hutang->tanggal_pengajuan)->format('d M Y') }}
                </span>
        
        
            </div>
        </div>

        <div class="text-right text-white/70 text-sm hidden md:block">
            <p class="text-xs uppercase tracking-widest mb-1">Agen</p>
            <p class="text-2xl font-bold text-white">{{ $hutang->agen->username }}</p>
            <p class="text-sm text-white/60 mt-1">ID: {{ $hutang->agen->id_agen_pp }}</p>
        </div>

    </div>

</div>

<!-- INFO GRID -->
<div class="grid md:grid-cols-2 gap-6 mb-8">

    <!-- DATA AGEN -->
    <div class="bg-white rounded-2xl p-6 border border-purple-100 shadow-sm">

        <h2 class="flex items-center gap-2 text-sm font-semibold text-gray-800 mb-5">
            <span class="w-2 h-2 rounded-full bg-[#5628C7]"></span>
            Data Agen
        </h2>

        <div class="flex items-center gap-4 mb-6 pb-5 border-b border-gray-100">
            <div class="w-14 h-14 rounded-full bg-purple-100 text-[#5628C7] text-xl font-bold flex items-center justify-center flex-shrink-0">
                {{ strtoupper(substr($hutang->agen->username, 0, 2)) }}
            </div>
            <div>
                <p class="font-bold text-gray-800 text-lg">{{ $hutang->agen->username }}</p>
                <p class="text-sm text-gray-400">ID: {{ $hutang->agen->id_agen_pp }}</p>
            </div>
            <span class="ml-auto px-3 py-1 rounded-full text-xs font-semibold
                {{ $hutang->agen->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                {{ ucfirst($hutang->agen->status) }}
            </span>
        </div>

        <div class="space-y-4">

            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-phone text-[#5628C7] text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">No. HP</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $hutang->agen->no_hp }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-id-badge text-[#5628C7] text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">ID Agen PP</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $hutang->agen->id_agen_pp }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-user-check text-[#5628C7] text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Status Agen</p>
                    <p class="text-sm font-semibold text-gray-800">{{ ucfirst($hutang->agen->status) }}</p>
                </div>
            </div>

        </div>

    </div>

    <!-- DETAIL PENGAJUAN -->
    <div class="bg-white rounded-2xl p-6 border border-purple-100 shadow-sm">

        <h2 class="flex items-center gap-2 text-sm font-semibold text-gray-800 mb-5">
            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            Pengajuan Saat Ini
        </h2>

        <div class="space-y-4">

            <div class="flex items-center justify-between py-3 border-b border-gray-50">
                <div class="flex items-center gap-3">
                    <i class="ti ti-cash text-gray-300 text-lg"></i>
                    <p class="text-sm text-gray-500">Jumlah Hutang</p>
                </div>
                <p class="text-sm font-bold text-[#5628C7]">Rp{{ number_format($hutang->jumlah_hutang, 0, ',', '.') }}</p>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-gray-50">
                <div class="flex items-center gap-3">
                    <i class="ti ti-file-description text-gray-300 text-lg"></i>
                    <p class="text-sm text-gray-500">Metode Pembayaran</p>
                </div>
                <p class="text-sm font-semibold text-gray-800">@if($hutang->metode == 'cash')
                                                                    Pembayaran Penuh
                                                                @else
                                                                    Cicilan {{ ucfirst($hutang->lama_tempo) }}
                                                                @endif</p>
                                                                            </div>
@php

$statusPembayaran = '-';

if ($hutang->metode == 'cash') {

    switch ($hutang->status) {

        case 'pending':
            $statusPembayaran = 'Belum Diproses';
            break;

        case 'disetujui':
            $statusPembayaran = 'Menunggu Pencairan Dana';
            break;

        case 'ditolak':
            $statusPembayaran = 'Pengajuan Ditolak';
            break;

        case 'berjalan':
            $statusPembayaran = 'Menunggu Pembayaran Penuh';
            break;

        case 'terlambat':
            $statusPembayaran = 'Pembayaran Penuh Terlambat';
            break;

        case 'lunas':
            $statusPembayaran = 'Pembayaran Selesai';
            break;
    }

} else {

    switch ($hutang->status) {

        case 'pending':
            $statusPembayaran = 'Belum Diproses';
            break;

        case 'disetujui':
            $statusPembayaran = 'Menunggu Pencairan Dana';
            break;

        case 'ditolak':
            $statusPembayaran = 'Pengajuan Ditolak';
            break;

        case 'berjalan':
        case 'terlambat':

            if ($cicilanAktif) {

                if ($cicilanAktif->status == 'terlambat') {

                    $statusPembayaran =
                        'Cicilan ke-' .
                        $cicilanAktif->cicilan_ke .
                        ' Terlambat';

                } else {

                    $statusPembayaran =
                        'Cicilan ke-' .
                        $cicilanAktif->cicilan_ke;

                }

            }

            break;

        case 'lunas':
            $statusPembayaran = 'Seluruh Cicilan Lunas';
            break;
    }

}

@endphp
<div class="flex items-center justify-between py-3 border-b border-gray-50">

    <div class="flex items-center gap-3">

        <i class="ti ti-credit-card text-gray-300 text-lg"></i>

        <p class="text-sm text-gray-500">
            Status Pembayaran
        </p>

    </div>

    <p class="text-sm font-semibold text-gray-800">

        {{ $statusPembayaran }}

    </p>

</div>
            <div class="flex items-center justify-between py-3 border-b border-gray-50">
                <div class="flex items-center gap-3">
                    <i class="ti ti-calendar text-gray-300 text-lg"></i>
                    <p class="text-sm text-gray-500">Tanggal Pengajuan</p>
                </div>
                <p class="text-sm font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($hutang->tanggal_pengajuan)->format('d M Y') }}
                </p>
            </div>

            <div class="flex items-center justify-between py-3">
                <div class="flex items-center gap-3">
                    <i class="ti ti-info-circle text-gray-300 text-lg"></i>
                    <p class="text-sm text-gray-500">Status</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                    @if($hutang->status == 'pending') bg-yellow-100 text-yellow-700
                    @elseif($hutang->status == 'disetujui') bg-purple-100 text-purple-700
                    @elseif($hutang->status == 'berjalan') bg-blue-100 text-blue-700
                    @elseif($hutang->status == 'lunas') bg-green-100 text-green-700
                    @elseif($hutang->status == 'ditolak') bg-red-100 text-red-700
                    @else bg-gray-100 text-gray-600
                    @endif">
                    {{ ucfirst($hutang->status) }}
                </span>
            </div>

        </div>

    </div>

</div>

<!-- RIWAYAT HUTANG -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm mb-8">

    <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

        <h2 class="text-xl font-bold text-gray-800">
            Riwayat Hutang Agen
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
            <select id="filterStatusRiwayat" class="border border-gray-300 rounded-xl px-4 py-2">

                <option value="all">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="disetujui">Disetujui</option>
                <option value="berjalan">Berjalan</option>
                <option value="lunas">Lunas</option>
                <option value="ditolak">Ditolak</option>

            </select>

            <!-- Cari -->
            <button
                type="button"
                id="btnCariRiwayat"
                class="bg-purple-600 text-white px-4 py-2 rounded-xl">

                Cari

            </button>

            <!-- Reset -->
            <button
                type="button"
                id="resetFilterRiwayat"
                class="border border-gray-300 bg-red-600 text-white px-4 py-2 rounded-xl">

                Reset

            </button>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full table-auto">

            <thead>

                <tr class="border-b">
                    <th class="text-left py-3">ID Pengajuan</th>
                    <th class="text-left py-3">Tanggal</th>
                    <th class="text-left py-3">Jumlah</th>
                    <th class="text-left py-3">Metode</th>
                    <th class="text-left py-3">Status</th>
                    <th class="text-left py-3">Aksi</th>
                </tr>

            </thead>

            <tbody>

                @forelse($riwayat as $r)

                <tr
                    class="border-b riwayat-row"
                    data-status="{{ $r->status }}"
                    data-tahun="{{ \Carbon\Carbon::parse($r->tanggal_pengajuan)->format('Y') }}"
                    data-bulan="{{ \Carbon\Carbon::parse($r->tanggal_pengajuan)->format('m') }}">

                    <td class="py-4 text-gray-500 font-medium">
                        #{{ $r->id }}
                    </td>

                    <td class="py-4 text-gray-500">
                        {{ \Carbon\Carbon::parse($r->tanggal_pengajuan)->format('d M Y') }}
                    </td>

                    <td class="font-semibold text-gray-800">
                        Rp{{ number_format($r->jumlah_hutang, 0, ',', '.') }}
                    </td>

                    <td class="text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <i class="ti ti-{{ $r->metode == 'cicil' ? 'calendar-repeat' : 'cash' }} text-gray-300 text-sm"></i>
                            @if($r->metode == 'cash')
                                Pembayaran Penuh
                            @elseif($r->lama_tempo == '2 bulan')
                                Cicilan 2 Bulan
                            @elseif($r->lama_tempo == '3 bulan')
                                Cicilan 3 Bulan
                            @endif
                        </span>
                    </td>

                    <td>

                        @if($r->status == 'pending')

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                Pending
                            </span>

                        @elseif($r->status == 'disetujui')

                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">
                                Disetujui
                            </span>

                        @elseif($r->status == 'berjalan')

                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                Berjalan
                            </span>

                        @elseif($r->status == 'lunas')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                Lunas
                            </span>

                        @elseif($r->status == 'ditolak')

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                Ditolak
                            </span>

                        @else

                            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm">
                                {{ ucfirst($r->status) }}
                            </span>

                        @endif

                    </td>

                    <td>
                        @if($r->status == 'ditolak')
                            <button
                                type="button"
                                class="btn-lihat-alasan text-red-600 text-sm font-semibold hover:underline flex items-center gap-1"
                                data-alasan="{{ $r->alasan_penolakan ?? 'Tidak ada alasan yang tercatat.' }}"
                                data-tanggal="{{ \Carbon\Carbon::parse($r->tanggal_pengajuan)->format('d M Y') }}">
                                <i class="ti ti-info-circle text-sm"></i> Lihat Alasan
                            </button>
                        @else
                            <span class="text-gray-300 text-sm">-</span>
                        @endif
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        Belum ada riwayat hutang
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- ACTION BUTTONS -->
<div class="flex items-center justify-between">

    <a href="/owner/hutang"
       class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition-colors">
        <i class="ti ti-arrow-left text-sm"></i> Kembali
    </a>

    @if($hutang->status == 'pending')

    <div class="flex gap-3">

        <a href="/owner/hutang/setujui/{{ $hutang->id }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-green-500 text-white text-sm font-semibold hover:bg-green-600 transition-colors shadow-sm">
            <i class="ti ti-check text-sm"></i> Setujui Pengajuan
        </a>

        <a href="/owner/hutang/form-tolak/{{ $hutang->id }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-red-500 text-white text-sm font-semibold hover:bg-red-600 transition-colors shadow-sm">
            <i class="ti ti-x text-sm"></i> Tolak Pengajuan
        </a>

    </div>

    @endif

</div>

<!-- MODAL ALASAN PENOLAKAN -->
<div id="modalAlasanPenolakan" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-xl">

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="ti ti-alert-triangle text-red-500"></i>
                Alasan Penolakan
            </h3>
            <button type="button" id="closeModalAlasan" class="text-gray-400 hover:text-gray-600">
                <i class="ti ti-x text-lg"></i>
            </button>
        </div>

        <p class="text-xs text-gray-400 mb-1">Tanggal Pengajuan</p>
        <p id="modalAlasanTanggal" class="text-sm font-semibold text-gray-800 mb-4"></p>

        <p class="text-xs text-gray-400 mb-1">Alasan</p>
        <div class="bg-red-50 rounded-xl p-4 text-sm text-gray-700 leading-relaxed" id="modalAlasanTeks"></div>

        <button type="button" id="closeModalAlasanBtn"
            class="w-full mt-5 bg-gray-100 text-gray-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-200">
            Tutup
        </button>

    </div>
</div>

<script>

const filterBulan = document.getElementById('filterBulan');
const filterTahun = document.getElementById('filterTahun');
const filterStatusRiwayat = document.getElementById('filterStatusRiwayat');
const btnCariRiwayat = document.getElementById('btnCariRiwayat');
const resetFilterRiwayat = document.getElementById('resetFilterRiwayat');

function filterRiwayat() {

    let bulan = filterBulan.value;
    let tahun = filterTahun.value;
    let status = filterStatusRiwayat.value;

    document.querySelectorAll('.riwayat-row').forEach(function (row) {

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

filterBulan.addEventListener('change', filterRiwayat);
filterTahun.addEventListener('change', filterRiwayat);
filterStatusRiwayat.addEventListener('change', filterRiwayat);
btnCariRiwayat.addEventListener('click', filterRiwayat);

resetFilterRiwayat.addEventListener('click', function () {

    filterBulan.value = '';
    filterTahun.value = '';
    filterStatusRiwayat.value = 'all';

    filterRiwayat();

});

const modalAlasan = document.getElementById('modalAlasanPenolakan');
const modalAlasanTeks = document.getElementById('modalAlasanTeks');
const modalAlasanTanggal = document.getElementById('modalAlasanTanggal');

document.querySelectorAll('.btn-lihat-alasan').forEach(function (btn) {
    btn.addEventListener('click', function () {
        modalAlasanTeks.textContent = btn.dataset.alasan;
        modalAlasanTanggal.textContent = btn.dataset.tanggal;
        modalAlasan.classList.remove('hidden');
        modalAlasan.classList.add('flex');
    });
});

function tutupModalAlasan() {
    modalAlasan.classList.add('hidden');
    modalAlasan.classList.remove('flex');
}

document.getElementById('closeModalAlasan').addEventListener('click', tutupModalAlasan);
document.getElementById('closeModalAlasanBtn').addEventListener('click', tutupModalAlasan);

</script>

@endsection