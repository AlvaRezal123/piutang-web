@extends('layouts.admin')

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
                    {{ ucfirst($hutang->metode) }}
                </span>
                <span class="text-white/40">·</span>
                <span class="flex items-center gap-1.5 text-sm text-white/80">
                    <i class="ti ti-calendar"></i>
                    {{ \Carbon\Carbon::parse($hutang->tanggal_pengajuan)->format('d M Y') }}
                </span>
                @if($hutang->tanggal_jatuh_tempo)
                <span class="text-white/40">·</span>
                <span class="flex items-center gap-1.5 text-sm text-white/80">
                    <i class="ti ti-clock"></i>
                    Jatuh tempo {{ \Carbon\Carbon::parse($hutang->tanggal_jatuh_tempo)->format('d M Y') }}
                </span>
                @endif
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
                    <i class="ti ti-shield text-[#5628C7] text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Status Kredit</p>
                    <p class="text-sm font-semibold text-gray-800">{{ ucfirst($hutang->agen->status_kredit ?? '-') }}</p>
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
                    <i class="ti ti-wallet text-gray-300 text-lg"></i>
                    <p class="text-sm text-gray-500">Sisa Hutang</p>
                </div>
                <p class="text-sm font-bold text-gray-800">Rp{{ number_format($hutang->sisa_hutang, 0, ',', '.') }}</p>
            </div>
            <div class="flex items-center justify-between py-3 border-b border-gray-50">
                <div class="flex items-center gap-3">
                    <i class="ti ti-file-description text-gray-300 text-lg"></i>
                    <p class="text-sm text-gray-500">Metode Pembayaran</p>
                </div>
                <p class="text-sm font-semibold text-gray-800">{{ ucfirst($hutang->metode) }}
                    @if($hutang->lama_tempo) · {{ $hutang->lama_tempo }} @endif
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
                    <i class="ti ti-clock text-gray-300 text-lg"></i>
                    <p class="text-sm text-gray-500">Jatuh Tempo</p>
                </div>
                <p class="text-sm font-semibold text-red-500">
                    {{ $hutang->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($hutang->tanggal_jatuh_tempo)->format('d M Y') : 'Belum ditentukan' }}
                </p>
            </div>
        </div>
    </div>

</div>

<!-- DETAIL CICILAN -->
@if($hutang->metode == 'cicil' && $hutang->cicilan->count())
<div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
            Detail Cicilan
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Cicilan</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Nominal</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Jatuh Tempo</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hutang->cicilan as $c)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-800">Cicilan ke-{{ $c->cicilan_ke }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">Rp{{ number_format($c->jumlah_cicilan, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($c->tanggal_jatuh_tempo)->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        @if($c->status == 'lunas')
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-circle-check text-xs"></i> Lunas
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-clock text-xs"></i> Belum Dibayar
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
<div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-green-500"></span>
            Riwayat Pembayaran
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Tanggal</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Jumlah</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Metode</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Alasan Penolakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembayaran as $p)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($p->tanggal_pembayaran)->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">Rp{{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $p->bank_pengirim ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($p->status == 'disetujui')
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-circle-check text-xs"></i> Disetujui
                            </span>
                        @elseif($p->status == 'ditolak')
                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-x text-xs"></i> Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-clock text-xs"></i> Pending
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $p->alasan_penolakan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-12 text-gray-400">
                        <i class="ti ti-inbox text-4xl block mb-2 text-gray-200"></i>
                        Belum ada riwayat pembayaran
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- RIWAYAT HUTANG -->
<div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            Riwayat Hutang Agen
        </h2>
        <span class="text-xs text-gray-400">{{ count($riwayat) }} transaksi</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Tanggal</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Jumlah</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Metode</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $r)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($r->tanggal_pengajuan)->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">Rp{{ number_format($r->jumlah_hutang, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <i class="ti ti-{{ $r->metode == 'cicil' ? 'calendar-repeat' : 'cash' }} text-gray-300 text-sm"></i>
                            {{ ucfirst($r->metode) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($r->status == 'pending')
                            <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-clock text-xs"></i> Pending
                            </span>
                        @elseif($r->status == 'disetujui')
                            <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-check text-xs"></i> Disetujui
                            </span>
                        @elseif($r->status == 'berjalan')
                            <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-loader text-xs"></i> Berjalan
                            </span>
                        @elseif($r->status == 'lunas')
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-circle-check text-xs"></i> Lunas
                            </span>
                        @elseif($r->status == 'ditolak')
                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="ti ti-x text-xs"></i> Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                                {{ ucfirst($r->status) }}
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-12 text-gray-400">
                        <i class="ti ti-inbox text-4xl block mb-2 text-gray-200"></i>
                        Belum ada riwayat hutang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- TOMBOL KEMBALI -->
<div>
    <a href="/admin/pengajuan-hutang"
       class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition-colors">
        <i class="ti ti-arrow-left text-sm"></i> Kembali
    </a>
</div>

@endsection