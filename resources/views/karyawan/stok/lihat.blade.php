<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Stok - Aula Coffe Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}"><img src="{{ asset('logo_aula_cofee.jpeg') }}" alt="Logo" style="height: 30px; border-radius: 50%; margin-right: 5px; vertical-align: middle; padding-bottom: 3px;"> Aula Coffe Club</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4"><i class="fas fa-boxes"></i> Daftar Stok Barang</h2>

        @if(count($barang) > 0)
            @foreach($barang as $kategori => $items)
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ $kategori }}</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Stok Opname</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->nama }}</td>
                                        <td><strong>{{ $item->qty }} {{ $item->unit }}</strong></td>
                                        <td>{{ $item->stok_opname }} {{ $item->unit }}</td>
                                        <td>
                                            @if($item->isStockLow())
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> Rendah
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> Aman
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Belum ada data barang
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('stok.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Input Stok
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
