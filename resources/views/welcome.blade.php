<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aula Coffee Club - Sistem Stok Barang</title>
    <meta name="description" content="Sistem Manajemen Stok Barang Aula Coffee Club">
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/jpeg">
    <!-- Preconnect to speed up font loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <!-- Font Awesome deferred for performance -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    <style>
        :root {
            --primary: #d4a373;
            --primary-hover: #b5835a;
            --bg-dark: #1a1714;
            --bg-card: rgba(28, 24, 20, 0.75);
            --text-light: #fefae0;
        }

        *, *::before, *::after {
            margin: 0; padding: 0; box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-light);
            overflow: hidden;
        }

        /* ── Background ── */
        .bg-image {
            position: fixed;
            inset: 0;
            /* Use a CSS gradient as fallback while image loads */
            background: linear-gradient(135deg, #3b2a1a 0%, #1a1714 60%, #0d0b09 100%);
            z-index: 0;
        }

        .bg-image::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=1400&q=70&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            /* Fade in after image loads */
            opacity: 0;
            animation: bgFadeIn 1.2s ease 0.3s forwards;
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(100deg, rgba(15,10,6,0.97) 0%, rgba(15,10,6,0.7) 55%, rgba(15,10,6,0.4) 100%);
            z-index: 1;
        }

        /* ── Layout ── */
        .page {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 1140px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
            animation: slideUp 0.6s ease-out both;
        }

        /* ── Hero ── */
        .hero {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .logo-wrap {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 3px solid var(--primary);
            box-shadow: 0 8px 30px rgba(212,163,115,0.35);
            overflow: hidden;
            background: #fff;
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .hero h1 {
            font-size: clamp(32px, 4.5vw, 56px);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -1px;
        }

        .hero h1 span { color: var(--primary); }

        .hero p {
            font-size: clamp(14px, 1.8vw, 17px);
            color: #ccc;
            line-height: 1.65;
            font-weight: 300;
            max-width: 440px;
        }

        .btn-masuk {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--primary);
            color: #1a1714;
            padding: 14px 36px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            width: fit-content;
            transition: transform .25s, background .25s, box-shadow .25s;
            box-shadow: 0 8px 24px rgba(212,163,115,0.3);
        }

        .btn-masuk:hover {
            transform: translateY(-3px);
            background: var(--primary-hover);
            box-shadow: 0 14px 32px rgba(212,163,115,0.4);
        }

        /* ── Feature Cards ── */
        .features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.06);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 22px 20px;
            border-radius: 18px;
            transition: transform .25s, border-color .25s, background .25s;
            will-change: transform;
        }

        .card:hover {
            transform: translateY(-5px);
            border-color: rgba(212,163,115,0.35);
            background: rgba(35,28,22,0.85);
        }

        .card-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            background: rgba(212,163,115,0.12);
            color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            margin-bottom: 14px;
        }

        .card h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 7px;
            color: #fff;
        }

        .card p {
            font-size: 12.5px;
            color: #999;
            line-height: 1.55;
            font-weight: 300;
        }

        /* ── Animations ── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes bgFadeIn {
            to { opacity: 1; }
        }

        /* ── Responsive: Tablet ── */
        @media (max-width: 900px) {
            html, body { overflow: auto; }
            .page { padding: 30px 20px 40px; align-items: flex-start; }
            .container { grid-template-columns: 1fr; gap: 32px; }
            .features { grid-template-columns: 1fr 1fr; }
            .hero p { max-width: 100%; }
        }

        /* ── Responsive: Mobile ── */
        @media (max-width: 520px) {
            .logo-wrap { width: 100px; height: 100px; }
            .features { grid-template-columns: 1fr; }
            .btn-masuk { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

    <div class="bg-image"></div>
    <div class="overlay"></div>

    <div class="page">
        <div class="container">

            <!-- LEFT: Hero -->
            <div class="hero">
                <div class="logo-wrap">
                    <img src="{{ asset('logo_aula_cofee.jpeg') }}"
                         alt="Logo Aula Coffee Club"
                         width="130" height="130"
                         loading="eager">
                </div>

                <h1>Aula Coffee Club<br><span>Inventory Management</span></h1>

                <p>Sistem eksklusif untuk memantau, mengelola, dan menganalisis stok barang Aula Coffee Club secara real-time.</p>

                <a href="{{ route('login') }}" class="btn-masuk">
                    Masuk ke Sistem <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- RIGHT: Features -->
            <div class="features">
                <div class="card">
                    <div class="card-icon"><i class="fas fa-boxes"></i></div>
                    <h4>Real-time Stok</h4>
                    <p>Pantau persediaan bahan baku dan perlengkapan secara akurat.</p>
                </div>
                <div class="card">
                    <div class="card-icon"><i class="fas fa-bell"></i></div>
                    <h4>Smart Alerts</h4>
                    <p>Notifikasi otomatis saat stok bahan baku mulai menipis.</p>
                </div>
                <div class="card">
                    <div class="card-icon"><i class="fas fa-chart-line"></i></div>
                    <h4>Laporan Detail</h4>
                    <p>Export laporan harian, bulanan hingga tahunan.</p>
                </div>
                <div class="card">
                    <div class="card-icon"><i class="fas fa-user-shield"></i></div>
                    <h4>Multi-Role</h4>
                    <p>Hak akses khusus untuk Owner, Admin, dan Karyawan Shift.</p>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
