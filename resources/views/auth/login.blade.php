<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aula Coffee Club</title>
    <meta name="description" content="Login Sistem Stok Barang Aula Coffee Club">
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/jpeg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    <style>
        :root {
            --primary: #d4a373;
            --primary-hover: #b5835a;
            --bg-dark: #1a1714;
            --text-light: #fefae0;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            height: 100%;
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
        }

        .bg-image {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #3b2a1a 0%, #1a1714 60%, #0d0b09 100%);
            z-index: 0;
        }

        .bg-image::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=1400&q=70&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            opacity: 0;
            animation: bgFadeIn 1.2s ease 0.3s forwards;
        }

        @keyframes bgFadeIn { to { opacity: 1; } }

        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 7, 4, 0.82);
            z-index: 1;
        }

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

        .login-card {
            background: rgba(28, 24, 20, 0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(212, 163, 115, 0.15);
            border-radius: 24px;
            width: 100%;
            max-width: 420px;
            padding: 40px 36px;
            animation: slideUp 0.5s ease-out both;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            margin-bottom: 32px;
            text-align: center;
        }

        .logo-wrap {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 2.5px solid var(--primary);
            overflow: hidden;
            background: #fff;
            box-shadow: 0 6px 20px rgba(212,163,115,0.3);
        }

        .logo-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
        }

        .logo-section h1 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-light);
            line-height: 1.2;
        }

        .logo-section p {
            font-size: 13px;
            color: #999;
            font-weight: 300;
        }

        /* Error alert */
        .alert {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.35);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            color: #ff6b7a;
            font-size: 13.5px;
        }

        .alert p { margin: 2px 0; }

        /* Form */
        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #ccc;
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 14px;
            pointer-events: none;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px 12px 40px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            color: var(--text-light);
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            transition: border-color .2s, background .2s;
            outline: none;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--primary);
            background: rgba(212,163,115,0.07);
        }

        input.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            color: #ff6b7a;
            font-size: 12px;
            margin-top: 5px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .remember-row input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .remember-row label {
            margin: 0;
            font-size: 13px;
            color: #aaa;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: var(--primary);
            color: #1a1714;
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: background .25s, transform .25s, box-shadow .25s;
            box-shadow: 0 6px 20px rgba(212,163,115,0.3);
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(212,163,115,0.4);
        }

        .btn-submit:active { transform: translateY(0); }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            color: #888;
            text-decoration: none;
            transition: color .2s;
        }

        .back-link:hover { color: var(--primary); }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card { padding: 30px 22px; border-radius: 20px; }
        }
    </style>
</head>
<body>
    <div class="bg-image"></div>
    <div class="overlay"></div>

    <div class="page">
        <div class="login-card">

            <!-- Logo & Title -->
            <div class="logo-section">
                <div class="logo-wrap">
                    <img src="{{ asset('logo_aula_cofee.jpeg') }}" alt="Logo Aula Coffee Club" width="90" height="90" loading="eager">
                </div>
                <h1>Aula Coffee Club</h1>
                <p>Sistem Manajemen Stok Barang</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               autocomplete="email"
                               required
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                    </div>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="••••••••"
                               autocomplete="current-password"
                               required
                               class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                    </div>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat Saya</label>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>

            <a href="{{ route('home') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>

        </div>
    </div>
</body>
</html>
