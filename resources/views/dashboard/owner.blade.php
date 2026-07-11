@extends('layouts.owner')

@section('content')

<!-- GREETING BANNER -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#5628C7] to-[#7B52E0] p-8 text-white mb-8 flex items-center justify-between">

    <div>
        <h1 class="text-2xl font-bold">Halo, Owner </h1>
      <div class="mt-4 flex flex-wrap gap-3">

    <span class="bg-white/15 px-4 py-2 rounded-xl text-sm">
        📝 {{ $pengajuanHariIni }} Pengajuan Hari Ini
    </span>

    <span class="bg-white/15 px-4 py-2 rounded-xl text-sm">
        ⚠️ {{ $pending }} Menunggu Persetujuan
    </span>

    <span class="bg-white/15 px-4 py-2 rounded-xl text-sm">
        🚨 {{ $terlambat }} Terlambat
    </span>

</div>
    </div>

    <div class="flex items-center gap-4 flex-shrink-0">

        <button
            type="button"
            onclick="openPasswordModalOwner()"
            class="flex items-center gap-2 bg-white/15 hover:bg-white/25 border border-white/25 px-4 py-2.5 rounded-xl text-sm font-semibold transition">
            <i class="ti ti-lock text-base"></i>
            Ganti Password
        </button>

        @if($pending > 0)
        <div class="bg-white/15 border border-white/25 rounded-2xl px-6 py-4 text-center">
            <p class="text-3xl font-bold">{{ $pending }}</p>
            <p class="text-xs text-white/70 mt-1">Perlu ditinjau</p>
        </div>
        @endif

    </div>

</div>

<!-- STATISTIK -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm">
        <div class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center mb-4">
            <i class="ti ti-file-text text-[#5628C7] text-lg"></i>
        </div>
        <p class="text-xs text-gray-400 mb-1">Total pengajuan</p>
        <p class="text-3xl font-bold text-[#5628C7]">{{ $totalPengajuan }}</p>
        <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
            <i class="ti ti-minus text-xs"></i> Sepanjang masa
        </p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm">
        <div class="w-9 h-9 rounded-xl bg-yellow-100 flex items-center justify-center mb-4">
            <i class="ti ti-clock text-yellow-600 text-lg"></i>
        </div>
        <p class="text-xs text-gray-400 mb-1">Menunggu persetujuan</p>
        <p class="text-3xl font-bold text-yellow-600">{{ $pending }}</p>
        <p class="text-xs text-yellow-500 mt-2 flex items-center gap-1">
            <i class="ti ti-alert-circle text-xs"></i> Perlu ditinjau segera
        </p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm">
        <div class="w-9 h-9 rounded-xl bg-green-100 flex items-center justify-center mb-4">
            <i class="ti ti-circle-check text-green-600 text-lg"></i>
        </div>
        <p class="text-xs text-gray-400 mb-1">Disetujui</p>
        <p class="text-3xl font-bold text-green-600">{{ $disetujui }}</p>
        <p class="text-xs text-green-500 mt-2 flex items-center gap-1">
            <i class="ti ti-trending-up text-xs"></i> Aktif berjalan
        </p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-purple-100 shadow-sm">
        <div class="w-9 h-9 rounded-xl bg-red-100 flex items-center justify-center mb-4">
            <i class="ti ti-circle-x text-red-500 text-lg"></i>
        </div>
        <p class="text-xs text-gray-400 mb-1">Ditolak</p>
        <p class="text-3xl font-bold text-red-500">{{ $ditolak }}</p>
        <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
            <i class="ti ti-minus text-xs"></i> Total ditolak
        </p>
    </div>

</div>

<!-- BAWAH: AKTIVITAS + ANTRIAN -->
<div class="grid md:grid-cols-2 gap-5">

    <!-- AKTIVITAS TERBARU -->
    <div class="bg-white rounded-2xl p-6 border border-purple-100 shadow-sm">

        <h3 class="flex items-center gap-2 text-sm font-semibold text-gray-800 mb-5">
            <span class="w-2 h-2 rounded-full bg-[#5628C7] inline-block"></span>
            Aktivitas terbaru
        </h3>

        <div class="space-y-1">
            @forelse($aktivitas as $a)

            <div class="flex items-start gap-3 py-3 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">

                <div class="w-8 h-8 rounded-full bg-purple-100 text-[#5628C7] flex items-center justify-center text-xs font-semibold flex-shrink-0">
                    {{ strtoupper(substr($a->agen->username, 0, 2)) }}
                </div>

                <div class="flex-1 min-w-0">
                  <p class="text-sm text-gray-700 leading-snug">

@if($a->status == 'pending')

📝
<span class="font-medium">{{ $a->agen->username }}</span>
mengajukan pinjaman
<span class="font-semibold text-[#5628C7]">
Rp{{ number_format($a->jumlah_hutang,0,',','.') }}
</span>

@elseif($a->status == 'disetujui')

✅
Pengajuan Hutang Agen
<span class="font-medium">{{ $a->agen->username }}</span>
telah disetujui.

@elseif($a->status == 'ditolak')

❌
Pengajuan Hutang Agen
<span class="font-medium">{{ $a->agen->username }}</span>
ditolak.

@elseif($a->status == 'berjalan')

🔵
Pengajuan Hutang Agen
<span class="font-medium">{{ $a->agen->username }}</span>
sedang berjalan.

@elseif($a->status == 'lunas')

🎉
Hutang Agen
<span class="font-medium">{{ $a->agen->username }}</span>
telah lunas.

@elseif($a->status == 'terlambat')

⚠️
<span class="font-medium">{{ $a->agen->username }}</span>
terlambat membayar.

@endif

</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $a->created_at->diffForHumans() }}</p>
                </div>

            </div>

            @empty

            <div class="text-center py-8">
                <i class="ti ti-inbox text-3xl text-gray-300 block mb-2"></i>
                <p class="text-sm text-gray-400">Belum ada aktivitas</p>
            </div>

            @endforelse
        </div>

    </div>

    <!-- ANTRIAN APPROVAL -->
    <div class="bg-white rounded-2xl p-6 border border-purple-100 shadow-sm">

      <h2 class="flex items-center justify-between mb-5">

    <div class="flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-yellow-500"></span>

        <p class="text-sm font-semibold text-gray-800">
            Antrian Approval
        </p>
    </div>

    <span
        class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full">
        {{ $pending }} Pending
    </span>

</h2>

        <div class="space-y-1">
            @forelse($antrianPending as $h)

            <div class="flex items-center gap-3 py-3 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">

                <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center text-xs font-semibold flex-shrink-0">
                    {{ strtoupper(substr($h->agen->username, 0, 2)) }}
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $h->agen->username }}</p>
                    <p class="text-xs font-semibold text-gray-700">
    Rp{{ number_format($h->jumlah_hutang,0,',','.') }}
</p>

<p class="text-xs text-gray-400 mt-1">
    {{ ucfirst($h->metode) }}
</p>

<p class="text-xs text-gray-400">
    Diajukan {{ $h->created_at->format('d M Y') }}
</p>
                </div>

               @php
    $jamMenunggu = $h->created_at->diffInHours(now());
@endphp

@if($jamMenunggu < 24)

<span
class="flex-shrink-0 text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-700">

Baru

</span>

@else

<span
class="flex-shrink-0 text-xs font-semibold px-3 py-1 rounded-full bg-red-100 text-red-700">

Mendesak

</span>

@endif

            </div>

            @empty

            <div class="text-center py-8">
                <i class="ti ti-circle-check text-3xl text-green-300 block mb-2"></i>
                <p class="text-sm text-gray-400">Semua sudah ditinjau</p>
            </div>

            @endforelse
        </div>

        @if($pending > 0)
        <a href="/owner/hutang" class="mt-5 w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-[#5628C7] text-white text-sm font-semibold hover:bg-[#4b22b0] transition-colors">
            Tinjau semua <i class="ti ti-arrow-right text-sm"></i>
        </a>
        @endif

    </div>

</div>

<!-- MODAL GANTI PASSWORD OWNER -->
<div id="modalPasswordOwner" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden flex flex-col">

        <!-- HEADER GRADIENT -->
        <div class="relative px-8 py-6 text-white flex-shrink-0" style="background: linear-gradient(135deg, #5628C7 0%, #7B52E0 100%);">
            <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10 bg-white"></div>
            <div class="absolute -right-8 bottom-0 w-16 h-16 rounded-full opacity-10 bg-white"></div>
            <div class="relative z-10 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                        <i class="ti ti-lock text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black leading-tight">Ganti Password</h2>
                        <p class="text-white/70 text-xs mt-0.5">Pastikan password baru kamu aman</p>
                    </div>
                </div>
                <button type="button" onclick="closePasswordModalOwner()" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/25 flex items-center justify-center transition flex-shrink-0">
                    <i class="ti ti-x text-lg"></i>
                </button>
            </div>
        </div>

        <!-- BODY -->
        <div class="px-8 py-7">

            @if(session('error'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-sm text-red-600 font-medium">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-sm text-green-600 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form id="formGantiPasswordOwner" action="/owner/update-password" method="POST">
                @csrf

                <div class="space-y-4">

                    <!-- Password Lama -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            Password Lama
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="ti ti-lock text-[#5628C7]"></i>
                            </div>
                            <input
                                type="password"
                                name="password_lama"
                                placeholder="Masukkan password lama"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm font-medium text-gray-800 transition focus:outline-none focus:ring-2 focus:ring-[#5628C7]/30 focus:border-[#5628C7] focus:bg-white">
                        </div>
                             @error('password_lama')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            Password Baru
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="ti ti-lock text-[#5628C7]"></i>
                            </div>
                            <input
                                type="password"
                                name="password_baru"
                                placeholder="Minimal 6 karakter"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm font-medium text-gray-800 transition focus:outline-none focus:ring-2 focus:ring-[#5628C7]/30 focus:border-[#5628C7] focus:bg-white">
                        </div>
                           @error('password_baru')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="ti ti-circle-check text-[#5628C7]"></i>
                            </div>
                            <input
                                type="password"
                                name="password_baru_confirmation"
                                placeholder="Ulangi password baru"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm font-medium text-gray-800 transition focus:outline-none focus:ring-2 focus:ring-[#5628C7]/30 focus:border-[#5628C7] focus:bg-white">
                        </div>
                         @error('password_baru_confirmation')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-8 pt-5 border-t border-gray-100">
                    <button
                        type="button"
                        onclick="closePasswordModalOwner()"
                        class="px-5 py-3 rounded-xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="flex items-center gap-2 px-5 py-3 rounded-xl text-white text-sm font-semibold shadow-lg shadow-purple-200 transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #5628C7 0%, #7B52E0 100%);">
                        <i class="ti ti-check"></i>
                        Simpan Password
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>

<script>
function openPasswordModalOwner() {
    document.getElementById('modalPasswordOwner').classList.remove('hidden');
    document.getElementById('modalPasswordOwner').classList.add('flex');
}
function closePasswordModalOwner() {
    document.getElementById('modalPasswordOwner').classList.remove('flex');
    document.getElementById('modalPasswordOwner').classList.add('hidden');
}
</script>

@if($errors->any() || session('error') || session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        openPasswordModalOwner();
    });
</script>
@endif

@endsection