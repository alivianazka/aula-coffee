<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Karyawan - Aula Coffee Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/jpeg">
</head>
<body>

@include('components.navbar')

<div class="page-header">
    <h1><i class="fas fa-mug-hot" style="color:#d4a373;margin-right:10px;"></i>Dashboard Karyawan</h1>
    <p>Selamat datang, {{ Auth::user()->name }}! Kelola shift dan stok barang Anda.</p>
</div>

<div class="page-body">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="flash-info"><i class="fas fa-info-circle"></i> {{ session('info') }}</div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $activeShift ? '#e8f5e9' : '#fdecea' }}; color:{{ $activeShift ? '#27ae60' : '#e53935' }};">
                <i class="fas fa-{{ $activeShift ? 'play-circle' : 'pause-circle' }}"></i>
            </div>
            <div class="stat-text">
                <div class="num" style="font-size:16px; padding-top:6px; color:{{ $activeShift ? '#27ae60' : '#e53935' }};">
                    {{ $activeShift ? 'Shift Aktif' : 'Tidak Aktif' }}
                </div>
                <div class="label">
                    @if($activeShift)
                        Mulai: {{ $activeShift->awal_shift->format('H:i, d M Y') }}
                    @else
                        Belum memulai shift hari ini
                    @endif
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdecea; color:#e53935;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-text">
                <div class="num" style="color:#e53935;">{{ $barangStokRendah }}</div>
                <div class="label">Item Stok Rendah</div>
            </div>
        </div>
    </div>

    {{-- Shift Actions --}}
    <p class="section-title"><i class="fas fa-clock"></i> Manajemen Shift</p>
    @if($activeShift)
        <div class="action-grid">
            <a href="{{ route('stok.create') }}" class="action-card">
                <div class="action-icon" style="background:#e8f5e9; color:#27ae60;"><i class="fas fa-plus-circle"></i></div>
                <div class="action-text">
                    <div class="atitle">Input Stok</div>
                    <div class="adesc">Catat pergerakan barang masuk/keluar</div>
                </div>
            </a>
            <a href="{{ route('stok.lihat') }}" class="action-card">
                <div class="action-icon" style="background:#e3f2fd; color:#1976d2;"><i class="fas fa-eye"></i></div>
                <div class="action-text">
                    <div class="atitle">Lihat Stok</div>
                    <div class="adesc">Periksa kondisi stok barang saat ini</div>
                </div>
            </a>
            <a href="{{ route('shift.end') }}" class="action-card" style="border-color:#e53935;">
                <div class="action-icon" style="background:#fdecea; color:#e53935;"><i class="fas fa-sign-out-alt"></i></div>
                <div class="action-text">
                    <div class="atitle">Akhiri Shift</div>
                    <div class="adesc">Selesaikan shift kerja Anda hari ini</div>
                </div>
            </a>
        </div>
    @else
        <div class="action-grid">
            <a href="{{ route('shift.create') }}" class="action-card" style="border-color:#27ae60;">
                <div class="action-icon" style="background:#e8f5e9; color:#27ae60;"><i class="fas fa-play-circle"></i></div>
                <div class="action-text">
                    <div class="atitle">Mulai Shift</div>
                    <div class="adesc">Klik untuk memulai shift kerja Anda</div>
                </div>
            </a>
            <a href="{{ route('stok.lihat') }}" class="action-card">
                <div class="action-icon" style="background:#e3f2fd; color:#1976d2;"><i class="fas fa-eye"></i></div>
                <div class="action-text">
                    <div class="atitle">Lihat Stok</div>
                    <div class="adesc">Periksa kondisi stok barang saat ini</div>
                </div>
            </a>
        </div>
    @endif

    {{-- Info Akun --}}
    <p class="section-title"><i class="fas fa-user"></i> Informasi Akun</p>
    <div style="background:white; border-radius:16px; padding:22px 24px; box-shadow:0 2px 10px rgba(0,0,0,0.07);">
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px;">
            <div>
                <div style="font-size:12px;color:#999;margin-bottom:4px;">Nama Lengkap</div>
                <div style="font-size:15px;font-weight:700;">{{ Auth::user()->name }}</div>
            </div>
            <div>
                <div style="font-size:12px;color:#999;margin-bottom:4px;">Email</div>
                <div style="font-size:15px;font-weight:600;">{{ Auth::user()->email }}</div>
            </div>
            <div>
                <div style="font-size:12px;color:#999;margin-bottom:4px;">Role</div>
                <span style="background:#e8f5e9;color:#27ae60;padding:4px 12px;border-radius:50px;font-size:12px;font-weight:700;">Karyawan</span>
            </div>
            <div>
                <div style="font-size:12px;color:#999;margin-bottom:4px;">Bergabung</div>
                <div style="font-size:15px;font-weight:600;">{{ Auth::user()->created_at->format('d M Y') }}</div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
