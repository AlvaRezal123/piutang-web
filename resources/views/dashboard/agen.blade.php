@extends('layouts.agen')

@section('content')

<!-- HEADER -->
<div class="mb-8">

    <h1 class="text-3xl font-bold text-gray-800">
        Halo, {{ $agen->username }}
    </h1>

    <p class="text-gray-500 mt-2">
        Semoga aktivitas penjualan pulsa Anda hari ini berjalan lancar.
    </p>

    <div class="mt-4 flex items-center gap-3">
        <span class="px-4 py-2 bg-purple-100 text-[#5628C7] rounded-full text-sm font-semibold">
            ID Agen : {{ $agen->id_agen_pp }}
        </span>
    </div>

</div>

<!-- CARD STATISTIK -->
<div class="grid lg:grid-cols-12 gap-6 mt-8">

    <!-- TOTAL UANG DIPINJAM -->
    <div class="lg:col-span-6 bg-gradient-to-r from-[#5628C7] to-purple-600 rounded-3xl p-8 text-white shadow-sm">

        <p class="text-sm uppercase tracking-wider text-white/80">
            Total Uang Dipinjam
        </p>

        <h2 class="text-5xl font-bold mt-4">
            Rp{{ number_format($totalUangDipinjam,0,',','.') }}
        </h2>

        <p class="mt-4 text-white/80">
            Akumulasi pinjaman yang disetujui
        </p>

    </div>

    <!-- TOTAL PENGAJUAN PIUTANG -->
    <div class="lg:col-span-3 bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm font-bold text-gray-500">
            Total Pengajuan Hutang
        </p>

        <h2 class="text-4xl font-bold text-[#5628C7] mt-4">
            {{ $totalPengajuanHutang }}
        </h2>

        <p class="text-xs text-gray-400 mt-4">
            Total pengajuan yang pernah dibuat
        </p>

    </div>

    <!-- STATUS KREDIT -->
    <div class="lg:col-span-3 bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <p class="text-sm font-bold text-gray-500">
            Status Kredit
        </p>

        <h2 class="text-3xl font-bold mt-4
            @if($agen->status_kredit == 'terpercaya')
                text-green-600
            @elseif($agen->status_kredit == 'bermasalah')
                text-red-600
            @else
                text-blue-600
            @endif
        ">
            {{ ucfirst($agen->status_kredit) }}
        </h2>

        <p class="text-xs text-gray-400 mt-4">
            @if($agen->status_kredit == 'terpercaya')
                Agen dengan riwayat pembayaran baik
            @elseif($agen->status_kredit == 'bermasalah')
                Terdapat riwayat keterlambatan pembayaran
            @else
                Agen baru dalam sistem
            @endif
        </p>

    </div>

</div>

<!-- QUICK ACTION -->
<div class="grid md:grid-cols-3 gap-6 mt-8">

    <!-- Ajukan -->
    <a href="/hutang/create"
        class="bg-gradient-to-r from-[#5628C7] to-purple-600 rounded-3xl p-6 shadow-sm hover:shadow-lg transition text-white">

        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center mb-4">
            <i class="ti ti-cash text-3xl"></i>
        </div>

        <h3 class="font-bold text-white">
            Ajukan Piutang
        </h3>

        <p class="text-sm text-white/80 mt-2">
            Buat pengajuan pinjaman baru
        </p>

    </a>

    <!-- Bayar -->
    <a href="/pembayaran"
        class="bg-white rounded-3xl p-6 shadow-sm border border-purple-100 hover:shadow-lg transition">

        <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center mb-4">
            <i class="ti ti-credit-card text-3xl text-[#5628C7]"></i>
        </div>

        <h3 class="font-bold text-gray-800">
            Bayar Piutang
        </h3>

        <p class="text-sm text-gray-500 mt-2">
            Lakukan pembayaran hutang
        </p>

    </a>

    <!-- Hutang Saya -->
    <a href="/hutang-saya"
        class="bg-white rounded-3xl p-6 shadow-sm border border-purple-100 hover:shadow-lg transition">

        <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center mb-4">
            <i class="ti ti-wallet text-3xl text-[#5628C7]"></i>
        </div>

        <h3 class="font-bold text-gray-800">
            Hutang Saya
        </h3>

        <p class="text-sm text-gray-500 mt-2">
            Lihat detail hutang aktif
        </p>

    </a>

</div>

<!-- RINGKASAN HUTANG + AKTIVITAS -->
<div class="grid lg:grid-cols-2 gap-6 mt-8">

    <!-- RINGKASAN HUTANG -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-purple-100">

        <h2 class="text-xl font-bold text-gray-800 mb-8 flex items-center gap-2">
            <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
            Ringkasan Hutang Aktif
        </h2>

        <div class="space-y-6">

            <div class="flex justify-between border-b border-dashed pb-4">
                <span class="text-gray-500">
                    Nominal Hutang
                </span>

                <span class="font-bold text-xl text-[#5628C7]">
                    Rp{{ number_format($hutangAktif->jumlah_hutang ?? 0,0,',','.') }}
                </span>
            </div>

            <div class="flex justify-between border-b border-dashed pb-4">
                <span class="text-gray-500">
                    Tanggal Pengajuan
                </span>

                <span class="font-semibold">
                    @if($hutangAktif)
                        {{ \Carbon\Carbon::parse($hutangAktif->tanggal_pengajuan)->translatedFormat('d F Y') }}
                    @else
                        -
                    @endif
                </span>
            </div>

            <div class="flex justify-between border-b border-dashed pb-4">
                <span class="text-gray-500">
                    Metode
                </span>

                <span class="font-semibold">
                    @if($hutangAktif)
                        @if($hutangAktif->metode == 'cash')
                            Pembayaran Penuh
                        @else
                            Cicilan {{ $hutangAktif->lama_tempo }}
                        @endif
                    @else
                        -
                    @endif
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">
                    Status
                </span>

                @if($hutangAktif)
                    <span class="
                        px-4 py-1 rounded-full text-sm font-semibold
                        @if($hutangAktif->status == 'lunas')
                            bg-green-100 text-green-700
                        @elseif($hutangAktif->status == 'terlambat')
                            bg-red-100 text-red-700
                        @elseif($hutangAktif->status == 'pending')
                            bg-yellow-100 text-yellow-700
                        @else
                            bg-blue-100 text-blue-700
                        @endif
                    ">
                        {{ ucfirst($hutangAktif->status) }}
                    </span>
                @else
                    <span class="px-4 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-600">
                        Belum Ada Hutang/ Pengajuan yang Aktif
                    </span>
                @endif

            </div>

        </div>

    </div>

    <!-- AKTIVITAS -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-purple-100">

        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
            Aktivitas Terbaru
        </h2>

        <div class="overflow-y-auto h-[420px] pr-2 space-y-5">

            @forelse($aktivitas as $item)

                @php
                    $icon = '<i class="ti ti-file-text text-purple-600 text-xl"></i>';

                    if ($item->tipe == 'pembayaran') {
                        $icon = '<i class="ti ti-credit-card text-blue-600 text-xl"></i>';
                    } elseif ($item->tipe == 'persetujuan') {
                        $icon = '<i class="ti ti-circle-check text-green-600 text-xl"></i>';
                    } elseif ($item->tipe == 'pencairan') {
                        $icon = '<i class="ti ti-cash text-yellow-600 text-xl"></i>';
                    }
                @endphp

                <div class="flex items-start gap-4">

                    <div class="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center">
                        {!! $icon !!}
                    </div>

                    <div class="flex-1">

                        <p class="font-semibold text-gray-800">
                            {{ $item->judul }}
                        </p>

                        <p class="text-gray-600 text-sm mt-1">
                            {{ $item->pesan }}
                        </p>

                        <p class="text-xs text-gray-400 mt-2">
                            {{ $item->created_at->diffForHumans() }}
                        </p>

                    </div>

                </div>

            @empty

                <div class="text-center py-10 text-gray-500">
                    Belum ada aktivitas terbaru
                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection