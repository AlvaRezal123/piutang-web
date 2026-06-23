<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPAN Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f7f5ff]">
    @php

$notifBelum =
    \App\Models\Notifikasi::where(
        'status_baca',
        'belum'
    )->count();

@endphp

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
   <aside class="w-72 bg-white border-r border-purple-100 shadow-lg sticky top-0 h-screen">

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

          <a
    href="/dashboard-admin"
    class="
    flex items-center gap-3 px-4 py-3 rounded-xl transition

    {{ request()->is('dashboard-admin')
        ? 'bg-purple-100 text-purple-700 font-semibold'
        : 'hover:bg-purple-50'
    }}
">
                🏠 Dashboard

            </a>

            <p class="text-xs font-bold text-gray-400 uppercase mt-8 mb-3">
                Manajemen Agen
            </p>

           <a
    href="/agen"
    class="
        flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200

        {{ request()->is('agen*')
            ? 'bg-purple-100 text-purple-700 font-semibold shadow-sm'
            : 'hover:bg-purple-50'
        }}
">

    👤 Validasi Agen

</a>

            <p class="text-xs font-bold text-gray-400 uppercase mt-8 mb-3">
                Transaksi
            </p>

          <a
    href="/admin/pencairan"
    class="
        flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200

        {{ request()->is('admin/pencairan*')
            ? 'bg-purple-100 text-purple-700 font-semibold shadow-sm'
            : 'hover:bg-purple-50'
        }}
">

    💰 Pencairan Saldo

</a>
          <a
    href="/admin/pembayaran"
    class="
        flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200

        {{ request()->is('admin/pembayaran*')
            ? 'bg-purple-100 text-purple-700 font-semibold shadow-sm'
            : 'hover:bg-purple-50'
        }}
">

    💳 Validasi Pembayaran

</a>

    <a
    href="/admin/notifikasi"
    class="
        flex items-center justify-between
        px-4 py-3
        rounded-xl
        transition-all duration-200

        {{ request()->is('admin/notifikasi*')
            ? 'bg-purple-100 text-purple-700 font-semibold shadow-sm'
            : 'hover:bg-purple-50'
        }}
    ">

    <div class="flex items-center gap-3">

        <span>🔔</span>

        <span>
            Notifikasi
        </span>

    </div>

    <span
        id="badgeNotif"
        class="
            bg-red-500
            text-white
            text-xs
            font-bold
            min-w-[24px]
            text-center
            px-2 py-1
            rounded-full

            {{ $notifBelum == 0 ? 'hidden' : '' }}
        ">

        {{ $notifBelum }}

    </span>

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
<script>

let notifLama =
    {{ $notifBelum }};
setInterval(() => {

    fetch('/jumlah-notifikasi')

        .then(response => response.json())

        .then(data => {

            let badge =
                document.getElementById(
                    'badgeNotif'
                );

            if(data.jumlah > 0){

                badge.innerText =
                    data.jumlah;

                badge.classList.remove(
                    'hidden'
                );

            }else{

                badge.classList.add(
                    'hidden'
                );

            }

            // PLAY SOUND
            if(
                data.jumlah >
                notifLama
            ){

                document
                    .getElementById(
                        'notifSound'
                    )
                    .play();

            }

            notifLama =
                data.jumlah;

        });

}, 10000);

</script>
<audio id="notifSound">

    <source
        src="/sounds/notif.mp3"
        type="audio/mpeg">

</audio>
</body>
</html>