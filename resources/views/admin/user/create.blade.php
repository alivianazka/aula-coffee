<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Karyawan - Aula Coffee Club</title>
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
        max-width: 600px;
        margin: 0 auto;
    }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px; }
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1.5px solid #e0dbd4;
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Outfit', sans-serif;
        box-sizing: border-box;
    }
    .form-control:focus { border-color: #d4a373; outline: none; }
    .btn-submit {
        background: #d4a373;
        color: #1a1714;
        padding: 12px 25px;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        width: 100%;
        font-family: 'Outfit', sans-serif;
    }
    .btn-submit:hover { background: #b5835a; }
    .error-msg { color: #e74c3c; font-size: 12px; margin-top: 5px; }
</style>

<div class="page-header">
    <h1><i class="fas fa-user-plus" style="color:#d4a373;margin-right:10px;"></i>Tambah Karyawan Baru</h1>
    <p>Buat akun baru untuk karyawan Aula Coffee Club.</p>
</div>

<div class="page-body">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.users.index') }}" style="color: #888; text-decoration: none; font-size: 14px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                @error('name') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
                @error('password') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>
            
            <button type="submit" class="btn-submit">Buat Akun Karyawan</button>
        </form>
    </div>
</div>

</body>
</html>
