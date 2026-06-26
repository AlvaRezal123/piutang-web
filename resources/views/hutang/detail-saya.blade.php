@extends('layouts.agen')

@section('content')

@php
$persentase = $hutang->jumlah_hutang > 0
    ? (($hutang->jumlah_hutang - $hutang->sisa_hutang) / $hutang->jumlah_hutang) * 100
    : 0;
@endphp

<!-- HEADER -->
<div class="flex justify-between items-center mb-8">

    <div>
        <h1 class="text-3xl font-bold text-gray-800">Detail Hutang</h1>
        <p class="text-gray-500 mt-1">Informasi lengkap pengajuan hutang dan riwayat pembayaran.</p>
    </div>

    <span class="px-4 py-2 rounded-full text-sm font-semibold
        @if($hutang->status == 'lunas') bg-green-100 text-green-700
        @elseif($hutang->status == 'ditolak') bg-red-100 text-red-700
        @elseif($hutang->status == 'pending') bg-yellow-100 text-yellow-700
        @else bg-blue-100 text-blue-700
        @endif">
        {{ ucfirst($hutang->status) }}
    </span>

</div>

<!-- HERO CARD -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-700 to-purple-500 p-8 text-white shadow-xl mb-8">

    <div class="relative z-10">

        <p class="text-xs uppercase tracking-wider opacity-80">Jumlah Hutang</p>

        <h2 class="text-5xl font-bold mt-2">
            Rp{{ number_format($hutang->jumlah_hutang, 0, ',', '.') }}
        </h2>

        <div class="mt-8">

            <div class="flex justify-between text-sm mb-2">
                <span>Progress Pelunasan</span>
                <span>{{ round($persentase) }}%</span>
            </div>

            <div class="w-full bg-white/20 rounded-full h-3">
                <div class="bg-white h-3 rounded-full transition-all" style="width: {{ $persentase }}%"></div>
            </div>

            <div class="flex justify-between mt-3 text-sm">
                <span>Terbayar: Rp{{ number_format($hutang->jumlah_hutang - $hutang->sisa_hutang, 0, ',', '.') }}</span>
                <span>Sisa: Rp{{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</span>
            </div>

        </div>

    </div>

</div>

<!-- INFORMASI HUTANG -->
<div class="bg-white rounded-3xl p-8 border border-purple-100 shadow-sm">

    <h2 class="flex items-center gap-2 text-lg font-bold text-gray-800 mb-8">

        <span class="w-2 h-2 bg-purple-600 rounded-full"></span>

        Informasi Hutang

    </h2>

    <!-- BARIS ATAS -->
    <div class="grid md:grid-cols-3 gap-8">

        <!-- Jumlah Hutang -->
        <div class="flex items-center gap-4">

            <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center text-2xl">
                💰
            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Jumlah Hutang
                </p>

                <p class="text-3xl font-bold text-[#5628C7]">
                    Rp{{ number_format($hutang->jumlah_hutang,0,',','.') }}
                </p>

            </div>

        </div>

        <!-- Sisa Hutang -->
        <div class="flex items-center gap-4">

            <div class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center text-2xl">
                💸
            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Sisa Hutang
                </p>

                <p class="text-3xl font-bold text-gray-800">
                    Rp{{ number_format($hutang->sisa_hutang,0,',','.') }}
                </p>

            </div>

        </div>

        <!-- Metode -->
        <div class="flex items-center gap-4">

            <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-2xl">
                💳
            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Metode Pembayaran
                </p>

                <p class="text-2xl font-bold text-gray-800">
                    {{ ucfirst($hutang->metode) }}
                </p>

            </div>

        </div>

    </div>

    <!-- GARIS -->
    <div class="border-t border-gray-100 my-8"></div>

    <!-- BARIS BAWAH -->
    <div class="grid md:grid-cols-2 gap-8">

        <!-- Tanggal Pengajuan -->
        <div class="flex items-center gap-4">

            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center text-xl">
                📅
            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Tanggal Pengajuan
                </p>

                <p class="font-bold text-lg text-gray-800">
                    {{ \Carbon\Carbon::parse($hutang->tanggal_pengajuan)->translatedFormat('d F Y') }}
                </p>

            </div>

        </div>

        <!-- Jatuh Tempo -->
        <div class="flex items-center gap-4">

            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center text-xl">
                ⏰
            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Tanggal Jatuh Tempo
                </p>

                <p class="font-bold text-lg text-red-500">
                    {{ \Carbon\Carbon::parse($hutang->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}
                </p>

            </div>

        </div>

    </div>

</div>

<!-- INFORMASI PENCAIRAN -->
@if(in_array($hutang->status, ['berjalan', 'terlambat', 'lunas']))

<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm mb-6">

    <h2 class="flex items-center gap-2 text-lg font-bold text-gray-800 mb-6">
        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
        Informasi Pencairan
    </h2>

    <div class="grid md:grid-cols-3 gap-6">

        <div>
            <p class="text-sm text-gray-500 mb-1">Tanggal Pencairan</p>
            <p class="font-semibold text-gray-800">{{ $hutang->tanggal_pencairan ?? '-' }}</p>
        </div>


        <div>
            <p class="text-sm text-gray-500 mb-1">Bukti Transfer</p>
            @if($hutang->bukti_pencairan)
                <a href="{{ asset('uploads/' . $hutang->bukti_pencairan) }}"
                   target="_blank"
                   class="inline-flex items-center gap-1 text-[#5628C7] font-semibold hover:underline">
                    <i class="ti ti-external-link text-sm"></i> Lihat Bukti Transfer
                </a>
            @else
                <p class="font-semibold text-gray-800">-</p>
            @endif
        </div>

    </div>

</div>

@endif


<!-- DETAIL CICILAN -->
@if($hutang->metode == 'cicil')

<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm mb-6">

    <h2 class="text-xl font-bold text-gray-800 mb-6">Detail Cicilan</h2>

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 text-gray-500 font-semibold">Cicilan</th>
                    <th class="text-left py-3 text-gray-500 font-semibold">Nominal</th>
                    <th class="text-left py-3 text-gray-500 font-semibold">Jatuh Tempo</th>
                    <th class="text-left py-3 text-gray-500 font-semibold">Tanggal Lunas</th>
                    <th class="text-left py-3 text-gray-500 font-semibold">Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($hutang->cicilan as $c)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <td class="py-4 font-medium text-gray-800">Cicilan ke-{{ $c->cicilan_ke }}</td>
                    <td class="py-4 font-semibold text-gray-800">Rp{{ number_format($c->jumlah_cicilan, 0, ',', '.') }}</td>
                    <td class="py-4 text-gray-600">{{ \Carbon\Carbon::parse($c->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}</td>
                    <td class="py-4 text-gray-600">{{ $c->tanggal_lunas ? \Carbon\Carbon::parse($c->tanggal_lunas)->translatedFormat('d F Y') : '-' }}</td>
                    <td class="py-4">
                        @if($c->status == 'lunas')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Lunas</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">Belum Dibayar</span>
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
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm mb-8">

    <h2 class="text-xl font-bold text-gray-800 mb-6">Riwayat Pembayaran</h2>

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 text-gray-500 font-semibold">Tanggal</th>
                    <th class="text-left py-3 text-gray-500 font-semibold">Jumlah</th>
                    <th class="text-left py-3 text-gray-500 font-semibold">Status</th>
                    <th class="text-left py-3 text-gray-500 font-semibold">Alasan Penolakan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pembayaran as $p)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <td class="py-4 text-gray-600">{{ \Carbon\Carbon::parse($p->tanggal_pembayaran)->format('d M Y') }}</td>
                    <td class="py-4 font-semibold text-gray-800">Rp{{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($p->status == 'disetujui') bg-green-100 text-green-700
                            @elseif($p->status == 'ditolak') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700
                            @endif">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td class="py-4 text-gray-500">{{ $p->alasan_penolakan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-10 text-gray-400">
                        <i class="ti ti-inbox text-3xl block mb-2"></i>
                        Belum ada pembayaran
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

<!-- BUTTON -->
<div class="flex gap-4">

    <a href="/hutang-saya"
       class="flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition">
        <i class="ti ti-arrow-left text-sm"></i> Kembali
    </a>

    @if(in_array($hutang->status, ['berjalan', 'terlambat']))
    <a href="/pembayaran/create/{{ $hutang->id }}"
       class="flex items-center gap-2 px-6 py-3 bg-[#5628C7] text-white rounded-xl font-semibold hover:bg-[#4b22b0] transition">
        <i class="ti ti-credit-card text-sm"></i> Bayar Sekarang
    </a>
    @endif

</div>

@endsection