{{-- Reusable Navbar Component: resources/views/components/navbar.blade.php --}}
@props(['title' => 'Dashboard', 'role' => 'user'])

@php
    $roleColors = [
        'admin'    => '#e67e22',
        'owner'    => '#8e44ad',
        'karyawan' => '#27ae60',
    ];
    $roleColor = $roleColors[Auth::user()->role] ?? '#d4a373';
    $roleLabel = ucfirst(Auth::user()->role);
@endphp

<style>
    :root {
        --primary: #d4a373;
        --primary-hover: #b5835a;
        --dark: #1a1714;
        --dark2: #231f1b;
        --border: rgba(212,163,115,0.15);
        --text: #f0ebe3;
        --muted: #9a9085;
        --border: rgba(212,163,115,0.15);
    }

    i { font-style: normal; display: inline-block; line-height: 1; vertical-align: middle; }
    .fas, .far, .fab { font-size: inherit; }

    /* Pagination Fix - Handles Giant Icons */
    .pagination { display: flex; list-style: none; padding: 0; gap: 5px; align-items: center; margin-top: 15px; }
    .pagination svg { width: 14px !important; height: 14px !important; display: inline-block; }
    .page-link { 
        padding: 6px 12px; 
        border-radius: 8px; 
        background: white; 
        border: 1px solid rgba(212,163,115,0.15); 
        color: #1a1714; 
        text-decoration: none; 
        font-size: 13px;
        transition: all .2s;
    }
    .page-item.active .page-link { background: #d4a373; color: white; border-color: #d4a373; }
    .page-link:hover { background: #f0ede8; }
    /* Hide default laravel text that might look weird without tailwind */
    .hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between { display: none !important; }

    * { margin:0; padding:0; box-sizing:border-box; }

    body {
        font-family: 'Outfit', sans-serif;
        background: #f0ede8;
        color: #2c2c2c;
        min-height: 100vh;
    }

    /* ── NAVBAR ── */
    .topbar {
        background: var(--dark);
        border-bottom: 1px solid var(--border);
        padding: 0 24px;
        height: 62px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 15px rgba(0,0,0,0.4);
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .brand img {
        width: 36px; height: 36px;
        border-radius: 50%;
        border: 2px solid var(--primary);
        object-fit: cover;
    }

    .brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
    }

    .brand-text .name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
    }

    .brand-text .sub {
        font-size: 10.5px;
        color: var(--muted);
        font-weight: 300;
    }

    /* Nav links */
    .nav-links {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .nav-links a {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #bbb;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        padding: 7px 13px;
        border-radius: 8px;
        transition: background .2s, color .2s;
    }

    .nav-links a:hover, .nav-links a.active {
        background: rgba(212,163,115,0.12);
        color: var(--primary);
    }

    /* User dropdown */
    .user-menu {
        position: relative;
    }

    .user-btn {
        display: flex;
        align-items: center;
        gap: 9px;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: rgba(255,255,255,0.04);
        transition: background .2s, border-color .2s;
        user-select: none;
    }

    .user-btn:hover {
        background: rgba(212,163,115,0.1);
        border-color: rgba(212,163,115,0.3);
    }

    .user-avatar {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: var(--primary);
        color: var(--dark);
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
        font-weight: 700;
    }

    .user-info .uname {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        max-width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-info .urole {
        font-size: 10.5px;
        font-weight: 600;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .user-btn .chevron {
        color: var(--muted);
        font-size: 11px;
        transition: transform .2s;
    }

    .user-menu.open .chevron { transform: rotate(180deg); }

    /* Dropdown */
    .dropdown-menu {
        position: absolute;
        right: 0;
        top: calc(100% + 8px);
        min-width: 210px;
        background: var(--dark2);
        border: 1px solid var(--border);
        border-radius: 14px;
        box-shadow: 0 12px 35px rgba(0,0,0,0.5);
        overflow: hidden;
        opacity: 0;
        transform: translateY(-8px);
        pointer-events: none;
        transition: opacity .2s, transform .2s;
        z-index: 200;
    }

    .user-menu.open .dropdown-menu {
        opacity: 1;
        transform: translateY(0);
        pointer-events: all;
    }

    .dd-header {
        padding: 16px 18px 12px;
        border-bottom: 1px solid var(--border);
    }

    .dd-header .full-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
    }

    .dd-header .full-email {
        font-size: 12px;
        color: var(--muted);
        margin-top: 2px;
        word-break: break-all;
    }

    .dd-role-badge {
        display: inline-block;
        margin-top: 8px;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .5px;
    }

    .dd-items { padding: 8px; }

    .dd-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 9px;
        font-size: 13.5px;
        color: #ccc;
        text-decoration: none;
        transition: background .2s, color .2s;
        cursor: pointer;
    }

    .dd-item:hover { background: rgba(255,255,255,0.06); color: var(--text); }

    .dd-item.logout {
        color: #ff6b6b;
        border-top: 1px solid var(--border);
        margin-top: 4px;
        padding-top: 14px;
        border-radius: 0 0 9px 9px;
    }

    .dd-item.logout:hover { background: rgba(255,107,107,0.1); }

    .dd-item i { width: 16px; text-align: center; }

    /* Mobile hamburger */
    .hamburger {
        display: none;
        background: none;
        border: none;
        color: var(--text);
        font-size: 20px;
        cursor: pointer;
        padding: 6px;
    }

    @media (max-width: 768px) {
        .hamburger { display: block; }
        .nav-links {
            display: none;
            position: absolute;
            top: 62px;
            left: 0; right: 0;
            background: var(--dark);
            padding: 12px 16px;
            flex-direction: column;
            align-items: stretch;
            border-bottom: 1px solid var(--border);
        }
        .nav-links.show { display: flex; }
        .topbar { position: relative; }
        .user-info { display: none; }
    }

    /* ── PAGE LAYOUT ── */
    .page-header {
        background: linear-gradient(135deg, var(--dark) 0%, #2c2518 100%);
        padding: 28px 32px;
        border-bottom: 1px solid rgba(212,163,115,0.1);
    }

    .page-header h1 {
        font-size: 24px;
        font-weight: 800;
        color: var(--text);
    }

    .page-header p {
        font-size: 13.5px;
        color: var(--muted);
        margin-top: 4px;
    }

    .page-body {
        padding: 28px 32px;
        max-width: 1280px;
        margin: 0 auto;
    }

    /* ── STATS CARDS ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 22px 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: transform .2s, box-shadow .2s;
    }

    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }

    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .stat-text .num {
        font-size: 28px;
        font-weight: 800;
        line-height: 1;
        color: #1a1a1a;
    }

    .stat-text .label {
        font-size: 12.5px;
        color: #888;
        margin-top: 3px;
        font-weight: 500;
    }

    /* ── ACTION CARDS ── */
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c2c2c;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i { color: var(--primary); }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
        margin-bottom: 28px;
    }

    .action-card {
        background: white;
        border-radius: 14px;
        padding: 20px 22px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 16px;
        color: #2c2c2c;
        border: 1.5px solid transparent;
        transition: all .25s;
    }

    .action-card:hover {
        border-color: var(--primary);
        box-shadow: 0 6px 20px rgba(212,163,115,0.2);
        transform: translateY(-2px);
        color: #1a1a1a;
    }

    .action-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .action-text .atitle {
        font-size: 14px;
        font-weight: 700;
    }

    .action-text .adesc {
        font-size: 12px;
        color: #888;
        margin-top: 2px;
    }

    /* ── ALERTS ── */
    .flash-success, .flash-error, .flash-info {
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .flash-success { background: #d4edda; color: #155724; }
    .flash-error   { background: #f8d7da; color: #721c24; }
    .flash-info    { background: #d1ecf1; color: #0c5460; }

    /* ── RESPONSIVE ── */
    @media (max-width: 640px) {
        .page-body { padding: 20px 16px; }
        .page-header { padding: 20px 16px; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .action-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 400px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

<nav class="topbar">
    <!-- Brand (Clickable to Dashboard) -->
    <a href="{{ route('dashboard') }}" class="brand" style="text-decoration: none;">
        <img src="{{ asset('logo_aula_cofee.jpeg') }}" alt="Logo">
        <div class="brand-text">
            <span class="name">Aula Coffee Club</span>
            <span class="sub">Inventory System</span>
        </div>
    </a>

    <!-- Nav Links -->
    <div class="nav-links" id="navLinks">
        {{ $navLinks ?? '' }}
    </div>

    <div style="display: flex; align-items: center; gap: 12px;">
        <!-- Notification Bell (All Actors) -->
        @php $notifCount = \App\Models\Notifikasi::where('status','belum_dibaca')->count(); @endphp
        <a href="{{ route('notifikasi.index') }}" id="notifBell" style="
            position: relative;
            display: flex; align-items: center; justify-content: center;
            width: 40px; height: 40px;
            border-radius: 10px;
            border: 1px solid rgba(212,163,115,0.15);
            color: #bbb;
            text-decoration: none;
            transition: background .2s, color .2s;
        " onmouseover="this.style.background='rgba(212,163,115,0.1)'; this.style.color='#d4a373'"
           onmouseout="this.style.background=''; this.style.color='#bbb'">
            <i class="fas fa-bell"></i>
            @if($notifCount > 0)
            <span id="notifDot" style="
                position: absolute; top: 6px; right: 6px;
                width: 8px; height: 8px;
                background: #e53935;
                border-radius: 50%;
                border: 2px solid #1a1714;
                animation: blink 1.5s infinite;
            "></span>
            @endif
        </a>
        <style>
            @keyframes blink {
                0%, 100% { opacity: 1; }
                50%       { opacity: .3; }
            }
        </style>

        <!-- User Dropdown -->
        <div class="user-menu" id="userMenu">
            <div class="user-btn" onclick="toggleUserMenu()">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="user-info">
                    <div class="uname">{{ Auth::user()->name }}</div>
                    <div class="urole" style="color: {{ $roleColor }}">{{ $roleLabel }}</div>
                </div>
                <i class="fas fa-chevron-down chevron"></i>
            </div>

            <div class="dropdown-menu">
                <div class="dd-header">
                    <div class="full-name">{{ Auth::user()->name }}</div>
                    <div class="full-email">{{ Auth::user()->email }}</div>
                    <span class="dd-role-badge" style="background: {{ $roleColor }}22; color: {{ $roleColor }}">
                        {{ $roleLabel }}
                    </span>
                </div>
                <div class="dd-items">
                    <a href="{{ route('dashboard') }}" class="dd-item">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                        @csrf
                        <button type="submit" class="dd-item logout" style="width:100%;border:none;background:none;text-align:left;font-family:inherit;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <button class="hamburger" onclick="toggleNav()">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<script>
    function toggleUserMenu() {
        document.getElementById('userMenu').classList.toggle('open');
    }
    function toggleNav() {
        document.getElementById('navLinks').classList.toggle('show');
    }
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('userMenu');
        if (!menu.contains(e.target)) menu.classList.remove('open');
    });

    async function refreshLowStockCount() {
        const countElement = document.getElementById('stock-low-count');
        if (!countElement) return;

        try {
            const response = await fetch('{{ route('api.stok-rendah') }}', {
                headers: { 'Accept': 'application/json' },
                cache: 'no-store'
            });
            if (response.ok) {
                const result = await response.json();
                countElement.textContent = result.count;
            }
        } catch (error) {
            console.error('Gagal memperbarui status stok rendah:', error);
        }
    }

    setInterval(refreshLowStockCount, 5000);
</script>
