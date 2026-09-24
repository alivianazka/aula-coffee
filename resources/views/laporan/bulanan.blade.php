<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan - Aula Coffe Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container"><a class="navbar-brand" href="{{ route('home') }}"><img src="{{ asset('logo_aula_cofee.jpeg') }}" alt="Logo" style="height: 30px; border-radius: 50%; margin-right: 5px; vertical-align: middle; padding-bottom: 3px;"> Aula Coffe Club</a></div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4">Laporan Bulanan</h2>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok Awal</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Stok Akhir</th>
                        <th>Input Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $item)
                        <tr>
                            <td>{{ $item->barang->nama }}</td>
                            <td>{{ $item->barang->kategori->nama }}</td>
                            <td>{{ $item->stok_awal }}</td>
                            <td>{{ $item->stok_masuk }}</td>
                            <td>{{ $item->stok_keluar }}</td>
                            <td><strong>{{ $item->stok_akhir }}</strong></td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <form method="POST" action="{{ route('laporan.download-bulanan') }}" class="mt-3">
            @csrf
            <input type="hidden" name="bulan" value="{{ request('bulan') }}">
            <button type="submit" class="btn btn-success"><i class="fas fa-file-excel"></i> Download Excel</button>
            <a href="{{ route('laporan.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
