@extends('layouts.admin')

@section('content')

<!-- HEADER -->
<div class="flex items-center justify-between mb-8">

    <div>
        <h1 class="text-3xl font-bold text-gray-800">Monitoring Hutang</h1>
        <p class="text-gray-400 text-sm mt-1">Pantau seluruh pengajuan dan status hutang agen Partner Pulsa.</p>
    </div>

</div>

<!-- STATISTIK -->
<div class="grid md:grid-cols-[1.3fr_1fr_1fr_1fr] gap-6 mb-8">

    <!-- Card 1: Total Piutang Dicairkan -->
    <div class="bg-gradient-to-r from-[#5628C7] to-purple-600 rounded-3xl p-6 shadow-sm text-white">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center">
                <i class="ti ti-cash text-2xl"></i>
            </div>
            <p class="text-sm font-bold uppercase tracking-wide text-white/80">Total Piutang</p>
        </div>
        <h2 class="text-4xl font-bold">Rp{{ number_format($totalPiutang, 0, ',', '.') }}</h2>
        <div class="border-t border-white/20 mt-4 pt-4">
            <p class="text-sm text-white/80">Sudah dicairkan ke agen</p>
        </div>
    </div>

    <!-- Card 2: Sudah Kembali -->
    <div class="bg-yellow-50 rounded-3xl p-6 border border-yellow-100 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-yellow-100 flex items-center justify-center">
                <i class="ti ti-circle-check text-2xl text-yellow-600"></i>
            </div>
            <p class="text-sm font-bold uppercase tracking-wide text-gray-500">Sudah Kembali</p>
        </div>
        <h2 class="text-3xl font-bold text-yellow-600">Rp{{ number_format($sudahKembali, 0, ',', '.') }}</h2>
        <p class="text-sm text-gray-500 mt-4 flex items-center gap-1">
            <i class="ti ti-trending-up text-sm"></i> Lunas dibayar agen
        </p>
    </div>

    <!-- Card 3: Belum Kembali -->
    <div class="bg-yellow-50 rounded-3xl p-6 border border-yellow-100 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-yellow-100 flex items-center justify-center">
                <i class="ti ti-clock-dollar text-2xl text-yellow-600"></i>
            </div>
            <p class="text-sm font-bold uppercase tracking-wide text-gray-500">Belum Kembali</p>
        </div>
        <h2 class="text-3xl font-bold text-yellow-600">Rp{{ number_format($belumKembali, 0, ',', '.') }}</h2>
        <p class="text-sm text-gray-500 mt-4 flex items-center gap-1">
            <i class="ti ti-loader text-sm"></i> Masih berjalan / terlambat
        </p>
    </div>

    <!-- Card 4: Terlambat -->
    <div class="bg-yellow-50 rounded-3xl p-6 border border-yellow-100 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-yellow-100 flex items-center justify-center">
                <i class="ti ti-alert-triangle text-2xl text-yellow-600"></i>
            </div>
            <p class="text-sm font-bold uppercase tracking-wide text-gray-500">Terlambat</p>
        </div>
        <h2 class="text-4xl font-bold text-yellow-600">{{ $jumlahTerlambat }}</h2>
        <p class="text-sm text-gray-500 mt-4 flex items-center gap-1">
            <i class="ti ti-alert-circle text-sm"></i> Perlu perhatian
        </p>
    </div>

</div>

<!-- TABEL -->
<div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm mb-8">

    <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

        <h2 class="text-xl font-bold text-gray-800">
            Data Monitoring Hutang
        </h2>

        <form method="GET" class="flex flex-wrap gap-3">

            <!-- Bulan -->
            <select
                name="bulan"
                class="border border-gray-300 rounded-xl px-4 py-2">
                <option value="">Semua Bulan</option>

                <option value="01" {{ request('bulan')=='01' ? 'selected' : '' }}>Januari</option>
                <option value="02" {{ request('bulan')=='02' ? 'selected' : '' }}>Februari</option>
                <option value="03" {{ request('bulan')=='03' ? 'selected' : '' }}>Maret</option>
                <option value="04" {{ request('bulan')=='04' ? 'selected' : '' }}>April</option>
                <option value="05" {{ request('bulan')=='05' ? 'selected' : '' }}>Mei</option>
                <option value="06" {{ request('bulan')=='06' ? 'selected' : '' }}>Juni</option>
                <option value="07" {{ request('bulan')=='07' ? 'selected' : '' }}>Juli</option>
                <option value="08" {{ request('bulan')=='08' ? 'selected' : '' }}>Agustus</option>
                <option value="09" {{ request('bulan')=='09' ? 'selected' : '' }}>September</option>
                <option value="10" {{ request('bulan')=='10' ? 'selected' : '' }}>Oktober</option>
                <option value="11" {{ request('bulan')=='11' ? 'selected' : '' }}>November</option>
                <option value="12" {{ request('bulan')=='12' ? 'selected' : '' }}>Desember</option>
            </select>

            <!-- Tahun -->
            <select
                name="tahun"
                class="border border-gray-300 rounded-xl px-4 py-2">
                <option value="">Semua Tahun</option>

                @for($i = date('Y'); $i >= 2024; $i--)
                    <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>

            <!-- Status -->
            <select
                name="status"
                class="border border-gray-300 rounded-xl px-4 py-2">
                <option value="all" {{ request('status')=='all' ? 'selected' : '' }}>Semua Status</option>
                <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                <option value="disetujui" {{ request('status')=='disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="berjalan" {{ request('status')=='berjalan' ? 'selected' : '' }}>Berjalan</option>
                <option value="terlambat" {{ request('status')=='terlambat' ? 'selected' : '' }}>Terlambat</option>
                <option value="lunas" {{ request('status')=='lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="ditolak" {{ request('status')=='ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>

            <!-- Cari -->
            <button
                type="submit"
                id="btnCariMonitoring"
                class="bg-purple-600 text-white px-4 py-2 rounded-xl">
                Cari
            </button>

            <!-- Reset -->
            <a
                href="{{ url()->current() }}"
                class="border border-gray-300 bg-red-600 text-white px-4 py-2 rounded-xl">
                Reset
            </a>

        </form>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full table-auto text-sm">

            <thead>
                <tr class="border-b">
                    <th class="text-left py-3">ID Pengajuan</th>
                    <th class="text-left py-3"> Nama Agen</th>
                    <th class="text-left py-3">Jumlah</th>
                    <th class="text-left py-3">Metode Pembayaran</th>
                    <th class="text-left py-3">Tanggal  Pengajuan</th>
                    <th class="text-left py-3">Status</th>
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

                        <!-- ID -->
                        <td class="py-4 text-gray-500 font-mono text-xs">
                            #{{ $h->id }}
                        </td>

                        <!-- Agen -->
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-purple-100 text-[#5628C7] text-xs font-semibold flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($h->agen->username, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $h->agen->username }}</p>
                                    <p class="text-sm text-gray-500">{{ $h->agen->id_agen_pp }}</p>
                                </div>
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

    <div class="flex justify-between items-center mt-6">

        <div class="text-sm text-gray-500">
            Menampilkan
            {{ $hutang->firstItem() }}
            -
            {{ $hutang->lastItem() }}
            dari
            {{ $hutang->total() }}
            data
        </div>

        {{ $hutang->links() }}

    </div>

</div>

@endsection