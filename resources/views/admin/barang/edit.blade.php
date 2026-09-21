<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Aula Coffee Club</title>
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
    <h1><i class="fas fa-edit" style="color:#d4a373;margin-right:10px;"></i>Edit Barang</h1>
    <p>Perbarui informasi barang {{ $barang->nama }}.</p>
</div>

<div class="page-body">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('barang.index') }}" style="color: #888; text-decoration: none; font-size: 14px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Barang
        </a>
    </div>

    <div class="form-card">
        <form action="{{ route('barang.update', $barang->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="nama">Nama Barang</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $barang->nama) }}" required>
                    @error('nama') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label for="kategori_id">Kategori</label>
                    <select name="kategori_id" id="kategori_id" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $cat)
                            <option value="{{ $cat->id }}" {{ old('kategori_id', $barang->kategori_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label for="unit">Satuan (Unit)</label>
                    <input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit', $barang->unit) }}" required>
                    @error('unit') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label for="stok_opname">Batas Stok Opname (Minimum)</label>
                    <input type="number" name="stok_opname" id="stok_opname" class="form-control" value="{{ old('stok_opname', $barang->stok_opname) }}" required>
                    @error('stok_opname') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group full">
                    <label for="deskripsi">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                    @error('deskripsi') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <button type="submit" class="btn-submit">Perbarui Informasi Barang</button>
        </form>
    </div>
</div>

</body>
</html>
