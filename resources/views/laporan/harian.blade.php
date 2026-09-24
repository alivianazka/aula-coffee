<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian - Aula Coffee Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/jpeg">
</head>
<body>

@include('components.navbar')

<style>
    .result-table-wrap {
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.07);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .result-head {
        background: #1a1714;
        color: #d4a373;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .result-head h2 { font-size: 17px; font-weight: 800; margin: 0; }
    .result-head .sub { font-size: 13px; opacity: .7; margin-top: 2px; }

    .rtable { width: 100%; border-collapse: collapse; }
    .rtable thead tr { background: #f8f4ef; }
    .rtable th { padding: 12px 16px; font-size: 11.5px; font-weight: 700; color: #999; text-transform: uppercase; letter-spacing: .5px; white-space: nowrap; border-bottom: 2px solid #f0ede8; }
    .rtable tbody tr { border-bottom: 1px solid #f5f2ee; transition: background .15s; }
    .rtable tbody tr:hover { background: #faf8f5; }
    .rtable td { padding: 13px 16px; font-size: 13.5px; color: #2c2c2c; }
    .rtable tfoot tr { background: #1a1714; }
    .rtable tfoot td { padding: 12px 16px; font-size: 13px; color: #d4a373; font-weight: 700; }

    .empty-state { text-align:center; padding:60px 20px; color:#bbb; }
    .empty-state i { font-size:44px; display:block; margin-bottom:14px; }

    .btn-back {
        display: inline-flex; align-items: center; gap: 8px;
        background: #1a1714; color: #d4a373;
        padding: 10px 22px; border-radius: 10px;
        font-size: 13.5px; font-weight: 600;
        text-decoration: none; border: 1.5px solid rgba(212,163,115,0.3);
        transition: background .2s;
    }
    .btn-back:hover { background: #2c2518; color: #d4a373; }

    .btn-dl-inline {
        display: inline-flex; align-items: center; gap: 8px;
        background: #27ae60; color: white;
        padding: 10px 22px; border-radius: 10px;
        font-size: 13.5px; font-weight: 600;
        border: none; cursor: pointer;
        font-family: 'Outfit', sans-serif;
        transition: background .2s;
    }
    .btn-dl-inline:hover { background: #219a52; }

    .action-row { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:4px; }

    @media (max-width: 640px) {
        .result-head { flex-direction: column; align-items: flex-start; }
        .rtable th, .rtable td { padding: 10px 12px; font-size: 12.5px; }
    }
</style>

<div class="page-header">
    <h1><i class="fas fa-calendar-day" style="color:#d4a373;margin-right:10px;"></i>Laporan Harian</h1>
    <p>Periode: <strong>{{ isset($tanggal) ? $tanggal->format('d F Y') : '-' }}</strong></p>
</div>

<div class="page-body">

    <div class="action-row" style="margin-bottom:22px;">
        <a href="{{ route('laporan.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Laporan
        </a>
        @if($laporan->count() > 0)
        <form method="POST" action="{{ route('laporan.download-harian') }}">
            @csrf
            <input type="hidden" name="tanggal" value="{{ isset($tanggal) ? $tanggal->format('Y-m-d') : '' }}">
            <button type="submit" class="btn-dl-inline">
                <i class="fas fa-file-excel"></i> Download Excel
            </button>
        </form>
        @endif
    </div>

    <div class="result-table-wrap">
        <div class="result-head">
            <div>
                <h2><i class="fas fa-calendar-day"></i> Laporan Harian</h2>
                <div class="sub">{{ isset($tanggal) ? $tanggal->format('d F Y') : '-' }} · <span id="jumlah-data">{{ $laporan->count() }}</span> data · diperbarui otomatis</div>
            </div>
        </div>

        <div id="laporan-table-wrap" style="overflow-x:auto;{{ $laporan->count() === 0 ? 'display:none;' : '' }}">
            <table class="rtable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok Awal</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Stok Akhir</th>
                        <th>Input Oleh</th>
                    </tr>
                </thead>
                <tbody id="laporan-body">
                    @foreach($laporan as $i => $item)
                    <tr>
                        <td style="color:#bbb;">{{ $i + 1 }}</td>
                        <td><strong>{{ $item->barang?->nama ?? '-' }}</strong></td>
                        <td style="color:#888;">{{ $item->barang?->kategori?->nama ?? '-' }}</td>
                        <td>{{ $item->stok_awal }}</td>
                        <td style="color:#27ae60; font-weight:700;">+{{ $item->stok_masuk }}</td>
                        <td style="color:#e53935; font-weight:700;">-{{ $item->stok_keluar }}</td>
                        <td><strong>{{ $item->stok_akhir }}</strong></td>
                        <td style="color:#888;">{{ $item->user?->name ?? 'Sistem' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">TOTAL</td>
                        <td id="total-stok-awal">{{ $laporan->sum('stok_awal') }}</td>
                        <td id="total-stok-masuk">+{{ $laporan->sum('stok_masuk') }}</td>
                        <td id="total-stok-keluar">-{{ $laporan->sum('stok_keluar') }}</td>
                        <td id="total-stok-akhir">{{ $laporan->sum('stok_akhir') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div id="laporan-empty-state" class="empty-state" style="{{ $laporan->count() > 0 ? 'display:none;' : '' }}">
            <i class="fas fa-inbox"></i>
            <div style="font-size:16px; font-weight:700; color:#666;">Tidak ada data laporan</div>
            <div style="font-size:13px; margin-top:6px;">Belum ada transaksi stok pada tanggal yang dipilih.</div>
        </div>
    </div>

</div>

<script>
    const laporanDataUrl = @json(route('laporan.harian.data', ['tanggal' => $tanggal->format('Y-m-d')]));

    async function refreshLaporan() {
        try {
            const response = await fetch(laporanDataUrl, { headers: { 'Accept': 'application/json' } });
            if (!response.ok) return;

            const result = await response.json();
            const body = document.getElementById('laporan-body');
            const count = document.getElementById('jumlah-data');
            const tableWrap = document.getElementById('laporan-table-wrap');
            const emptyState = document.getElementById('laporan-empty-state');
            if (!body || !count || !tableWrap || !emptyState) return;

            count.textContent = result.data.length;
            tableWrap.style.display = result.data.length ? 'block' : 'none';
            emptyState.style.display = result.data.length ? 'none' : 'block';
            document.getElementById('total-stok-awal').textContent = result.data.reduce((total, item) => total + Number(item.stok_awal), 0);
            document.getElementById('total-stok-masuk').textContent = '+' + result.data.reduce((total, item) => total + Number(item.stok_masuk), 0);
            document.getElementById('total-stok-keluar').textContent = '-' + result.data.reduce((total, item) => total + Number(item.stok_keluar), 0);
            document.getElementById('total-stok-akhir').textContent = result.data.reduce((total, item) => total + Number(item.stok_akhir), 0);
            body.innerHTML = result.data.map((item, index) => `
                <tr>
                    <td style="color:#bbb;">${index + 1}</td>
                    <td><strong>${item.barang}</strong></td>
                    <td style="color:#888;">${item.kategori}</td>
                    <td>${item.stok_awal}</td>
                    <td style="color:#27ae60; font-weight:700;">+${item.stok_masuk}</td>
                    <td style="color:#e53935; font-weight:700;">-${item.stok_keluar}</td>
                    <td><strong>${item.stok_akhir}</strong></td>
                    <td style="color:#888;">${item.user}</td>
                </tr>
            `).join('');
        } catch (error) {
            console.error('Gagal memperbarui laporan:', error);
        }
    }

    setInterval(refreshLaporan, 5000);
</script>

</body>
</html>
