@extends('layouts.agen')

@section('content')

<!-- HEADER -->
<div class="flex justify-between items-center mb-8">

    <div>

        <h1 class="text-3xl font-bold text-gray-800">
            Detail Hutang
        </h1>

        <p class="text-gray-500 mt-2">
            Informasi lengkap pengajuan hutang dan riwayat pembayaran.
        </p>

    </div>

    <span class="
        px-4 py-2 rounded-full text-sm font-semibold

        @if($hutang->status == 'lunas')
            bg-green-100 text-green-700
        @elseif($hutang->status == 'ditolak')
            bg-red-100 text-red-700
        @elseif($hutang->status == 'pending')
            bg-yellow-100 text-yellow-700
        @else
            bg-blue-100 text-blue-700
        @endif
    ">

        {{ ucfirst($hutang->status) }}

    </span>

</div>

<!-- INFORMASI HUTANG -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm mb-8">

    <h2 class="text-xl font-bold text-gray-800 mb-6">
        Informasi Hutang
    </h2>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

        <div>

            <p class="text-sm text-gray-500">
                Jumlah Hutang
            </p>

            <p class="text-2xl font-bold text-[#5628C7] mt-2">
                Rp{{ number_format($hutang->jumlah_hutang,0,',','.') }}
            </p>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Sisa Hutang
            </p>

            <p class="text-xl font-semibold mt-2">
                Rp{{ number_format($hutang->sisa_hutang,0,',','.') }}
            </p>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Metode Pembayaran
            </p>

            <p class="font-semibold mt-2">
                {{ ucfirst($hutang->metode) }}
            </p>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Tanggal Pengajuan
            </p>

            <p class="font-semibold mt-2">
                {{ \Carbon\Carbon::parse($hutang->tanggal_pengajuan)->format('d M Y') }}
            </p>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Tanggal Jatuh Tempo
            </p>

            <p class="font-semibold text-red-500 mt-2">
                {{ \Carbon\Carbon::parse($hutang->tanggal_jatuh_tempo)->format('d M Y') }}
            </p>

        </div>

    </div>

</div>

<!-- INFORMASI PENCAIRAN -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm mb-8">

    <h2 class="text-xl font-bold text-gray-800 mb-6">
        Informasi Pencairan
    </h2>

    <div class="grid md:grid-cols-3 gap-6">

        <div>

            <p class="text-sm text-gray-500">
                Tanggal Pencairan
            </p>

            <p class="font-semibold mt-2">
                {{ $hutang->tanggal_pencairan ?? '-' }}
            </p>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Keterangan
            </p>

            <p class="font-semibold mt-2">
                {{ $hutang->keterangan_pencairan ?? '-' }}
            </p>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Bukti Transfer
            </p>

            @if($hutang->bukti_pencairan)

                <a
                    href="{{ asset('uploads/'.$hutang->bukti_pencairan) }}"
                    target="_blank"
                    class="inline-block mt-2 text-[#5628C7] font-semibold hover:underline">

                    Lihat Bukti Transfer

                </a>

            @else

                <p class="font-semibold mt-2">
                    -
                </p>

            @endif

        </div>

    </div>

</div>

<!-- ALASAN PENOLAKAN -->
@if($hutang->status == 'ditolak')

<div class="bg-red-50 border border-red-200 rounded-3xl p-6 mb-8">

    <h2 class="text-lg font-bold text-red-700 mb-3">
        Alasan Penolakan
    </h2>

    <p class="text-red-600">

        {{ $hutang->alasan_penolakan }}

    </p>

</div>

@endif

<!-- DETAIL CICILAN -->

@if($hutang->metode == 'cicil')

<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm mb-8">

    <h2 class="text-xl font-bold text-gray-800 mb-6">
        Detail Cicilan
    </h2>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left py-3">
                        Cicilan
                    </th>

                    <th class="text-left py-3">
                        Nominal
                    </th>

                    <th class="text-left py-3">
                        Jatuh Tempo
                    </th>

                    <th class="text-left py-3">
                        Tanggal Lunas
                    </th>

                    <th class="text-left py-3">
                        Status
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($hutang->cicilan as $c)

                <tr class="border-b">

                    <td class="py-4">

                        Cicilan ke-{{ $c->cicilan_ke }}

                    </td>

                    <td>

                        Rp{{ number_format($c->jumlah_cicilan,0,',','.') }}

                    </td>

                    <td>

                        {{ \Carbon\Carbon::parse($c->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}

                    </td>

                    <td>

                        {{ $c->tanggal_lunas ? \Carbon\Carbon::parse($c->tanggal_lunas)->translatedFormat('d F Y') : '-' }}

                    </td>

                    <td>

                        @if($c->status == 'lunas')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">

                                Lunas

                            </span>

                        @else

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">

                                Belum Dibayar

                            </span>

                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endif

<!-- RIWAYAT PEMBAYARAN -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

    <h2 class="text-xl font-bold text-gray-800 mb-6">
        Riwayat Pembayaran
    </h2>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left py-3">
                        Tanggal
                    </th>

                    <th class="text-left py-3">
                        Jumlah
                    </th>

                    <th class="text-left py-3">
                        Status
                    </th>

                    <th class="text-left py-3">
                        Alasan Penolakan
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($pembayaran as $p)

                <tr class="border-b">

                    <td class="py-4">
                        {{ \Carbon\Carbon::parse($p->tanggal_pembayaran)->format('d M Y') }}
                    </td>

                    <td>
                        Rp{{ number_format($p->jumlah_bayar,0,',','.') }}
                    </td>

                    <td>

                        <span class="
                            px-3 py-1 rounded-full text-sm

                            @if($p->status == 'disetujui')
                                bg-green-100 text-green-700
                            @elseif($p->status == 'ditolak')
                                bg-red-100 text-red-700
                            @else
                                bg-yellow-100 text-yellow-700
                            @endif
                        ">

                            {{ ucfirst($p->status) }}

                        </span>

                    </td>

                    <td>

                        {{ $p->alasan_penolakan ?? '-' }}

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="4" class="text-center py-8 text-gray-500">

                        Belum ada pembayaran

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- BUTTON -->
<div class="mt-8 flex gap-4">

    <a
        href="/hutang-saya"
        class="px-6 py-3 bg-gray-100 rounded-xl font-semibold hover:bg-gray-200 transition">

        ← Kembali

    </a>

    @if(in_array($hutang->status, ['berjalan','terlambat']))

    <a
        href="/pembayaran/create/{{ $hutang->id }}"
        class="px-6 py-3 bg-[#5628C7] text-white rounded-xl font-semibold hover:bg-[#4b22b0] transition">

        Bayar Sekarang

    </a>

    @endif

</div>

@endsection