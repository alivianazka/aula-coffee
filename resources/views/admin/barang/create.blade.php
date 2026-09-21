<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang - Aula Coffee Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/jpeg">
</head>
<body>

@include('components.navbar')

<style>
    .form-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.07);
        padding: 30px;
        max-width: 800px;
        margin: 0 auto;
    }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group { margin-bottom: 20px; }
    .form-group.full { grid-column: span 2; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px; }
    .form-control, select, textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1.5px solid #e0dbd4;
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Outfit', sans-serif;
        box-sizing: border-box;
    }
    .form-control:focus, select:focus, textarea:focus { border-color: #d4a373; outline: none; }
    .btn-submit {
        background: #d4a373;
        color: #1a1714;
        padding: 14px;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        width: 100%;
        font-family: 'Outfit', sans-serif;
        margin-top: 10px;
    }
    .btn-submit:hover { background: #b5835a; }
    .error-msg { color: #e74c3c; font-size: 12px; margin-top: 5px; }
    
    @media (max-width: 600px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-group.full { grid-column: span 1; }
    }
</style>

<div class="page-header">
    <h1><i class="fas fa-plus-circle" style="color:#d4a373;margin-right:10px;"></i>Tambah Barang Baru</h1>
    <p>Daftarkan item barang baru ke dalam sistem inventaris.</p>
</div>

<div class="page-body">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('barang.index') }}" style="color: #888; text-decoration: none; font-size: 14px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Barang
        </a>
    </div>

    <div class="form-card">
        <form action="{{ route('barang.store') }}" method="POST">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="nama">Nama Barang</label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Contoh: Biji Kopi Arabica" value="{{ old('nama') }}" required>
                    @error('nama') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label for="kategori_id">Kategori</label>
                    <select name="kategori_id" id="kategori_id" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $cat)
                            <option value="{{ $cat->id }}" {{ old('kategori_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label for="unit">Satuan (Unit)</label>
                    <input type="text" name="unit" id="unit" class="form-control" placeholder="Contoh: kg, liter, pcs" value="{{ old('unit') }}" required>
                    @error('unit') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label for="stok_opname">Batas Stok Opname (Minimum)</label>
                    <input type="number" name="stok_opname" id="stok_opname" class="form-control" placeholder="Notifikasi muncul jika stok <= angka ini" value="{{ old('stok_opname', 0) }}" required>
                    @error('stok_opname') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label for="stok_awal">Stok Awal</label>
                    <input type="number" name="stok_awal" id="stok_awal" class="form-control" value="{{ old('stok_awal', 0) }}" required>
                    @error('stok_awal') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" placeholder="Keterangan tambahan barang...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <button type="submit" class="btn-submit">Daftarkan Barang</button>
        </form>
    </div>
</div>

</body>
</html>
