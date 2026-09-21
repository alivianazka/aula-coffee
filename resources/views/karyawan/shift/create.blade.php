<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mulai Shift - Aula Coffe Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .shift-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .shift-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .shift-body {
            padding: 40px;
        }
        .btn-start-shift {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 5px;
            width: 100%;
        }
        .btn-start-shift:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
        }
    </style>
</head>
<body>
    <div class="shift-container">
        <div class="shift-header">
            <i class="fas fa-hourglass-start" style="font-size: 50px; margin-bottom: 15px;"></i>
            <h1>Mulai Shift</h1>
            <p class="mb-0">Aula Coffe Club</p>
        </div>
        <div class="shift-body">
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle"></i> 
                Klik tombol di bawah untuk memulai shift Anda
            </div>

            <form method="POST" action="{{ route('shift.store') }}">
                @csrf
                <p class="text-center text-muted mb-4">
                    Waktu Saat Ini: <strong id="current-time"></strong>
                </p>

                <button type="submit" class="btn btn-start-shift text-white mb-3">
                    <i class="fas fa-play"></i> MULAI SHIFT SEKARANG
                </button>
            </form>

            <hr>

            <p class="text-center text-muted">
                <a href="{{ route('dashboard') }}" class="text-decoration-none">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </p>
        </div>
    </div>

    <script>
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleString('id-ID', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('current-time').textContent = timeString;
        }
        
        updateTime();
        setInterval(updateTime, 1000);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
