<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Stok - Aula Coffee Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/jpeg">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

@include('components.navbar')

<style>
    .notif-empty {
        text-align: center;
        padding: 60px 20px;
        color: #aaa;
    }
    .notif-empty i { font-size: 48px; color: #ddd; margin-bottom: 16px; }

    .notif-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        margin-bottom: 14px;
        border-left: 4px solid transparent;
        display: flex;
        align-items: stretch;
        transition: box-shadow .2s;
    }
    .notif-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
    .notif-card.unread { border-left-color: #e53935; background: #fffafa; }
    .notif-card.read   { border-left-color: #27ae60; }

    .notif-icon-col {
        width: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 22px;
    }
    .notif-card.unread .notif-icon-col { color: #e53935; background: #fdecea; }
    .notif-card.read   .notif-icon-col { color: #27ae60; background: #e8f5e9; }

    .notif-body {
        flex: 1;
        padding: 16px 18px;
    }
    .notif-judul {
        font-size: 15px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 5px;
    }
    .notif-pesan {
        font-size: 13px;
        color: #666;
        line-height: 1.5;
        margin-bottom: 8px;
    }
    .notif-meta {
        font-size: 11.5px;
        color: #aaa;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .notif-actions {
        display: flex;
        align-items: center;
        padding: 16px;
        gap: 8px;
        flex-shrink: 0;
    }

    .btn-baca {
        padding: 8px 16px;
        background: #1a1714;
        color: #d4a373;
        border: none;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Outfit', sans-serif;
        transition: background .2s;
        white-space: nowrap;
    }
    .btn-baca:hover { background: #2c2518; }

    .status-badge {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11.5px;
        font-weight: 700;
    }
    .badge-unread { background: #fdecea; color: #e53935; }
    .badge-read   { background: #e8f5e9; color: #27ae60; }

    .topbar-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .btn-baca-semua {
        padding: 10px 20px;
        background: #1a1714;
        color: #d4a373;
        border: 1.5px solid rgba(212,163,115,0.3);
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Outfit', sans-serif;
        transition: background .2s;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-baca-semua:hover { background: #2c2518; color: #d4a373; }

    /* Real-time badge di header */
    .live-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fdecea;
        color: #e53935;
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 700;
        margin-left: 12px;
    }
    .pulse-dot {
        width: 8px; height: 8px;
        background: #e53935;
        border-radius: 50%;
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: .4; transform: scale(.7); }
    }
</style>

<div class="page-header">
    <h1>
        <i class="fas fa-bell" style="color:#d4a373;margin-right:10px;"></i>
        Notifikasi Stok
        @php $unreadCount = $notifikasi->where('status','belum_dibaca')->count(); @endphp
        @if($unreadCount > 0)
            <span class="live-badge" id="liveBadge">
                <span class="pulse-dot"></span>
                {{ $unreadCount }} baru
            </span>
        @endif
    </h1>
    <p>Notifikasi otomatis saat stok barang mencapai batas SO (Stok Opname).</p>
</div>

<div class="page-body">

    <div class="topbar-actions">
        <a href="{{ route('dashboard') }}" class="nav-link-back" style="margin:0; font-size:14px; color:#888; display:flex; align-items:center; gap:7px; text-decoration:none;">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifikasi.read-all') }}">
                @csrf
                <button type="submit" class="btn-baca-semua">
                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    @if($notifikasi->count() > 0)
        @foreach($notifikasi as $item)
            <div class="notif-card {{ $item->status == 'belum_dibaca' ? 'unread' : 'read' }}">
                <div class="notif-icon-col">
                    <i class="fas fa-{{ $item->status == 'belum_dibaca' ? 'exclamation-triangle' : 'check-circle' }}"></i>
                </div>
                <div class="notif-body">
                    <div class="notif-judul">{{ $item->judul }}</div>
                    <div class="notif-pesan">{{ $item->pesan }}</div>
                    <div class="notif-meta">
                        <span><i class="fas fa-box"></i> {{ $item->barang?->nama ?? 'Barang dihapus' }}</span>
                        <span><i class="fas fa-clock"></i> {{ $item->created_at->diffForHumans() }}</span>
                        <span class="status-badge {{ $item->status == 'belum_dibaca' ? 'badge-unread' : 'badge-read' }}">
                            {{ $item->status == 'belum_dibaca' ? 'Belum Dibaca' : 'Sudah Dibaca' }}
                        </span>
                    </div>
                </div>
                @if($item->status == 'belum_dibaca')
                    <div class="notif-actions">
                        <form method="POST" action="{{ route('notifikasi.read', $item->id) }}">
                            @csrf
                            <button type="submit" class="btn-baca">
                                <i class="fas fa-check"></i> Baca
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach

        <div style="margin-top:16px;">
            {{ $notifikasi->links() }}
        </div>
    @else
        <div class="notif-empty">
            <div><i class="fas fa-bell-slash"></i></div>
            <div style="font-size:18px; font-weight:700; color:#555; margin-bottom:8px;">Tidak Ada Notifikasi</div>
            <div style="font-size:14px;">Semua stok barang masih dalam kondisi aman.</div>
        </div>
    @endif

</div>

{{-- Real-time polling: cek notifikasi baru setiap 15 detik --}}
<script>
let lastCount = {{ $unreadCount }};

setInterval(async () => {
    try {
        const res  = await fetch('/api/notifikasi/count', {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });
        const data = await res.json();
        const count = data.count ?? 0;

        if (count !== lastCount) {
            lastCount = count;
            // Reload halaman untuk menampilkan notifikasi terbaru
            window.location.reload();
        }
    } catch (e) {}
}, 15000);
</script>

</body>
</html>
