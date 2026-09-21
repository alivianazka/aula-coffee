<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Karyawan - Aula Coffee Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/jpeg">
</head>
<body>

@include('components.navbar')

<style>
    .user-table-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.07);
        overflow: hidden;
    }
    .user-table { width: 100%; border-collapse: collapse; }
    .user-table thead tr { background: #1a1714; color: #d4a373; }
    .user-table th { padding: 14px 18px; text-align: left; font-size: 13px; text-transform: uppercase; letter-spacing: .5px; }
    .user-table td { padding: 14px 18px; font-size: 14px; border-bottom: 1px solid #f0ede8; }
    .user-table tr:last-child td { border-bottom: none; }
    
    .role-badge {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        background: #e8f5e9;
        color: #27ae60;
    }
    
    .btn-action {
        padding: 6px 12px;
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
    
    .btn-create {
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
    .btn-create:hover { background: #b5835a; }
</style>

<div class="page-header">
    <h1><i class="fas fa-users" style="color:#d4a373;margin-right:10px;"></i>Manajemen Karyawan</h1>
    <p>Kelola akun karyawan yang memiliki akses ke sistem input stok.</p>
</div>

<div class="page-body">
    @if(session('success'))
        <div class="flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('dashboard') }}" class="btn-create" style="background: #1a1714; color: #d4a373; border: 1.5px solid var(--border);">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn-create">
                <i class="fas fa-user-plus"></i> Tambah Karyawan Baru
            </a>
        </div>
    </div>

    <div class="user-table-card">
        <div style="overflow-x: auto;">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tanggal Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td><span class="role-badge">Karyawan</span></td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <div style="display: flex;">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #999;">Belum ada akun karyawan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>

</body>
</html>
