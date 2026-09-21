<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akhiri Shift - Aula Coffe Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-hourglass-end"></i> Akhiri Shift</h5>
                    </div>
                    <div class="card-body">
                        <p class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Anda akan mengakhiri shift dan logout.
                        </p>

                        @if($shift)
                            <div class="mb-4">
                                <h6>Detail Shift:</h6>
                                <p class="text-muted small">
                                    <strong>Mulai:</strong> {{ $shift->awal_shift->format('d-m-Y H:i:s') }}<br>
                                    <strong>Durasi:</strong> {{ $shift->awal_shift->diffInHours(now()) }} jam
                                </p>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('shift.end-shift') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan (Opsional)</label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="3" 
                                          placeholder="Catatan atau laporan shift..."></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-check"></i> Konfirmasi Akhiri Shift
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
