<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Barang - Aula Coffee Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/jpeg">
</head>
<body>

@include('components.navbar')

<style>
    .barang-table-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.07);
        overflow: hidden;
    }
    .barang-table { width: 100%; border-collapse: collapse; }
    .barang-table thead tr { background: #1a1714; color: #d4a373; }
    .barang-table th { padding: 14px 18px; text-align: left; font-size: 13px; text-transform: uppercase; letter-spacing: .5px; }
    .barang-table td { padding: 14px 18px; font-size: 14px; border-bottom: 1px solid #f0ede8; }
    .barang-table tr:last-child td { border-bottom: none; }
    
    .badge-status {
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
    }
    .badge-danger { background: #fdecea; color: #e53935; }
    .badge-success { background: #e8f5e9; color: #27ae60; }
    
    .btn-action {
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 12px;
        text-decoration: none;
        margin-right: 5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-edit { background: #f39c12; color: white; }
    .btn-delete { background: #e74c3c; color: white; border: none; cursor: pointer; }
    
    .btn-add {
        background: #d4a373;
        color: #1a1714;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }
    .btn-add:hover { background: #b5835a; }
</style>

<div class="page-header">
    <h1><i class="fas fa-boxes" style="color:#d4a373;margin-right:10px;"></i>Kelola Master Barang</h1>
    <p>Atur daftar barang, kategori, dan batas stok opname.</p>
</div>

<div class="page-body">
    @if(session('success'))
        <div class="flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('dashboard') }}" class="btn-add" style="background: #1a1714; color: #d4a373; border: 1.5px solid var(--border);">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('barang.create') }}" class="btn-add">
                <i class="fas fa-plus-circle"></i> Tambah Barang Baru
            </a>
        </div>
    </div>

    <div class="barang-table-card">
        <div style="overflow-x: auto;">
            <table class="barang-table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Nama Barang</th>
                        <th>Stok Saat Ini</th>
                        <th>Batas SO</th>
                        <th>Satuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $item)
                        <tr>
                            <td><span style="color: #888; font-size: 12px;">{{ $item->kategori?->nama }}</span></td>
                            <td><strong>{{ $item->nama }}</strong></td>
                            <td>
                                <strong style="color: {{ $item->isStockLow() ? '#e53935' : '#27ae60' }}">{{ $item->qty }}</strong>
                                @if($item->isStockLow())
                                    <span class="badge-status badge-danger" style="margin-left: 8px;">RENDAH</span>
                                @endif
                            </td>
                            <td><span style="color: #e53935; font-weight: 600;">{{ $item->stok_opname }}</span></td>
                            <td>{{ $item->unit }}</td>
                            <td>
                                <div style="display: flex;">
                                    <a href="{{ route('barang.edit', $item->id) }}" class="btn-action btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('barang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #999;">Belum ada data barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div style="margin-top: 20px;">
        {{ $barang->links() }}
    </div>
</div>

</body>
</html>
