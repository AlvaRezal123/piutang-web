@extends('layouts.agen')

@section('content')

<!-- HEADER -->

<!-- PAGE HEADER -->

<!-- HEADER -->

<div class="mb-8">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Halo, {{ $agen->username }} 👋
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

</div>

<!-- CARD STATISTIK -->

<div class="grid md:grid-cols-4 gap-6 mt-8">


    <!-- LIMIT PINJAMAN -->
    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm hover:shadow-md transition">

        <p class="text-sm text-gray-500">
            Limit Pinjaman
        </p>

        <h2 class="text-3xl font-bold text-[#5628C7] mt-3">
            @if($hutangAktif)
          Rp{{ number_format($hutangAktif->jumlah_hutang, 0, ',', '.') }}
            @else
                -
            @endif
        </h2>

        <p class="text-xs text-gray-400 mt-2">
            Limit maksimal yang tersedia
        </p>

    </div>

    <!-- HUTANG AKTIF -->
    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm hover:shadow-md transition">

       <p class="text-sm text-gray-500">

@if($hutangAktif)

    @if($hutangAktif->status == 'pending')

        Pengajuan Pending

    @elseif($hutangAktif->status == 'disetujui')

        Menunggu Pencairan

    @else

        Hutang Aktif

    @endif

@else

    Tidak Ada Hutang

@endif

</p>
    </div>

    <!-- JATUH TEMPO -->
    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm hover:shadow-md transition">

        <p class="text-sm text-gray-500">
            Jatuh Tempo
        </p>

        <h2 class="text-xl font-semibold text-red-600 mt-3">
            @if($tanggalJatuhTempo)

            {{ \Carbon\Carbon::parse($tanggalJatuhTempo)->translatedFormat('d F Y') }}

            @else

            -

            @endif
        </h2>

        <p class="text-xs text-red-500 mt-2">
            @if($sisaHari !== null)

            Sisa {{ $sisaHari }} Hari Lagi

            @else

            Tidak Ada Hutang Aktif

            @endif
        </p>

    </div>

    <!-- STATUS KREDIT -->
    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm hover:shadow-md transition">

        <p class="text-sm text-gray-500">
            Status Kredit
        </p>

        <h2 class="text-3xl font-bold text-green-600 mt-3">
            {{ $agen->status_kredit ?? 'Baru' }}
        </h2>

       <p class="text-xs text-gray-400 mt-2">

@if($hutangAktif)

    @if($hutangAktif->status == 'pending')

        Menunggu persetujuan owner

    @elseif($hutangAktif->status == 'disetujui')

        Menunggu pencairan saldo

    @elseif($hutangAktif->status == 'berjalan')

        Pinjaman sedang berjalan

    @elseif($hutangAktif->status == 'terlambat')

        Terdapat keterlambatan pembayaran

    @endif

@else

    Tidak ada pinjaman aktif

@endif

</p>
            Tidak ada keterlambatan pembayaran
        </p>

    </div>
</div>
@if(
    $hutangAktif &&
    in_array(
        $hutangAktif->status,
        ['berjalan','terlambat']
    )
)
<!-- REMINDER -->

<div class="mt-8 rounded-3xl p-6

        @if($sisaHari < 0)
            bg-red-50 border border-red-200
        @elseif($sisaHari <= 7)
            bg-yellow-50 border border-yellow-200
        @else
            bg-green-50 border border-green-200
        @endif
        ">


    <h3 class="font-semibold text-yellow-800">
        Pengingat Pembayaran
    </h3>

    <p class="text-yellow-700 mt-2">

        @if($sisaHari > 0)

        Anda memiliki tagihan aktif sebesar
        Rp{{ number_format($jumlahHutangAktif,0,',','.') }}
        yang akan jatuh tempo dalam
        <strong>{{ $sisaHari }} hari</strong>.

        @elseif($sisaHari == 0)

        Tagihan sebesar
        Rp{{ number_format($jumlahHutangAktif,0,',','.') }}
        jatuh tempo hari ini.

        @else

        Tagihan sebesar
        Rp{{ number_format($jumlahHutangAktif,0,',','.') }}
        telah terlambat
        <strong>{{ abs($sisaHari) }} hari</strong>.

        @endif

    </p>

</div>
@endif


<!-- QUICK ACTION -->
<div class="grid md:grid-cols-3 gap-6 mt-8">

    <!-- Ajukan -->
    <a href="/hutang/create"
        class="bg-white rounded-3xl p-6 shadow-sm border border-purple-100 hover:shadow-lg transition">

        <div class="text-3xl mb-3">
            💰
        </div>

        <h3 class="font-bold text-gray-800">
            Ajukan Piutang
        </h3>

        <p class="text-sm text-gray-500 mt-2">
            Buat pengajuan pinjaman baru
        </p>

    </a>

    <!-- Bayar -->
    <a href="/pembayaran"
        class="bg-white rounded-3xl p-6 shadow-sm border border-purple-100 hover:shadow-lg transition">

        <div class="text-3xl mb-3">
            💳
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

        <div class="text-3xl mb-3">
            📋
        </div>

        <h3 class="font-bold text-gray-800">
            Hutang Saya
        </h3>

        <p class="text-sm text-gray-500 mt-2">
            Lihat detail hutang aktif
        </p>

    </a>

</div>
<!-- RINGKASAN HUTANG -->
<div class="mt-8">

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-purple-100">

        <h2 class="text-xl font-bold text-gray-800 mb-6">
            Ringkasan Hutang Aktif
        </h2>

        <div class="grid md:grid-cols-5 gap-6">

            <div>
                <p class="text-sm text-gray-500">
                    Nominal Hutang
                </p>

                <p class="font-bold text-lg text-[#5628C7]">
                    Rp{{ number_format($hutangAktif->jumlah_hutang ?? 0, 0, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Tanggal Pengajuan
                </p>

                <p class="font-semibold">
                    @if($hutangAktif)
                    {{ \Carbon\Carbon::parse($hutangAktif->tanggal_pengajuan)->translatedFormat('d F Y') }}
                    @else
                    -
                    @endif
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Jatuh Tempo
                </p>

                <p class="font-semibold text-red-500">
                   @if($hutangAktif)
                    {{ \Carbon\Carbon::parse($hutangAktif->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}
                @else
                    -
                @endif
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Metode
                </p>

                <p class="font-semibold">
                    @if($hutangAktif)
                    {{ ucfirst($hutangAktif->metode) }}
                @else
                    -
                @endif
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Status
                </p>

                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                   @if($hutangAktif)
                    {{ ucfirst($hutangAktif->status) }}
                @else
                    Belum Ada Hutang
                @endif
                </span>
            </div>

        </div>

    </div>

</div>
<!-- AKTIVITAS -->
<div class="mt-8">

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-purple-100">

        <div class="space-y-5">

            @forelse($aktivitas as $item)

            @php

            $icon = '📋';

            if($item->tipe == 'pembayaran'){
            $icon = '💳';
            }
            elseif($item->tipe == 'persetujuan'){
            $icon = '✅';
            }
            elseif($item->tipe == 'pencairan'){
            $icon = '💰';
            }

            @endphp

            <div class="flex items-start gap-4">

                <div
                    class="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center text-xl flex-shrink-0">

                    {{ $icon }}

                </div>

                <div class="flex-1">

                    <p class="font-semibold text-gray-800">
                        {{ $item->judul }}
                    </p>

                    <p class="text-gray-600 mt-1">
                        {{ $item->pesan }}
                    </p>

                    <p class="text-sm text-gray-400 mt-2">
                        {{ $item->created_at->diffForHumans() }}
                    </p>

                </div>

            </div>

            @empty

            <div class="text-center py-6 text-gray-500">

                Belum ada aktivitas terbaru

            </div>

            @endforelse

        </div>
    </div>

</div>
@endsection