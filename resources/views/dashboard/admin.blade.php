<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIMPAN</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f7f5ff]">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-72 bg-white border-r border-purple-100 shadow-sm">

        <div class="p-6 border-b border-purple-100">

            <div class="flex items-center gap-3">

                <img
                    src="{{ asset('images/logo-partnerpulsa.png') }}"
                    alt="Partner Pulsa"
                    class="w-12 h-12 rounded-xl">

                <div>

                    <h1 class="font-black text-xl text-[#5628C7]">
                        SIMPAN
                    </h1>

                    <p class="text-xs text-gray-500">
                        Admin Panel
                    </p>

                </div>

            </div>

        </div>

        <div class="p-4">

            <p class="text-xs font-bold text-gray-400 uppercase mb-3">
                Dashboard
            </p>

            <div class="space-y-2">

                <a
                    href="/dashboard-admin"
                    class="flex items-center gap-3 px-4 py-3 bg-purple-50 text-[#5628C7] rounded-xl font-semibold">

                    🏠 Dashboard

                </a>

            </div>

            <p class="text-xs font-bold text-gray-400 uppercase mt-8 mb-3">
                Manajemen Agen
            </p>

            <a
                href="/agen"
                class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 rounded-xl transition">

                👤 Validasi Agen

            </a>

            <p class="text-xs font-bold text-gray-400 uppercase mt-8 mb-3">
                Transaksi
            </p>

            <div class="space-y-2">

                <a
                    href="/admin/pencairan"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 rounded-xl transition">

                    💰 Pencairan Saldo

                </a>

                <a
                    href="/admin/pembayaran"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 rounded-xl transition">

                    💳 Validasi Pembayaran

                </a>
                <a
                    href="/admin/notifikasi"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 rounded-xl transition">

                    🔔 Notifikasi

                </a>
            </div>

            <p class="text-xs font-bold text-gray-400 uppercase mt-8 mb-3">
                Akun
            </p>

            <a
                href="/logout"
                class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition">

                🚪 Logout

            </a>

        </div>

    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-8">

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

    </main>

</div>

</body>
</html>