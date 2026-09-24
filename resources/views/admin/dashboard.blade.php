<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Aula Coffee Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/jpeg">
</head>
<body>

@include('components.navbar')

<div class="page-header">
    <h1><i class="fas fa-tachometer-alt" style="color:#d4a373;margin-right:10px;"></i>Dashboard Admin</h1>
    <p>Selamat datang, {{ Auth::user()->name }}! Pantau seluruh operasional stok barang.</p>
</div>

<div class="page-body">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid">
        <a href="{{ route('barang.index') }}" class="stat-card" style="text-decoration: none;">
            <div class="stat-icon" style="background:#fff3e0; color:#e67e22;">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-text">
                <div class="num">{{ $totalBarang }}</div>
                <div class="label">Total Barang</div>
            </div>
        </a>
        <a href="{{ route('stok.index') }}?filter=so" class="stat-card" style="text-decoration: none;">
            <div class="stat-icon" style="background:#fdecea; color:#e53935;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-text">
                <div class="num" id="stock-low-count" style="color:#e53935;">{{ $barangStokRendah }}</div>
                <div class="label">Stok Rendah</div>
            </div>
        </a>

        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f5e9; color:#27ae60;">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="stat-text">
                <div class="num" style="font-size:16px; padding-top:4px;">{{ Auth::user()->name }}</div>
                <div class="label">Login sebagai Admin</div>
            </div>
        </div>
    </div>

    {{-- Management Actions --}}
    <p class="section-title"><i class="fas fa-cogs"></i> Manajemen Data</p>
    <div class="action-grid">
        <a href="{{ route('barang.index') }}" class="action-card">
            <div class="action-icon" style="background:#fff3e0; color:#e67e22;"><i class="fas fa-list"></i></div>
            <div class="action-text">
                <div class="atitle">Kelola Barang</div>
                <div class="adesc">Lihat & kelola semua data barang</div>
            </div>
        </a>
        <a href="{{ route('barang.create') }}" class="action-card">
            <div class="action-icon" style="background:#e8f5e9; color:#27ae60;"><i class="fas fa-plus-circle"></i></div>
            <div class="action-text">
                <div class="atitle">Tambah Barang</div>
                <div class="adesc">Daftarkan barang baru ke sistem</div>
            </div>
        </a>
        <a href="{{ route('stok.index') }}" class="action-card">
            <div class="action-icon" style="background:#e3f2fd; color:#1976d2;"><i class="fas fa-eye"></i></div>
            <div class="action-text">
                <div class="atitle">Lihat Semua Stok</div>
                <div class="adesc">Pantau pergerakan stok barang</div>
            </div>
        </a>
        <a href="{{ route('laporan.index') }}" class="action-card">
            <div class="action-icon" style="background:#f3e5f5; color:#8e44ad;"><i class="fas fa-chart-bar"></i></div>
            <div class="action-text">
                <div class="atitle">Buat Laporan</div>
                <div class="adesc">Generate laporan harian/bulanan/tahunan</div>
            </div>
        </a>
        <a href="{{ route('admin.users.index') }}" class="action-card">
            <div class="action-icon" style="background:#e8f5e9; color:#27ae60;"><i class="fas fa-users-cog"></i></div>
            <div class="action-text">
                <div class="atitle">Kelola Karyawan</div>
                <div class="adesc">Atur akses akun karyawan</div>
            </div>
        </a>

    </div>

</div>

</body>
</html>
