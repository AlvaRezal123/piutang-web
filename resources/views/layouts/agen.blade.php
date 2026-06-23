<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPAN</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f7f5ff]">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-72 bg-white border-r border-purple-100 shadow-sm">

        <!-- Logo -->
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
                        Partner Pulsa
                    </p>

                </div>

            </div>

        </div>

        <!-- Menu -->
        <div class="p-4">

            <p class="text-xs font-bold text-gray-400 uppercase mb-3">
                Menu Utama
            </p>

            <div class="space-y-2">

                <a
                    href="/dashboard-agen"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 rounded-xl transition">

                    🏠 Dashboard

                </a>

                <a
                    href="/hutang/create"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 rounded-xl transition">

                    💰 Ajukan Hutang

                </a>

                <a
                    href="/hutang-saya"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 rounded-xl transition">

                    📋 Hutang Saya

                </a>

                <a
                    href="/pembayaran"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 rounded-xl transition">

                    💳 Pembayaran

                </a>

                <a
                    href="/notifikasi"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 rounded-xl transition">

                    🔔 Notifikasi

                </a>

            </div>

            <div class="mt-8">

                <p class="text-xs font-bold text-gray-400 uppercase mb-3">
                    Akun
                </p>

                <a
                    href="/logout"
                    class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition">

                    🚪 Logout

                </a>

            </div>

        </div>

    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-8">

        @yield('content')

    </main>

</div>

<!-- Floating WhatsApp -->
<a
    href="https://wa.me/628997911040?text=Halo%20CS%20Partner%20Pulsa,%20saya%20membutuhkan%20bantuan%20terkait%20SIMPAN."
    target="_blank"
    class="fixed bottom-6 right-6 z-50">

    <div class="bg-green-500 hover:bg-green-600 text-white px-5 py-4 rounded-full shadow-2xl flex items-center gap-3 transition-all duration-300 hover:scale-105">

        <span class="text-2xl">
            💬
        </span>

        <div>

            <p class="text-xs">
                Butuh Bantuan?
            </p>

            <p class="font-semibold text-sm">
                Hubungi CS
            </p>

        </div>

    </div>

</a>

</body>
</html>