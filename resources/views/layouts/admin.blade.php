<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPAN Admin</title>
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap');

        * { font-family: 'Inter', sans-serif; }

        .sidebar-bg {
            background: linear-gradient(160deg, #3b1fa3 0%, #5628C7 40%, #7c3aed 100%);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 12px;
            transition: all 0.2s ease;
            color: rgba(255,255,255,0.75);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.12);
            color: #ffffff;
            transform: translateX(2px);
        }

        .nav-link.active {
            background: rgba(255,255,255,0.18);
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .nav-link.danger {
            color: rgba(255, 160, 160, 0.85);
        }

        .nav-link.danger:hover {
            background: rgba(255, 80, 80, 0.18);
            color: #ffb3b3;
        }

        .section-label {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            padding: 0 14px;
            margin-top: 24px;
            margin-bottom: 6px;
        }

        .sidebar-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 6px 0;
        }

        .logo-box {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }

        .logo-title {
            font-size: 1.25rem;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        .logo-sub {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.5);
            margin-top: 1px;
        }

        .badge-notif {
            background: #ff4d4d;
            color: white;
            font-size: 0.65rem;
            font-weight: 800;
            min-width: 20px;
            text-align: center;
            padding: 2px 6px;
            border-radius: 999px;
            box-shadow: 0 0 0 2px rgba(255,255,255,0.2);
        }

        .nav-link-between {
            justify-content: space-between;
        }

        .icon-wrap {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .nav-link.active .icon-wrap {
            background: rgba(255,255,255,0.2);
        }
    </style>
</head>

@php
    $notifBelum =
        \App\Models\Notifikasi::where('id_user', session('id_user'))
        ->where('status_baca', 'belum')
        ->count();
@endphp

<body class="bg-[#f7f5ff]" data-notif-belum="{{ $notifBelum }}">

<div class="flex min-h-screen">

    <!-- OVERLAY (khusus mobile, muncul di belakang sidebar saat dibuka) -->
    <div
        id="sidebarOverlay"
        onclick="closeSidebar()"
        class="hidden fixed inset-0 bg-black/50 z-40 md:hidden">
    </div>

    <!-- SIDEBAR -->
    <aside
        id="sidebar"
        class="w-64 sidebar-bg fixed md:sticky top-0 left-0 h-screen flex flex-col shadow-2xl z-50
               -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">

        <!-- Logo + tombol tutup (mobile) -->
        <div class="logo-box flex items-center justify-between gap-3">

            <div class="flex items-center gap-3">
                <img
                    src="{{ asset('images/logo-partnerpulsa.png') }}"
                    alt="Partner Pulsa"
                    class="w-11 h-11 rounded-xl shadow-lg ring-2 ring-white/20">
                <div>
                    <h1 class="logo-title">SIMPAN</h1>
                    <p class="logo-sub">Admin Panel</p>
                </div>
            </div>

            <button
                type="button"
                onclick="closeSidebar()"
                class="md:hidden text-white/70 hover:text-white w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10 transition">
                <i class="ti ti-x text-xl"></i>
            </button>

        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 overflow-y-auto">

            <!-- Dashboard -->
            <div class="section-label">Dashboard</div>

            <a href="/dashboard-admin"
               class="nav-link {{ request()->is('dashboard-admin') ? 'active' : '' }}">
                <span class="icon-wrap">
                <i class="ti ti-home"></i>
            </span>
                Dashboard
            </a>

            <!-- Manajemen Agen -->
            <div class="section-label">Manajemen Agen</div>

            <a href="/agen"
               class="nav-link {{ request()->is('agen*') ? 'active' : '' }}">
                <span class="icon-wrap">
                <i class="ti ti-user-check"></i>
                </span>
                Validasi Agen
            </a>

            <!-- Transaksi -->
            <div class="section-label">Transaksi</div>

            <a href="/admin/pencairan"
               class="nav-link {{ request()->is('admin/pencairan*') ? 'active' : '' }}">
                <span class="icon-wrap">
                    <i class="ti ti-cash"></i>
                </span>
                Pencairan Saldo
            </a>
                <a href="/admin/monitoring-hutang"
        class="nav-link {{ request()->is('admin/monitoring-hutang') ? 'active' : '' }}">
           <span class="icon-wrap">
                <i class="ti ti-cash"></i>
            </span>
            Monitoring Hutang
        </a>

        <a href="/admin/periode-cicilan"
   class="nav-link {{ request()->is('admin/periode-cicilan*') ? 'active' : '' }}">

    <span class="icon-wrap">

        <i class="ti ti-calendar-month"></i>

    </span>

    Periode Cicilan

</a>

            <a href="/admin/pembayaran"
               class="nav-link {{ request()->is('admin/pembayaran*') ? 'active' : '' }}">
               <span class="icon-wrap">
                <i class="ti ti-credit-card"></i>
            </span>
                Validasi Pembayaran
            </a>

            

            

            <a href="/admin/notifikasi"
               class="nav-link nav-link-between {{ request()->is('admin/notifikasi*') ? 'active' : '' }}">
                <div class="flex items-center gap-[10px]">
                    <span class="icon-wrap">
                        <i class="ti ti-bell"></i>
                    </span>
                    <span>Notifikasi</span>
                </div>
                <span
                    id="badgeNotif"
                    class="badge-notif {{ $notifBelum == 0 ? 'hidden' : '' }}">
                    {{ $notifBelum }}
                </span>
            </a>

            <!-- Akun -->
            <div class="section-label">Akun</div>

            <a href="/logout" class="nav-link danger">
                <span class="icon-wrap" style="background:rgba(255,100,100,0.15);">
                <i class="ti ti-logout"></i>
                  </span>
                Logout
            </a>

        </nav>

        <!-- Footer hint -->
        <div class="px-5 py-4 border-t border-white/10">
            <p class="text-[10px] text-white/30 text-center">© 2026 SIMPAN · Partner Pulsa</p>
        </div>

    </aside>

    <!-- CONTENT -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- TOPBAR (khusus mobile, buat tombol buka sidebar) -->
        <header class="md:hidden sticky top-0 z-30 bg-white border-b border-purple-100 px-4 py-3 flex items-center gap-3 shadow-sm">

            <button
                type="button"
                onclick="openSidebar()"
                class="w-9 h-9 flex items-center justify-center rounded-lg bg-purple-50 text-[#5628C7]">
                <i class="ti ti-menu-2 text-xl"></i>
            </button>

            <span class="font-bold text-gray-800">SIMPAN Admin</span>

        </header>

        <main class="flex-1 p-4 md:p-8">
            @yield('content')
        </main>

    </div>

</div>

<script>
function openSidebar()
{
    document.getElementById('sidebar').classList.remove('-translate-x-full');
    document.getElementById('sidebarOverlay').classList.remove('hidden');
}

function closeSidebar()
{
    document.getElementById('sidebar').classList.add('-translate-x-full');
    document.getElementById('sidebarOverlay').classList.add('hidden');
}

let notifLama = parseInt(document.body.dataset.notifBelum, 10);

setInterval(() => {
    fetch('/jumlah-notifikasi')
        .then(response => response.json())
        .then(data => {
            let badge = document.getElementById('badgeNotif');
            if (data.jumlah > 0) {
                badge.innerText = data.jumlah;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
            if (data.jumlah > notifLama) {
                document.getElementById('notifSound').play();
            }
            notifLama = data.jumlah;
        });
}, 10000);
</script>

<audio id="notifSound">
    <source src="/sounds/notif.mp3" type="audio/mpeg">
</audio>

</body>
</html>