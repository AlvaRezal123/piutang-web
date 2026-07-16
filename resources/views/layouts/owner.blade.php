<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPAN - Owner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

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

        .nav-link.danger .icon-wrap {
            background: rgba(255, 100, 100, 0.15);
        }
    </style>
</head>

<body class="bg-[#f7f5ff]">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 sidebar-bg sticky top-0 h-screen flex flex-col shadow-2xl">

        <!-- Logo -->
        <div class="logo-box flex items-center gap-3">
            <img
                src="{{ asset('images/logo-partnerpulsa.png') }}"
                alt="Partner Pulsa"
                class="w-11 h-11 rounded-xl shadow-lg ring-2 ring-white/20">
            <div>
                <h1 class="logo-title">SIMPAN</h1>
                <p class="logo-sub">Owner Panel</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 overflow-y-auto">

            <div class="section-label">Dashboard</div>

            <a href="/dashboard-owner"
               class="nav-link {{ request()->is('dashboard-owner') ? 'active' : '' }}">
                <span class="icon-wrap">
    <i class="ti ti-home"></i>
</span>
                Dashboard
            </a>

            <div class="section-label">Approval</div>

            <a href="/owner/hutang"
               class="nav-link {{ request()->is('owner/hutang*') ? 'active' : '' }}">
              <span class="icon-wrap">
    <i class="ti ti-file-check"></i>
</span>
                Approval Pengajuan
            </a>

            <a href="/owner/approval-cicilan"
            class="nav-link {{ request()->is('owner/approval-cicilan*') ? 'active' : '' }}">
                <span class="icon-wrap">
                    <i class="ti ti-credit-card"></i>
                </span>
                Approval Cicilan
            </a>

            <a href="/admin/hutang"
               class="nav-link {{ request()->is('admin/hutang*') ? 'active' : '' }}">
                  <span class="icon-wrap">
    <i class="ti ti-chart-bar"></i>
</span>
                Monitoring Hutang
            </a>

            <div class="section-label">Akun</div>

            <a href="/logout" class="nav-link danger">
                <span class="icon-wrap">
    <i class="ti ti-logout"></i>
</span>
                Logout
            </a>

        </nav>

        <!-- Footer -->
        <div class="px-5 py-4 border-t border-white/10">
            <p class="text-[10px] text-white/30 text-center">© 2026 SIMPAN · Partner Pulsa</p>
        </div>

    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-8">
        @yield('content')
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</html>