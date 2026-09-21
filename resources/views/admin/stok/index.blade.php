<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Stok - Aula Coffee Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/jpeg">
</head>
<body>

@include('components.navbar')

<style>
    .filter-bar {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .search-input {
        flex: 1;
        min-width: 220px;
        padding: 10px 16px 10px 40px;
        border: 1.5px solid #e0dbd4;
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Outfit', sans-serif;
        background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%23999' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398l3.85 3.85a1 1 0 0 0 1.415-1.415l-3.868-3.833zm-5.242 1.156a5 5 0 1 1 0-10 5 5 0 0 1 0 10z'/%3E%3C/svg%3E") no-repeat 14px center;
        outline: none;
        transition: border-color .2s;
    }
    .search-input:focus { border-color: #d4a373; }

    .filter-select {
        padding: 10px 14px;
        border: 1.5px solid #e0dbd4;
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Outfit', sans-serif;
        background: white;
        outline: none;
        cursor: pointer;
    }

    /* Kategori Group */
    .kategori-group { margin-bottom: 28px; }

    .kategori-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 700;
        color: #2c2c2c;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f0ede8;
    }

    .kategori-label i { color: #d4a373; }

    /* Stok Table */
    .stok-table {
        width: 100%;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        overflow: hidden;
        border-collapse: collapse;
        table-layout: auto;
    }

    .stok-table thead tr {
        background: #1a1714;
        color: #d4a373;
    }

    .stok-table th {
        padding: 13px 16px;
        font-size: 12.5px;
        font-weight: 600;
        letter-spacing: .5px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .stok-table tbody tr {
        border-bottom: 1px solid #f5f2ee;
        transition: background .15s;
    }

    .stok-table tbody tr:hover { background: #faf8f5; }
    .stok-table tbody tr:last-child { border-bottom: none; }

    .stok-table td {
        padding: 13px 16px;
        font-size: 13.5px;
        color: #2c2c2c;
        vertical-align: middle;
    }

    .stok-bar-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 120px;
    }

    .stok-bar {
        flex: 1;
        height: 6px;
        background: #f0ede8;
        border-radius: 50px;
        overflow: hidden;
    }

    .stok-bar-fill {
        height: 100%;
        border-radius: 50px;
        transition: width .3s;
    }

    .badge-aman { background: #e8f5e9; color: #27ae60; padding: 4px 12px; border-radius: 50px; font-size: 11.5px; font-weight: 700; }
    .badge-so   { background: #fdecea; color: #e53935; padding: 4px 12px; border-radius: 50px; font-size: 11.5px; font-weight: 700; animation: badgePulse 1.5s infinite; }

    @keyframes badgePulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: .6; }
    }

    .summary-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .summary-item {
        background: white;
        border-radius: 14px;
        padding: 16px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        text-align: center;
    }

    .summary-item .s-num { font-size: 26px; font-weight: 800; }
    .summary-item .s-lbl { font-size: 12px; color: #999; margin-top: 2px; }

    .table-wrap { overflow-x: auto; }

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
        transition: background .2s;
    }
    .btn-add:hover { background: #b5835a; }

    @media (max-width: 640px) {
        .stok-table th, .stok-table td { padding: 10px 12px; font-size: 12.5px; }
    }
</style>

<div class="page-header">
    <h1><i class="fas fa-warehouse" style="color:#d4a373;margin-right:10px;"></i>Monitor Stok Barang</h1>
    <p>Pantau kondisi stok semua barang secara real-time.</p>
</div>

<div class="page-body">

    @php
        $allBarang = $barang->flatten();
        $totalItem = $allBarang->count();
        $stokAman  = $allBarang->filter(fn($b) => !$b->isStockLow())->count();
        $stokSO    = $allBarang->filter(fn($b) =>  $b->isStockLow())->count();
    @endphp

    {{-- Summary --}}
    <div class="summary-bar">
        <div class="summary-item">
            <div class="s-num">{{ $totalItem }}</div>
            <div class="s-lbl">Total Barang</div>
        </div>
        <div class="summary-item">
            <div class="s-num" style="color:#27ae60;">{{ $stokAman }}</div>
            <div class="s-lbl">Stok Aman</div>
        </div>
        <div class="summary-item">
            <div class="s-num" style="color:#e53935;">{{ $stokSO }}</div>
            <div class="s-lbl">Stok SO / Rendah</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <a href="{{ route('dashboard') }}" class="btn-add" style="background: #1a1714; color: #d4a373; border: 1.5px solid var(--border); margin-bottom: 0;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari nama barang..." oninput="filterTable()">
        <select class="filter-select" id="filterStatus" onchange="filterTable()">
            <option value="">Semua Status</option>
            <option value="aman">Stok Aman</option>
            <option value="so">Stok SO / Rendah</option>
        </select>
    </div>

    {{-- Tables per Kategori --}}
    @foreach($barang as $kategori => $items)
    <div class="kategori-group" data-kategori="{{ strtolower($kategori) }}">
        <div class="kategori-label">
            <i class="fas fa-tag"></i> {{ $kategori }}
            <span style="font-size:12px;color:#aaa;font-weight:400;">({{ $items->count() }} item)</span>
        </div>
        <div class="table-wrap">
            <table class="stok-table">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Stok Awal</th>
                        <th>Total Masuk</th>
                        <th>Total Keluar</th>
                        <th>Stok Saat Ini</th>
                        <th>Batas SO</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    @php
                        $totalMasuk  = $item->stokMovements->where('tipe','masuk')->sum('qty');
                        $totalKeluar = $item->stokMovements->where('tipe','keluar')->sum('qty');
                        $isLow       = $item->isStockLow();
                        $pct         = $item->stok_opname > 0
                            ? min(100, round(($item->qty / max($item->stok_opname * 2, $item->qty)) * 100))
                            : 100;
                        $barColor    = $isLow ? '#e53935' : '#27ae60';
                    @endphp
                    <tr class="stok-row" data-nama="{{ strtolower($item->nama) }}" data-status="{{ $isLow ? 'so' : 'aman' }}">
                        <td><strong>{{ $item->nama }}</strong></td>
                        <td style="color:#888;">{{ $item->unit }}</td>
                        <td>{{ $item->stok_awal }}</td>
                        <td style="color:#27ae60; font-weight:600;">+{{ $totalMasuk }}</td>
                        <td style="color:#e53935; font-weight:600;">-{{ $totalKeluar }}</td>
                        <td>
                            <div class="stok-bar-wrap">
                                <div class="stok-bar">
                                    <div class="stok-bar-fill" style="width:{{ $pct }}%; background:{{ $barColor }};"></div>
                                </div>
                                <strong style="color:{{ $barColor }}; min-width:40px; text-align:right;">{{ $item->qty }}</strong>
                            </div>
                        </td>
                        <td style="color:#e53935;">{{ $item->stok_opname }}</td>
                        <td>
                            @if($isLow)
                                <span class="badge-so"><i class="fas fa-exclamation-triangle"></i> SO!</span>
                            @else
                                <span class="badge-aman"><i class="fas fa-check"></i> Aman</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

</div>

<script>
function filterTable() {
    const q      = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;

    document.querySelectorAll('.stok-row').forEach(row => {
        const nama    = row.dataset.nama;
        const rowStat = row.dataset.status;
        const matchQ  = !q || nama.includes(q);
        const matchS  = !status || rowStat === status;
        row.style.display = (matchQ && matchS) ? '' : 'none';
    });

    // Hide empty kategori groups
    document.querySelectorAll('.kategori-group').forEach(group => {
        const visible = [...group.querySelectorAll('.stok-row')].some(r => r.style.display !== 'none');
        group.style.display = visible ? '' : 'none';
    });
}
</script>

</body>
</html>
