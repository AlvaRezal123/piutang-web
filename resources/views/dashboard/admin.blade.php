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
        <div class="grid md:grid-cols-4 gap-6 mt-8">

            <!-- Agen Pending -->
            <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

                <div class="text-4xl mb-4">
                    👤
                </div>

                <p class="text-gray-500 text-sm">
                    Agen Pending
                </p>

                <h2 class="text-3xl font-black text-orange-500 mt-2">
                 {{ $agenPending }}
                </h2>

            </div>

            <!-- Pencairan -->
            <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

                <div class="text-4xl mb-4">
                    💰
                </div>

                <p class="text-gray-500 text-sm">
                    Menunggu Pencairan
                </p>

                <h2 class="text-3xl font-black text-blue-500 mt-2">
                  {{ $pencairanPending }}
                </h2>

            </div>

            <!-- Pembayaran -->
            <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

                <div class="text-4xl mb-4">
                    💳
                </div>

                <p class="text-gray-500 text-sm">
                    Pembayaran Pending
                </p>

                <h2 class="text-3xl font-black text-red-500 mt-2">
                  {{ $pembayaranPending }}
                </h2>

            </div>

            <!-- Agen Aktif -->
            <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

                <div class="text-4xl mb-4">
                    ✅
                </div>

                <p class="text-gray-500 text-sm">
                    Agen Aktif
                </p>

                <h2 class="text-3xl font-black text-green-500 mt-2">
                    {{ $agenAktif }}
                </h2>

            </div>

        </div>
<!-- GRAFIK -->
<div class="grid md:grid-cols-2 gap-6 mt-8">

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

        <!-- AKTIVITAS -->
<div class="mt-8 bg-white rounded-3xl p-6 border border-purple-100 shadow-sm">

 <div class="flex justify-between items-center mb-6">

    <h2 class="text-xl font-bold text-gray-800">
        📋 Aktivitas Terbaru
    </h2>

</div>

    <div class="space-y-5">

        @forelse($aktivitas as $item)

        <div class="flex items-start gap-4">

            <div
                class="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center text-xl flex-shrink-0">

                {{ $item['icon'] }}

            </div>

            <div class="flex-1">

                <p class="font-semibold text-gray-800">

                    {{ $item['pesan'] }}

                </p>

                <p class="text-sm text-gray-500 mt-1">

                    {{ $item['tanggal']->diffForHumans() }}

                </p>

            </div>

        </div>

        @empty

        <div class="text-center py-8 text-gray-500">

            Belum ada aktivitas

        </div>

        @endforelse

    </div>

</div>

@endsection