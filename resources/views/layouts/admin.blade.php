<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | SISPUS SMAN 3 Padang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

   <style>
        /* --- HIGH-END MODERN COLOR PALETTE (Strictly Blue, Gray, White) --- */
        :root, [data-bs-theme="light"] {
            --bg-main: #f1f5f9; /* Abu-abu terang premium agar card putih terlihat kontras */
            --bg-card: #ffffff; /* Putih bersih */
            --text-main: #0f172a; /* Abu-abu kebiruan sangat gelap (nyaris hitam) */
            --text-muted: #64748b; /* Abu-abu standar */
            --border-color: #e2e8f0; /* Garis abu-abu muda */
            
            /* PREMIUM DARK SIDEBAR (Gradasi Biru Tua ke Abu-abu Gelap) */
            --sidebar-bg: linear-gradient(180deg, #1e3a8a 0%, #0f172a 100%);
            --sidebar-text: #cbd5e1;
            --sidebar-text-hover: #ffffff;
            --sidebar-border: #1e40af;
            
            --topbar-bg: rgba(255, 255, 255, 0.85);
            
            /* KOMBINASI WARNA BARU (Biru Tua & Biru Muda Saja) */
            --primary-color: #0ea5e9; /* Biru Muda Cerah */
            /* Gradasi Biru Tua ke Biru Muda */
            --primary-gradient: linear-gradient(135deg, #1e3a8a 0%, #0ea5e9 100%); 
            /* Gradasi Brand Teks (Biru Langit ke Biru Royal) */
            --brand-gradient: linear-gradient(135deg, #38bdf8 0%, #1d4ed8 100%);
            
            --shadow-sm: 0 4px 6px -1px rgba(15, 23, 42, 0.05), 0 2px 4px -1px rgba(15, 23, 42, 0.03);
            --shadow-md: 0 20px 25px -5px rgba(15, 23, 42, 0.06), 0 10px 10px -5px rgba(15, 23, 42, 0.04);
            --hover-bg: #f8fafc; /* Abu-abu super tipis untuk hover */
        }

        [data-bs-theme="dark"] {
            --bg-main: #090d16;
            --bg-card: #111827;
            --text-main: #f9fafb;
            --text-muted: #9ca3af;
            --border-color: #1f2937;
            
            --sidebar-bg: linear-gradient(180deg, #020617 0%, #090d16 100%);
            --sidebar-text: #9ca3af;
            --sidebar-text-hover: #ffffff;
            --sidebar-border: #111827;
            
            --topbar-bg: rgba(17, 24, 39, 0.8);
            --primary-color: #38bdf8;
            --primary-gradient: linear-gradient(135deg, #1e40af 0%, #38bdf8 100%);
            --brand-gradient: linear-gradient(135deg, #7dd3fc 0%, #3b82f6 100%);
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 20px 30px rgba(0, 0, 0, 0.4);
            --hover-bg: #1f2937;
        }

        /* Efek Transisi Animasi Halus */
        *, *::before, *::after {
            transition: background 0.3s ease, background-color 0.3s ease, border-color 0.3s ease, color 0.2s ease, box-shadow 0.3s ease, transform 0.2s ease;
        }

        body {
            background-color: var(--bg-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* --- STRIKING ULTRA-MODERN SIDEBAR --- */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            box-shadow: 8px 0 32px rgba(15, 23, 42, 0.15);
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.15); border-radius: 10px; }

        .sidebar-brand {
            padding: 32px 24px;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .sidebar-brand h4 {
            font-weight: 800;
            background: var(--brand-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.35rem;
        }

        .sidebar-brand small {
            color: #94a3b8;
            font-weight: 700;
            font-size: 0.72rem;
            display: block;
            margin-top: 6px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .sidebar-menu { padding: 24px 16px; flex-grow: 1; }

        .menu-title {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 1.5px;
            margin: 28px 12px 10px;
        }

        .sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 12px 16px;
            border-radius: 14px;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar .nav-link i { font-size: 1.2rem; opacity: 0.7; }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--sidebar-text-hover);
            transform: translateX(6px); /* Menu bergeser interaktif */
        }

        .sidebar .nav-link.active {
            /* Kombinasi warna Biru Tua ke Biru Muda (Tidak ada pink!) */
            background: var(--primary-gradient); 
            color: #ffffff !important;
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.3);
            font-weight: 700;
        }
        
        .sidebar .nav-link.active i { opacity: 1; }

        .sidebar .nav-link.text-danger:hover {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171 !important;
        }

        /* --- MAIN CONTENT & GLASS-TOPBAR --- */
        .main-content {
            margin-left: 260px;
            padding: 40px;
            min-height: 100vh;
        }

        .topbar {
            background: var(--topbar-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 18px 28px;
            margin-bottom: 40px;
            box-shadow: var(--shadow-sm);
            position: relative;
            z-index: 10;
        }

        .topbar h5 { margin: 0; font-weight: 800; font-size: 1.25rem; letter-spacing: -0.5px; color: var(--text-main); }
        .topbar p { margin: 0; color: var(--text-muted); font-size: 0.88rem; font-weight: 500; margin-top: 2px; }

        /* Widgets & Buttons */
        .theme-toggle-btn {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow-sm);
        }
        .theme-toggle-btn:hover {
            background: var(--border-color);
            transform: translateY(-2px);
        }

        .profile-widget {
            background: var(--bg-card);
            padding: 6px 16px 6px 6px;
            border-radius: 99px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar-circle {
            width: 38px;
            height: 38px;
            /* Avatar diganti jadi gradasi Biru Tua ke Biru Langit (Tidak ada ungu!) */
            background: linear-gradient(135deg, #1e3a8a, #0ea5e9);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(14, 165, 233, 0.25);
        }

        /* --- PREMIUM FLOATING CARDS --- */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 24px;
            background: var(--bg-card);
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-6px); /* Efek melayang saat hover */
            box-shadow: var(--shadow-md);
            border-color: #cbd5e1;
        }

        /* Tombol Utama Ganti Kombinasi Warna */
        .btn {
            border-radius: 14px;
            padding: 12px 24px;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: var(--shadow-sm);
        }
        
        .btn-primary {
            /* Tombol utama diganti jadi murni Biru Tua ke Biru Muda */
            background: var(--primary-gradient);
            border: none;
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1e3a8a 0%, #0284c7 100%);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.3);
            transform: translateY(-2px);
        }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; padding: 20px; }
        }
    </style>
</head>

<body>
@php
$notifications = Auth::user()
    ->unreadNotifications()
    ->latest()
    ->take(5)
    ->get();

$notificationCount = $notifications->count();
@endphp
    <div class="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-book-half"></i> SISPUS</h4>
            <small>SMAN 3 Padang</small>
        </div>

        <div class="sidebar-menu">
            <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="/admin/dashboard">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <div class="menu-title">Data Master</div>
            <a class="nav-link {{ request()->is('admin/buku') ? 'active' : '' }}" href="/admin/buku">
                <i class="bi bi-book-fill"></i> Data Buku
            </a>
            <a class="nav-link {{ request()->is('admin/anggota') ? 'active' : '' }}" href="/admin/anggota">
                <i class="bi bi-people-fill"></i> Anggota
            </a>

            <div class="menu-title">Transaksi</div>
            <a class="nav-link {{ request()->is('admin/peminjaman') ? 'active' : '' }}" href="/admin/peminjaman">
                <i class="bi bi-journal-arrow-up"></i> Peminjaman
            </a>
            <a class="nav-link {{ request()->is('admin/pengembalian') ? 'active' : '' }}" href="/admin/pengembalian">
                <i class="bi bi-journal-check"></i> Pengembalian
            </a>

            <div class="menu-title">Analitik</div>
            <a class="nav-link {{ request()->is('admin/laporan') ? 'active' : '' }}" href="/admin/laporan">
                <i class="bi bi-bar-chart-fill"></i> Laporan & Statistik
            </a>
            <a class="nav-link {{ request()->is('admin/gamifikasi') ? 'active' : '' }}" href="/admin/gamifikasi">
                <i class="bi bi-trophy-fill"></i> Leaderboard 
            </a>

            <div class="menu-title">Akun</div>
            <a href="{{ route('web.profile') }}" class="nav-link {{ request()->is('admin/profile*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profil
            </a>
            <a class="nav-link text-danger" href="{{ route('web.logout') }}">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>

    <div class="main-content">
        
        <div class="topbar d-flex justify-content-between align-items-center">
            <div class="d-none d-md-block">
                <h5>Sistem Informasi Perpustakaan</h5>
                <p>Dashboard pustakawan SMAN 3 Padang</p>
            </div>

            <div class="d-flex align-items-center gap-3 ms-auto">
                <button class="theme-toggle-btn" id="darkModeToggle" title="Ubah Tema">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>

        <div class="dropdown">

    <button
        class="btn btn-light rounded-circle shadow-sm position-relative border-0"
        type="button"
        data-bs-toggle="dropdown"
        data-bs-auto-close="outside"
        style="width:48px;height:48px;">

        <i class="bi bi-bell-fill text-primary fs-5"></i>

        @if($notificationCount > 0)
            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                style="font-size:11px;">

                {{ $notificationCount }}

            </span>
        @endif

    </button>

    <div
        class="dropdown-menu dropdown-menu-end p-0 shadow-lg border-0 notification-dropdown">

        {{-- HEADER --}}
        <div class="notification-header">

            <div class="fw-bold fs-5">

                🔔 Notifikasi

            </div>

            @if($notificationCount > 0)

                <a
                    href="{{ route('web.notification.clear') }}"
                    class="text-danger text-decoration-none fw-semibold">

                    Clear All

                </a>

            @endif

        </div>

        {{-- BODY --}}
        <div class="notification-body">

            @forelse($notifications as $notification)

                @php

                    $title = $notification->data['title'];

                    $icon='🔔';
                    $bg='#ececec';

                    if(str_contains($title,'Peminjaman')){
                        $icon='📚';
                        $bg='#EAF3FF';
                    }

                    if(str_contains($title,'Pengembalian')){
                        $icon='📖';
                        $bg='#ECFFF3';
                    }

                    if(str_contains($title,'Hilang')){
                        $icon='📕';
                        $bg='#FFF3E8';
                    }

                @endphp

                <a
                    href="{{ $notification->data['url'] }}"
                    class="notification-item text-decoration-none">

                    <div
                        class="notification-icon"
                        style="background-color: {{ $bg }};">

                        {{ $icon }}

                    </div>

                    <div class="flex-grow-1">

                        <div class="fw-semibold text-dark">

                            {{ $notification->data['title'] }}

                        </div>

                        <div
                            class="small text-muted mt-1">

                            {{ $notification->data['message'] }}

                        </div>

                        <div
                            class="small text-secondary mt-2">

                            <i class="bi bi-clock"></i>

                            {{ $notification->created_at->diffForHumans() }}

                        </div>

                    </div>

                </a>

            @empty

                <div class="text-center py-5">

                    <div style="font-size:55px">

                        🔔

                    </div>

                    <div class="fw-bold mt-3">

                        Belum ada notifikasi

                    </div>

                    <div class="text-muted small">

                        Semua notifikasi akan muncul di sini.

                    </div>

                </div>

            @endforelse

        </div>

    </div>

</div>

<style>

.notification-dropdown{

    width:420px;
    border-radius:18px;
    overflow:hidden;
    margin-top:12px;
    z-index:99999;

}

.notification-header{

    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 20px;
    background:#fff;
    border-bottom:1px solid #ececec;

}

.notification-body{

    max-height:430px;
    overflow-y:auto;
    background:white;

}

.notification-item{

    display:flex;
    gap:15px;
    padding:18px;
    border-bottom:1px solid #f2f2f2;
    transition:.2s;

}

.notification-item:hover{

    background:#f5f9ff;

}

.notification-icon{

    width:52px;
    height:52px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:23px;
    flex-shrink:0;

}

.notification-body::-webkit-scrollbar{

    width:6px;

}

.notification-body::-webkit-scrollbar-thumb{

    background:#d8d8d8;
    border-radius:20px;

}

.dropdown-menu{

    animation:fadeNotif .18s ease;

}

@keyframes fadeNotif{

    from{

        opacity:0;
        transform:translateY(-8px);

    }

    to{

        opacity:1;
        transform:translateY(0);

    }

}

</style>
                <div class="profile-widget d-flex align-items-center gap-3">
                    <div class="avatar-circle">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="text-start d-none d-sm-block">
                        <div class="fw-bold lh-1 mb-1" style="font-size: 0.9rem; color: var(--text-main);">{{ Auth::user()->name ?? 'Pustakawan' }}</div>
                        <small class="text-muted" style="font-size: 0.75rem;">{{ Auth::user()->role ?? 'Admin' }}</small>
                    </div>
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const themeToggleBtn = document.getElementById('darkModeToggle');
        const themeIcon = themeToggleBtn.querySelector('i');
        
        // Ambil preferensi tema dari localStorage, default light
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        updateToggleIcon(savedTheme);

        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateToggleIcon(newTheme);
        });

        function updateToggleIcon(theme) {
            if (theme === 'dark') {
                themeIcon.className = 'bi bi-sun-fill text-warning';
            } else {
                themeIcon.className = 'bi bi-moon-stars-fill text-secondary';
            }
        }
    </script>

    @if(session('success'))
    <script>
        Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}" });
    </script>
    @endif
</body>
</html>