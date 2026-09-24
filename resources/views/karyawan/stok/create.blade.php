<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Stok - Aula Coffee Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/jpeg">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

@include('components.navbar')

<style>
    .stok-panel { display: grid; grid-template-columns: 1fr 380px; gap: 24px; align-items: start; }

    .form-card, .info-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .card-head {
        padding: 18px 24px;
        font-size: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body-p { padding: 24px; }

    .form-group { margin-bottom: 20px; }
    label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 7px; }

    select, input[type="number"], textarea {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid #e0dbd4;
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Outfit', sans-serif;
        color: #2c2c2c;
        background: #fafaf9;
        transition: border-color .2s;
        outline: none;
    }

    select:focus, input:focus, textarea:focus {
        border-color: #d4a373;
        background: #fff;
    }

    .tipe-toggle {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .tipe-btn {
        padding: 14px;
        border-radius: 12px;
        border: 2px solid #e0dbd4;
        background: #fafaf9;
        text-align: center;
        cursor: pointer;
        transition: all .2s;
        font-size: 14px;
        font-weight: 600;
        color: #777;
        user-select: none;
    }

    .tipe-btn.masuk.active  { border-color: #27ae60; background: #e8f5e9; color: #27ae60; }
    .tipe-btn.keluar.active { border-color: #e53935; background: #fdecea; color: #e53935; }
    .tipe-btn:hover { opacity: .85; }

    input[type="hidden"] {}

    /* Stok preview */
    .stok-preview {
        background: #f8f5f0;
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 20px;
        display: none;
        border: 1.5px solid #e8d8c4;
    }

    .stok-preview.show { display: block; }
    .sp-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 13.5px; }
    .sp-row:last-child { margin-bottom: 0; }
    .sp-label { color: #888; }
    .sp-val { font-weight: 700; font-size: 15px; }
    .sp-so { color: #e53935; }
    .sp-ok { color: #27ae60; }
    .so-warning {
        background: #fdecea;
        border: 1.5px solid #e53935;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        color: #c62828;
        margin-top: 10px;
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background: #d4a373;
        color: #1a1714;
        border: none;
        border-radius: 50px;
        font-size: 15px;
        font-weight: 700;
        font-family: 'Outfit', sans-serif;
        cursor: pointer;
        transition: background .2s, transform .2s;
    }

    .btn-submit:hover { background: #b5835a; transform: translateY(-2px); }

    /* Riwayat stok */
    .riwayat-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid #f0ede8;
        font-size: 13px;
    }

    .riwayat-item:last-child { border-bottom: none; }
    .ri-nama { font-weight: 600; color: #2c2c2c; margin-bottom: 3px; }
    .ri-ket { color: #999; font-size: 12px; }
    .ri-badge {
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 11.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-masuk  { background: #e8f5e9; color: #27ae60; }
    .badge-keluar { background: #fdecea; color: #e53935; }

    /* SO alert flash */
    .so-toast {
        position: fixed;
        top: 80px;
        right: 20px;
        background: #c62828;
        color: white;
        padding: 14px 20px;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        font-size: 14px;
        font-weight: 600;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideIn .4s ease-out;
        max-width: 340px;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(60px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    .nav-link-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #888;
        font-size: 13px;
        text-decoration: none;
        margin-bottom: 18px;
        transition: color .2s;
    }
    .nav-link-back:hover { color: #d4a373; }

    @media (max-width: 768px) {
        .stok-panel { grid-template-columns: 1fr; }
    }
</style>

<div class="page-header">
    <h1><i class="fas fa-exchange-alt" style="color:#d4a373;margin-right:10px;"></i>Input Stok Barang</h1>
    <p>Shift aktif: <strong>{{ $shift ? $shift->awal_shift->format('H:i, d M Y') : '-' }}</strong></p>
</div>

<div class="page-body">

    @if(session('success'))
        <div class="flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    <a href="{{ route('dashboard') }}" class="nav-link-back"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>

    <div class="stok-panel">
        <!-- FORM INPUT -->
        <div class="form-card">
            <div class="card-head" style="background:#1a1714; color:#d4a373;">
                <i class="fas fa-boxes"></i> Form Input / Update Stok
            </div>
            <div class="card-body-p">

                <!-- Stok Preview -->
                <div class="stok-preview" id="stokPreview">
                    <div class="sp-row">
                        <span class="sp-label">Barang</span>
                        <span class="sp-val" id="pvNama">-</span>
                    </div>
                    <div class="sp-row">
                        <span class="sp-label">Stok Saat Ini</span>
                        <span class="sp-val" id="pvQty">-</span>
                    </div>
                    <div class="sp-row">
                        <span class="sp-label">Batas SO (Minimum)</span>
                        <span class="sp-val sp-so" id="pvSO">-</span>
                    </div>
                    <div class="sp-row">
                        <span class="sp-label">Prediksi Stok</span>
                        <span class="sp-val" id="pvPrediksi">-</span>
                    </div>
                    <div id="soWarning" class="so-warning" style="display:none;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Peringatan! Stok akan mencapai atau di bawah batas SO. Notifikasi otomatis akan dikirim ke Admin.</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('stok.store') }}" id="stokForm">
                    @csrf

                    <!-- Pilih Barang -->
                    <div class="form-group">
                        <label>Pilih Barang</label>
                        <select name="barang_id" id="barangSelect" required onchange="updatePreview()">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barang as $kategori => $items)
                                <optgroup label="{{ $kategori }}">
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}"
                                            data-nama="{{ $item->nama }}"
                                            data-qty="{{ $item->qty }}"
                                            data-so="{{ $item->stok_opname }}"
                                            data-unit="{{ $item->unit }}"
                                            {{ old('barang_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }} — Stok: {{ $item->qty }} {{ $item->unit }}
                                            @if($item->qty <= $item->stok_opname)
                                                ⚠️ SO!
                                            @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jenis Transaksi -->
                    <div class="form-group">
                        <label>Jenis Transaksi</label>
                        <div class="tipe-toggle">
                            <div class="tipe-btn masuk" onclick="setTipe('masuk')" id="btnMasuk">
                                <i class="fas fa-arrow-down"></i> Stok Masuk (IN)
                            </div>
                            <div class="tipe-btn keluar" onclick="setTipe('keluar')" id="btnKeluar">
                                <i class="fas fa-arrow-up"></i> Stok Keluar (OUT)
                            </div>
                        </div>
                        <input type="hidden" name="tipe" id="tipeInput" value="{{ old('tipe', '') }}" required>
                    </div>

                    <!-- Jumlah -->
                    <div class="form-group">
                        <label>Jumlah Barang</label>
                        <input type="number" name="qty" id="qtyInput" min="1" placeholder="Masukkan jumlah..." required value="{{ old('qty') }}" oninput="updatePreview()">
                    </div>

                    <!-- Keterangan -->
                    <div class="form-group">
                        <label>Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="2" placeholder="Contoh: Penggunaan dapur shift pagi...">{{ old('keterangan') }}</textarea>
                    </div>

                    <button type="submit" class="btn-submit" id="btnSubmit">
                        <i class="fas fa-save"></i> Simpan Transaksi Stok
                    </button>
                </form>
            </div>
        </div>

        <!-- RIWAYAT TERAKHIR -->
        <div>
            <div class="info-card">
                <div class="card-head" style="background:#27ae60; color:white;">
                    <i class="fas fa-history"></i> Riwayat Stok Terkini
                </div>
                <div class="card-body-p" style="padding:16px 20px;">
                    @php
                        $riwayat = \App\Models\StokMovement::with(['barang', 'user'])
                            ->latest()
                            ->take(12)
                            ->get();
                    @endphp
                    @forelse($riwayat as $r)
                        <div class="riwayat-item">
                            <div>
                                <div class="ri-nama">{{ $r->barang?->nama ?? 'N/A' }}</div>
                                <div class="ri-ket">{{ $r->user?->name }} · {{ $r->tanggal ? \Carbon\Carbon::parse($r->tanggal)->format('H:i, d M') : '-' }}</div>
                                @if($r->keterangan)
                                    <div class="ri-ket">"{{ Str::limit($r->keterangan, 30) }}"</div>
                                @endif
                            </div>
                            <span class="ri-badge {{ $r->tipe == 'masuk' ? 'badge-masuk' : 'badge-keluar' }}">
                                {{ $r->tipe == 'masuk' ? '+' : '-' }}{{ $r->qty }}
                            </span>
                        </div>
                    @empty
                        <p style="color:#aaa; font-size:13px; text-align:center; padding:20px 0;">Belum ada riwayat transaksi.</p>
                    @endforelse
                </div>
            </div>

            <!-- Stok Rendah Alert -->
            @php
                $stokRendah = \App\Models\Barang::with('kategori')->whereRaw('qty <= stok_opname')->get();
            @endphp
            <div id="stok-rendah-alert" class="info-card" style="margin-top:18px;{{ $stokRendah->count() === 0 ? 'display:none;' : '' }}">
                <div class="card-head" style="background:#e53935; color:white;">
                    <i class="fas fa-exclamation-triangle"></i> Stok Mencapai SO (<span id="stok-rendah-count">{{ $stokRendah->count() }}</span> Item)
                </div>
                <div id="stok-rendah-list" class="card-body-p" style="padding:16px 20px;">
                    @foreach($stokRendah as $b)
                    <div class="riwayat-item">
                        <div>
                            <div class="ri-nama">{{ $b->nama }}</div>
                            <div class="ri-ket">{{ $b->kategori?->nama }}</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-weight:700; color:#e53935;">{{ $b->qty }} {{ $b->unit }}</div>
                            <div style="font-size:11px; color:#aaa;">SO: {{ $b->stok_opname }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Barang data map
    const barangData = {
        @foreach($barang as $kategori => $items)
            @foreach($items as $item)
                {{ $item->id }}: {
                    nama: "{{ $item->nama }}",
                    qty: {{ $item->qty }},
                    so: {{ $item->stok_opname }},
                    unit: "{{ $item->unit }}"
                },
            @endforeach
        @endforeach
    };

    let selectedTipe = '{{ old("tipe", "") }}';

    function setTipe(tipe) {
        selectedTipe = tipe;
        document.getElementById('tipeInput').value = tipe;
        document.getElementById('btnMasuk').classList.toggle('active', tipe === 'masuk');
        document.getElementById('btnKeluar').classList.toggle('active', tipe === 'keluar');
        updatePreview();
    }

    function updatePreview() {
        const id = document.getElementById('barangSelect').value;
        const qty = parseInt(document.getElementById('qtyInput').value) || 0;
        const preview = document.getElementById('stokPreview');
        const soWarning = document.getElementById('soWarning');

        if (!id || !barangData[id]) {
            preview.classList.remove('show');
            return;
        }

        const b = barangData[id];
        preview.classList.add('show');
        document.getElementById('pvNama').textContent = b.nama;
        document.getElementById('pvQty').textContent = b.qty + ' ' + b.unit;
        document.getElementById('pvSO').textContent = b.so + ' ' + b.unit;

        // Prediksi stok
        let prediksi = b.qty;
        if (selectedTipe === 'masuk')  prediksi = b.qty + qty;
        if (selectedTipe === 'keluar') prediksi = b.qty - qty;

        const pvP = document.getElementById('pvPrediksi');
        pvP.textContent = prediksi + ' ' + b.unit;
        pvP.className = 'sp-val ' + (prediksi <= b.so ? 'sp-so' : 'sp-ok');

        // Warning jika prediksi <= SO
        soWarning.style.display = (prediksi <= b.so && qty > 0) ? 'flex' : 'none';
    }

    // Init tipe if old value exists
    if (selectedTipe) setTipe(selectedTipe);

    function escapeHtml(value) {
        return String(value).replace(/[&<>'"]/g, character => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;'
        }[character]));
    }

    async function refreshLowStockAlert() {
        try {
            const response = await fetch('{{ route('api.stok-rendah') }}', {
                headers: { 'Accept': 'application/json' },
                cache: 'no-store'
            });
            if (!response.ok) return;

            const result = await response.json();
            const alert = document.getElementById('stok-rendah-alert');
            const count = document.getElementById('stok-rendah-count');
            const list = document.getElementById('stok-rendah-list');
            if (!alert || !count || !list) return;

            count.textContent = result.count;
            alert.style.display = result.count > 0 ? '' : 'none';
            list.innerHTML = result.items.map(item => `
                <div class="riwayat-item">
                    <div>
                        <div class="ri-nama">${escapeHtml(item.nama)}</div>
                        <div class="ri-ket">${escapeHtml(item.kategori)}</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-weight:700; color:#e53935;">${item.qty} ${escapeHtml(item.unit)}</div>
                        <div style="font-size:11px; color:#aaa;">SO: ${item.stok_opname}</div>
                    </div>
                </div>
            `).join('');
        } catch (error) {
            console.error('Gagal memperbarui peringatan stok rendah:', error);
        }
    }

    setInterval(refreshLowStockAlert, 5000);

    // Show SO toast jika ada notif baru dari session
    @if(session('so_warning'))
    const toast = document.createElement('div');
    toast.className = 'so-toast';
    toast.innerHTML = '<i class="fas fa-exclamation-triangle"></i> {{ session("so_warning") }}';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
    @endif
</script>

</body>
</html>
