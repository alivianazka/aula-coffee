<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Aula Coffee Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/jpeg">
</head>
<body>

@include('components.navbar')

<style>
    .laporan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 22px;
        margin-bottom: 30px;
    }

    .laporan-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        transition: transform .25s, box-shadow .25s;
    }
    .laporan-card:hover { transform: translateY(-4px); box-shadow: 0 10px 28px rgba(0,0,0,0.12); }

    .lcard-head {
        padding: 20px 24px 16px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .lcard-icon {
        width: 46px; height: 46px;
        border-radius: 13px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .lcard-head h3 { font-size: 17px; font-weight: 800; margin: 0; }
    .lcard-head p  { font-size: 12.5px; margin: 2px 0 0; opacity: .7; }

    .lcard-body { padding: 0 24px 24px; }

    .form-lbl {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        color: #777;
        margin-bottom: 7px;
    }

    .form-input {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid #e8e3dc;
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Outfit', sans-serif;
        background: #faf8f5;
        margin-bottom: 14px;
        outline: none;
        transition: border-color .2s;
        box-sizing: border-box;
    }
    .form-input:focus { border-color: #d4a373; background: #fff; }

    .btn-lihat, .btn-dl {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        font-family: 'Outfit', sans-serif;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: opacity .2s, transform .2s;
        margin-bottom: 8px;
        text-decoration: none;
    }
    .btn-lihat:hover, .btn-dl:hover { opacity: .88; transform: translateY(-1px); }
    .btn-dl { background: #1a1714; color: #d4a373; }

    .divider { height: 1px; background: #f0ede8; margin: 16px 0; }

    /* Result table */
    .result-section {
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.07);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .result-head {
        background: #1a1714;
        color: #d4a373;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .result-head h2 { font-size: 16px; font-weight: 700; margin: 0; }

    .result-table { width: 100%; border-collapse: collapse; }
    .result-table thead tr { background: #f8f4ef; }
    .result-table th { padding: 12px 16px; font-size: 12px; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: .5px; white-space: nowrap; }
    .result-table tbody tr { border-bottom: 1px solid #f5f2ee; transition: background .15s; }
    .result-table tbody tr:hover { background: #faf8f5; }
    .result-table td { padding: 13px 16px; font-size: 13.5px; color: #2c2c2c; }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #bbb;
    }
    .empty-state i { font-size: 40px; display: block; margin-bottom: 12px; }

    @media (max-width: 640px) {
        .laporan-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="page-header">
    <h1><i class="fas fa-chart-bar" style="color:#d4a373;margin-right:10px;"></i>Laporan Stok Barang</h1>
    <p>Generate dan unduh laporan harian, bulanan, serta tahunan stok Aula Coffee Club.</p>
</div>

<div class="page-body">

    <a href="{{ route('dashboard') }}" style="display:inline-flex;align-items:center;gap:7px;color:#888;font-size:13px;text-decoration:none;margin-bottom:22px;">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>

    <div class="laporan-grid">

        {{-- ── HARIAN ── --}}
        <div class="laporan-card">
            <div class="lcard-head" style="background: linear-gradient(135deg, #1a1714 0%, #2c2518 100%); color:white;">
                <div class="lcard-icon" style="background:rgba(212,163,115,0.2); color:#d4a373;">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div>
                    <h3 style="color:white;">Laporan Harian</h3>
                    <p>Laporan stok per hari</p>
                </div>
            </div>
            <div class="lcard-body" style="padding-top:20px;">
                <form method="POST" action="{{ route('laporan.harian') }}" id="formHarian">
                    @csrf
                    <label class="form-lbl">Pilih Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal_harian" class="form-input"
                           value="{{ date('Y-m-d') }}" required>
                    <button type="submit" class="btn-lihat" style="background:#d4a373; color:#1a1714;">
                        <i class="fas fa-search"></i> Lihat Laporan
                    </button>
                </form>
                <div class="divider"></div>
                <form method="POST" action="{{ route('laporan.download-harian') }}">
                    @csrf
                    <input type="hidden" name="tanggal" id="dl_tanggal_harian">
                    <button type="submit" class="btn-dl"
                        onclick="document.getElementById('dl_tanggal_harian').value = document.getElementById('tanggal_harian').value">
                        <i class="fas fa-file-csv"></i> Download CSV
                    </button>
                </form>
            </div>
        </div>

        {{-- ── BULANAN ── --}}
        <div class="laporan-card">
            <div class="lcard-head" style="background: linear-gradient(135deg, #8e44ad 0%, #6c3483 100%); color:white;">
                <div class="lcard-icon" style="background:rgba(255,255,255,0.15); color:white;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <h3 style="color:white;">Laporan Bulanan</h3>
                    <p>Rekap stok per bulan</p>
                </div>
            </div>
            <div class="lcard-body" style="padding-top:20px;">
                <form method="POST" action="{{ route('laporan.bulanan') }}" id="formBulanan">
                    @csrf
                    <label class="form-lbl">Pilih Bulan</label>
                    <input type="month" name="bulan" id="bulan" class="form-input"
                           value="{{ date('Y-m') }}" required>
                    <button type="submit" class="btn-lihat" style="background:#8e44ad; color:white;">
                        <i class="fas fa-search"></i> Lihat Laporan
                    </button>
                </form>
                <div class="divider"></div>
                <form method="POST" action="{{ route('laporan.download-bulanan') }}">
                    @csrf
                    <input type="hidden" name="bulan" id="dl_bulan">
                    <button type="submit" class="btn-dl"
                        onclick="document.getElementById('dl_bulan').value = document.getElementById('bulan').value">
                        <i class="fas fa-file-csv"></i> Download CSV
                    </button>
                </form>
            </div>
        </div>

        {{-- ── TAHUNAN ── --}}
        <div class="laporan-card">
            <div class="lcard-head" style="background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%); color:white;">
                <div class="lcard-icon" style="background:rgba(255,255,255,0.15); color:white;">
                    <i class="fas fa-calendar"></i>
                </div>
                <div>
                    <h3 style="color:white;">Laporan Tahunan</h3>
                    <p>Rekap stok per tahun</p>
                </div>
            </div>
            <div class="lcard-body" style="padding-top:20px;">
                <form method="POST" action="{{ route('laporan.tahunan') }}" id="formTahunan">
                    @csrf
                    <label class="form-lbl">Pilih Tahun</label>
                    <input type="number" name="tahun" id="tahun" class="form-input"
                           min="2020" max="{{ date('Y') }}" value="{{ date('Y') }}" required>
                    <button type="submit" class="btn-lihat" style="background:#1565c0; color:white;">
                        <i class="fas fa-search"></i> Lihat Laporan
                    </button>
                </form>
                <div class="divider"></div>
                <form method="POST" action="{{ route('laporan.download-tahunan') }}">
                    @csrf
                    <input type="hidden" name="tahun" id="dl_tahun">
                    <button type="submit" class="btn-dl"
                        onclick="document.getElementById('dl_tahun').value = document.getElementById('tahun').value">
                        <i class="fas fa-file-csv"></i> Download CSV
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

</body>
</html>
