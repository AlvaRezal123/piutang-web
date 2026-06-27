@extends('layouts.admin')

@section('content')

        <!-- HEADER -->
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-purple-100">

            <h1 class="text-3xl font-black text-gray-800">
                Halo Admin 👋
            </h1>

            <p class="text-gray-500 mt-2">
                Kelola validasi agen, pencairan saldo, dan pembayaran agen Partner Pulsa.
            </p>

        </div>

 <!-- CARD STATISTIK -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">

    <!-- Agen Pending -->
    <div class="relative overflow-hidden rounded-2xl p-5 shadow-sm" style="background: linear-gradient(135deg, #6C5CE7 0%, #5628C7 100%);">
        <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10 bg-white"></div>
        <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10 bg-white"></div>
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background: rgba(251,146,60,0.25);">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" style="color: #FB923C;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
        </div>
        <p class="text-white/70 text-xs font-medium uppercase tracking-wide">Agen Pending</p>
        <h2 class="text-4xl font-black text-white mt-1">{{ $agenPending }}</h2>
        <p class="text-white/60 text-xs mt-2">Menunggu persetujuan</p>
        <div class="absolute bottom-0 left-0 right-0 h-1 rounded-b-2xl" style="background: #FB923C;"></div>
    </div>

    <!-- Menunggu Pencairan -->
    <div class="relative overflow-hidden rounded-2xl p-5 shadow-sm" style="background: linear-gradient(135deg, #6C5CE7 0%, #5628C7 100%);">
        <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10 bg-white"></div>
        <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10 bg-white"></div>
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background: rgba(56,189,248,0.25);">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" style="color: #38BDF8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <p class="text-white/70 text-xs font-medium uppercase tracking-wide">Menunggu Pencairan</p>
        <h2 class="text-4xl font-black text-white mt-1">{{ $pencairanPending }}</h2>
        <p class="text-white/60 text-xs mt-2">Perlu diproses</p>
        <div class="absolute bottom-0 left-0 right-0 h-1 rounded-b-2xl" style="background: #38BDF8;"></div>
    </div>

    <!-- Pembayaran Pending -->
    <div class="relative overflow-hidden rounded-2xl p-5 shadow-sm" style="background: linear-gradient(135deg, #6C5CE7 0%, #5628C7 100%);">
        <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10 bg-white"></div>
        <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10 bg-white"></div>
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background: rgba(248,113,113,0.25);">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" style="color: #F87171;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
            </svg>
        </div>
        <p class="text-white/70 text-xs font-medium uppercase tracking-wide">Pembayaran Pending</p>
        <h2 class="text-4xl font-black text-white mt-1">{{ $pembayaranPending }}</h2>
        <p class="text-white/60 text-xs mt-2">Belum divalidasi</p>
        <div class="absolute bottom-0 left-0 right-0 h-1 rounded-b-2xl" style="background: #F87171;"></div>
    </div>

    <!-- Agen Aktif -->
    <div class="relative overflow-hidden rounded-2xl p-5 shadow-sm" style="background: linear-gradient(135deg, #6C5CE7 0%, #5628C7 100%);">
        <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10 bg-white"></div>
        <div class="absolute -right-2 bottom-0 w-16 h-16 rounded-full opacity-10 bg-white"></div>
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background: rgba(74,222,128,0.25);">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" style="color: #4ADE80;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <p class="text-white/70 text-xs font-medium uppercase tracking-wide">Agen Aktif</p>
        <h2 class="text-4xl font-black text-white mt-1">{{ $agenAktif }}</h2>
        <p class="text-white/60 text-xs mt-2">Terdaftar & aktif</p>
        <div class="absolute bottom-0 left-0 right-0 h-1 rounded-b-2xl" style="background: #4ADE80;"></div>
    </div>

</div>

        <!-- QUICK MENU -->
        <div class="grid md:grid-cols-3 gap-6 mt-8">

            <!-- Validasi Agen -->
            <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

                <h2 class="font-bold text-lg text-gray-800">
                    👤 Validasi Agen
                </h2>

                <p class="text-gray-500 mt-2">
                    Periksa dan validasi pendaftaran agen baru.
                </p>

                <a
                    href="/agen"
                    class="inline-block mt-5 bg-[#5628C7] text-white px-5 py-3 rounded-xl font-semibold">

                    Buka Menu →

                </a>

            </div>

            <!-- Pencairan -->
            <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

                <h2 class="font-bold text-lg text-gray-800">
                    💰 Pencairan Saldo
                </h2>

                <p class="text-gray-500 mt-2">
                    Kelola pencairan hutang yang telah disetujui owner.
                </p>

                <a
                    href="/admin/pencairan"
                    class="inline-block mt-5 bg-[#5628C7] text-white px-5 py-3 rounded-xl font-semibold">

                    Buka Menu →

                </a>

            </div>

            <!-- Pembayaran -->
            <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

                <h2 class="font-bold text-lg text-gray-800">
                    💳 Validasi Pembayaran
                </h2>

                <p class="text-gray-500 mt-2">
                    Validasi bukti pembayaran yang dikirim agen.
                </p>

                <a
                    href="/admin/pembayaran"
                    class="inline-block mt-5 bg-[#5628C7] text-white px-5 py-3 rounded-xl font-semibold">

                    Buka Menu →

                </a>

            </div>

        </div>

      <!-- AKTIVITAS & PENGAJUAN HUTANG -->
<div class="grid md:grid-cols-2 gap-6 mt-8">

    <!-- AKTIVITAS TERBARU -->
    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-xl bg-purple-100 flex items-center justify-center text-sm">📋</div>
            <h2 class="text-lg font-bold text-gray-800">Aktivitas Terbaru</h2>
        </div>

        <div class="space-y-4">
            @forelse($aktivitas as $item)
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 flex items-center justify-center text-base flex-shrink-0">
                    {{ $item['icon'] }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800 leading-snug">{{ $item['pesan'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $item['tanggal']->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">Belum ada aktivitas</p>
            @endforelse
        </div>

    </div>

    <!-- PENGAJUAN HUTANG TERBARU -->
    <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-xl bg-purple-100 flex items-center justify-center text-sm">📝</div>
            <h2 class="text-lg font-bold text-gray-800">Pengajuan Hutang Terbaru</h2>
        </div>

        <div class="space-y-3">
            @forelse($pengajuanHutang as $h)
            <div class="flex items-center justify-between gap-3 p-3 rounded-2xl hover:bg-gray-50 transition">

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#5628C7] flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($h->agen->username, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $h->agen->username }}</p>
                        <p class="text-xs text-gray-400">
                            Rp{{ number_format($h->jumlah_hutang, 0, ',', '.') }}
                            &middot;
                            {{ $h->metode == 'cash' ? 'Bayar Penuh' : 'Cicilan ' . $h->lama_tempo }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col items-end gap-1">
                    @if($h->status == 'pending')
                        <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 border border-yellow-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 inline-block"></span> Pending
                        </span>
                    @elseif($h->status == 'disetujui')
                        <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span> Disetujui
                        </span>
                    @elseif($h->status == 'berjalan')
                        <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 border border-green-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Berjalan
                        </span>
                    @elseif($h->status == 'lunas')
                        <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 border border-gray-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span> Lunas
                        </span>
                    @elseif($h->status == 'terlambat')
                        <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 border border-red-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span> Terlambat
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 border border-red-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span> Ditolak
                        </span>
                    @endif
                    <p class="text-xs text-gray-400">
                        {{ \Carbon\Carbon::parse($h->tanggal_pengajuan)->diffForHumans() }}
                    </p>
                </div>

            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">Belum ada pengajuan hutang</p>
            @endforelse
        </div>

     <a href="/admin/pengajuan-hutang" class="mt-5 flex items-center justify-center gap-1.5 text-sm text-[#5628C7] font-semibold hover:underline pt-4 border-t border-gray-100">
    Lihat semua pengajuan
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
    </svg>
</a>

    </div>

</div>

@endsection