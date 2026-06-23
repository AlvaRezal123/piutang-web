<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPAN - Owner</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
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
                        Owner Panel
                    </p>

                </div>

            </div>

        </div>

        <div class="p-4">

            <p class="text-xs font-bold text-gray-400 uppercase mb-3">
                Dashboard
            </p>

            <a
                href="/dashboard-owner"
                class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 rounded-xl transition">

                🏠 Dashboard

            </a>

            <p class="text-xs font-bold text-gray-400 uppercase mt-8 mb-3">
                Approval
            </p>

            <a
                href="/owner/hutang"
                class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 rounded-xl transition">

                📋 Approval Pengajuan

            </a>

            <a
                href="/admin/hutang"
                class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 rounded-xl transition">

                📊 Monitoring Hutang

            </a>

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

        @yield('content')

    </main>

</div>

</body>
</html>