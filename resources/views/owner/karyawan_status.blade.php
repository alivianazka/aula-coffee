<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Karyawan - Aula Coffee Club</title>
    <link rel="icon" href="{{ asset('logo_aula_cofee.jpeg') }}" type="image/jpeg">
</head>
<body>

@include('components.navbar')

<div class="page-header">
    <h1><i class="fas fa-users" style="color:#d4a373;margin-right:10px;"></i>Status Karyawan Aktif</h1>
    <p>Pantau siapa saja karyawan yang sedang bertugas saat ini.</p>
</div>

<div class="page-body">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('dashboard') }}" style="color: #888; text-decoration: none; font-size: 14px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- Employee Status Table --}}
    <div style="background: white; border-radius: 18px; padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.07); margin-bottom: 30px;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #f0ede8; text-align: left;">
                        <th style="padding: 12px; font-size: 13px; color: #888; text-transform: uppercase;">Nama Karyawan</th>
                        <th style="padding: 12px; font-size: 13px; color: #888; text-transform: uppercase;">Status</th>
                        <th style="padding: 12px; font-size: 13px; color: #888; text-transform: uppercase;">Mulai Shift</th>
                        <th style="padding: 12px; font-size: 13px; color: #888; text-transform: uppercase;">Durasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawanStatus as $k)
                        @php
                            $activeShift = $k->shifts->first();
                            $isOnShift = $activeShift ? true : false;
                        @endphp
                        <tr style="border-bottom: 1px solid #f5f2ee;">
                            <td style="padding: 15px 12px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #d4a373; color: #1a1714; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">
                                        {{ strtoupper(substr($k->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-size: 14px; font-weight: 700; color: #2c2c2c;">{{ $k->name }}</div>
                                        <div style="font-size: 12px; color: #999;">{{ $k->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 15px 12px;">
                                @if($isOnShift)
                                    <span style="background: #e8f5e9; color: #27ae60; padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 700;">
                                        <i class="fas fa-circle" style="font-size: 8px; margin-right: 4px;"></i> ON SHIFT
                                    </span>
                                @else
                                    <span style="background: #f5f5f5; color: #999; padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 700;">
                                        OFF SHIFT
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 15px 12px; font-size: 13px; color: #555;">
                                {{ $isOnShift ? $activeShift->awal_shift->format('H:i') : '-' }}
                            </td>
                            <td style="padding: 15px 12px; font-size: 13px; color: #555;">
                                @if($isOnShift)
                                    @php
                                        $diff = $activeShift->awal_shift->diff(now());
                                        $hours = $diff->h + ($diff->days * 24);
                                        $duration = $hours . 'j ' . $diff->i . 'm';
                                    @endphp
                                    {{ $duration }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 30px; text-align: center; color: #999; font-size: 14px;">Belum ada data karyawan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
