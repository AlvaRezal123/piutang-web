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

    <a href="/hutang/create"
       class="bg-[#5628C7] text-white px-6 py-3 rounded-2xl font-semibold hover:bg-[#4b22b0] transition">

        + Ajukan Hutang

    </a>

</div>

<!-- CARD RINGKASAN -->
<div class="grid md:grid-cols-4 gap-6 mb-8">
     <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <p class="text-sm text-gray-500">
        Status Pinjaman
    </p>

    <h2 class="text-3xl font-bold mt-3">

        @if($hutang->whereIn('status',['pending','disetujui','berjalan','terlambat'])->count() > 0)

            <span class="text-green-600">
                Aktif
            </span>

        @else

            <span class="text-gray-500">
                Tidak Ada
            </span>

        @endif

    </h2>

</div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Total Pengajuan
        </p>

        <h2 class="text-3xl font-bold text-[#5628C7] mt-3">
            {{ count($hutang) }}
        </h2>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Hutang Aktif
        </p>

        <h2 class="text-3xl font-bold text-blue-600 mt-3">

            {{ $hutang->whereIn('status',['pending','disetujui','berjalan'])->count() }}

        </h2>

    </div>


    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm text-gray-500">
            Ditolak
        </p>

        <h2 class="text-3xl font-bold text-red-600 mt-3">

            {{ $hutang->where('status','ditolak')->count() }}

        </h2>

    </div>

</div>

<!-- HUTANG AKTIF -->
@php

$hutangAktif = $hutang->whereIn('status',
[
'pending',
'disetujui',
'berjalan',
'terlambat'
])->first();

@endphp

@if($hutangAktif)

<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm mb-8">

    <div class="flex justify-between items-center mb-5">

        <h2 class="text-xl font-bold text-gray-800">
            Hutang Aktif Saat Ini
        </h2>

        <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-semibold">

            {{ ucfirst($hutangAktif->status) }}

        </span>

    </div>

    <div class="grid md:grid-cols-4 gap-6">

        <div>

            <p class="text-sm text-gray-500">
                Nominal Hutang
            </p>

            <p class="text-2xl font-bold text-[#5628C7] mt-2">
                Rp{{ number_format($hutangAktif->jumlah_hutang,0,',','.') }}
            </p>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Sisa Hutang
            </p>

            <p class="text-xl font-semibold mt-2">
                Rp{{ number_format($hutangAktif->sisa_hutang,0,',','.') }}
            </p>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Jatuh Tempo
            </p>

            <p class="text-xl font-semibold text-red-500 mt-2">
                {{ \Carbon\Carbon::parse($hutangAktif->tanggal_jatuh_tempo)->format('d M Y') }}
            </p>

        </div>

        <div class="flex items-end gap-3">

            <a href="/hutang/detail/{{ $hutangAktif->id }}"
               class="bg-purple-100 text-[#5628C7] px-5 py-2 rounded-xl font-semibold">

                Detail

            </a>

            @if(!$hutangAktif->pembayaran_pending)

            <a href="/pembayaran/create/{{ $hutangAktif->id }}"
               class="bg-[#5628C7] text-white px-5 py-2 rounded-xl font-semibold">

                Bayar

            </a>

            @endif

        </div>

    </div>

</div>

@endif

<!-- RIWAYAT -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-xl font-bold text-gray-800">
            Riwayat Pengajuan
        </h2>

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
                data-status="{{ $h->status }}">

                <td class="py-4">
                    {{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->format('d M Y') }}
                </td>

                <td>
                    Rp{{ number_format($h->jumlah_hutang,0,',','.') }}
                </td>

                <td>
                    Rp{{ number_format($h->sisa_hutang,0,',','.') }}
                </td>

                <td>

                    <span class="px-3 py-1 rounded-full text-sm bg-gray-100">

                        {{ ucfirst($h->status) }}

                    </span>

                </td>

                <td>

                    {{ \Carbon\Carbon::parse($h->tanggal_jatuh_tempo)->format('d M Y') }}

                </td>

                <td>

                    <a
                        href="/hutang/detail/{{ $h->id }}"
                        class="text-[#5628C7] font-semibold">

                        Detail

                    </a>

                </td>

            </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

<script>

document.getElementById('filterStatus')
.addEventListener('change', function(){

    let status = this.value;

    document.querySelectorAll('.status-row')
    .forEach(function(row){

        if(status === 'all'){

            row.style.display = '';

        }else{

            row.style.display =
            row.dataset.status === status
            ? ''
            : 'none';

        }

    });

});

</script>

@endsection