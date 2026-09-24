<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Barang;
use App\Models\StokMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function harian(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $tanggal = Carbon::parse($request->tanggal);
        $laporan = $this->buildReport(
            $tanggal->copy()->startOfDay(),
            $tanggal->copy()->endOfDay(),
            'harian',
            $tanggal
        );

        return view('laporan.harian', compact('laporan', 'tanggal'));
    }

    public function harianData(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $tanggal = Carbon::parse($request->tanggal);
        $laporan = $this->buildReport(
            $tanggal->copy()->startOfDay(),
            $tanggal->copy()->endOfDay(),
            'harian',
            $tanggal
        );

        return response()->json([
            'data' => $laporan->map(fn ($item) => [
                'barang' => $item->barang?->nama ?? '-',
                'kategori' => $item->barang?->kategori?->nama ?? '-',
                'stok_awal' => $item->stok_awal,
                'stok_masuk' => $item->stok_masuk,
                'stok_keluar' => $item->stok_keluar,
                'stok_akhir' => $item->stok_akhir,
                'user' => $item->user?->name ?? 'Sistem',
            ])->values(),
            'updated_at' => now()->format('d/m/Y H:i:s'),
        ]);
    }

    public function bulanan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
        ]);

        list($tahun, $bulan) = explode('-', $request->bulan);
        $periode = Carbon::createFromDate((int) $tahun, (int) $bulan, 1);
        $laporan = $this->buildReport(
            $periode->copy()->startOfMonth(),
            $periode->copy()->endOfMonth(),
            'bulanan',
            $periode->copy()->endOfMonth()
        );

        $periode = $periode->format('F Y');

        return view('laporan.bulanan', compact('laporan', 'periode'));
    }

    public function tahunan(Request $request)
    {
        $request->validate([
            'tahun' => 'required|digits:4|integer|min:2020|max:' . date('Y'),
        ]);

        $tahun = $request->tahun;
        $periode = Carbon::createFromDate((int) $tahun, 1, 1);
        $laporan = $this->buildReport(
            $periode->copy()->startOfYear(),
            $periode->copy()->endOfYear(),
            'tahunan',
            $periode->copy()->endOfYear()
        );

        return view('laporan.tahunan', compact('laporan', 'tahun'));
    }

    public function downloadHarian(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $tanggal = Carbon::parse($request->tanggal);
        $laporan = $this->buildReport(
            $tanggal->copy()->startOfDay(),
            $tanggal->copy()->endOfDay(),
            'harian',
            $tanggal
        );

        return $this->generateSpreadsheet($laporan, 'Laporan Harian - ' . $tanggal->format('d-m-Y'));
    }

    public function downloadBulanan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
        ]);

        list($tahun, $bulan) = explode('-', $request->bulan);
        $periode = Carbon::createFromDate((int) $tahun, (int) $bulan, 1);
        $laporan = $this->buildReport(
            $periode->copy()->startOfMonth(),
            $periode->copy()->endOfMonth(),
            'bulanan',
            $periode->copy()->endOfMonth()
        );

        $periode = $periode->format('F Y');

        return $this->generateSpreadsheet($laporan, 'Laporan Bulanan - ' . $periode);
    }

    public function downloadTahunan(Request $request)
    {
        $request->validate([
            'tahun' => 'required|digits:4|integer|min:2020|max:' . date('Y'),
        ]);

        $periode = Carbon::createFromDate((int) $request->tahun, 1, 1);
        $laporan = $this->buildReport(
            $periode->copy()->startOfYear(),
            $periode->copy()->endOfYear(),
            'tahunan',
            $periode->copy()->endOfYear()
        );

        return $this->generateSpreadsheet($laporan, 'Laporan Tahunan - ' . $request->tahun);
    }

    private function generateSpreadsheet($laporan, $judul)
    {
        $rows = '';
        $totals = [
            'stok_awal' => 0,
            'stok_masuk' => 0,
            'stok_keluar' => 0,
            'stok_akhir' => 0,
        ];

        foreach ($laporan as $item) {
            foreach ($totals as $key => $total) {
                $totals[$key] += (int) $item->{$key};
            }

            $rows .= '<tr>' .
                '<td>' . e($item->barang?->nama ?? '-') . '</td>' .
                '<td>' . e($item->barang?->kategori?->nama ?? '-') . '</td>' .
                '<td class="number">' . number_format($item->stok_awal) . '</td>' .
                '<td class="number positive">+' . number_format($item->stok_masuk) . '</td>' .
                '<td class="number negative">-' . number_format($item->stok_keluar) . '</td>' .
                '<td class="number strong">' . number_format($item->stok_akhir) . '</td>' .
                '<td>' . e($item->user?->name ?? 'Sistem') . '</td>' .
                '</tr>';
        }

        $html = '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><style>' .
            'body{font-family:Arial,sans-serif;color:#29231f}' .
            '.sheet{width:100%;border-collapse:collapse}' .
            '.brand{background:#1a1714;color:#d4a373;font-size:22px;font-weight:bold;padding:18px 20px}' .
            '.subtitle{background:#f5eee7;color:#6d6259;font-size:14px;padding:10px 20px 18px}' .
            'th{background:#2c251f;color:#fff;padding:12px 10px;text-align:left;border:1px solid #51473e}' .
            'td{padding:10px;border:1px solid #ddd4cb;background:#fff}' .
            'tr:nth-child(even) td{background:#faf7f3}' .
            '.number{text-align:right;mso-number-format:"#,##0"}.positive{color:#218649;font-weight:bold}.negative{color:#c43d36;font-weight:bold}.strong{font-weight:bold}' .
            '.total td{background:#1a1714;color:#d4a373;font-weight:bold}' .
            '</style></head><body><table class="sheet">' .
            '<tr><td colspan="7" class="brand">Aula Coffee Club</td></tr>' .
            '<tr><td colspan="7" class="subtitle">' . e($judul) . ' &nbsp; | &nbsp; Dicetak: ' . now()->format('d/m/Y H:i') . '</td></tr>' .
            '<tr><td colspan="7"></td></tr>' .
            '<tr><th>Nama Barang</th><th>Kategori</th><th>Stok Awal</th><th>Masuk</th><th>Keluar</th><th>Stok Akhir</th><th>Input Oleh</th></tr>' .
            $rows .
            '<tr class="total"><td colspan="2">TOTAL</td><td class="number">' . number_format($totals['stok_awal']) . '</td><td class="number">+' . number_format($totals['stok_masuk']) . '</td><td class="number">-' . number_format($totals['stok_keluar']) . '</td><td class="number">' . number_format($totals['stok_akhir']) . '</td><td></td></tr>' .
            '</table></body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"" . str_replace(' ', '_', $judul) . ".xls\"",
        ]);
    }

    private function buildReport(Carbon $start, Carbon $end, string $type, Carbon $reportDate)
    {
        $movements = StokMovement::with(['barang.kategori', 'user'])
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal')
            ->get()
            ->groupBy('barang_id');

        if ($movements->isEmpty()) {
            return collect();
        }

        $priorMovements = StokMovement::where('tanggal', '<', $start)
            ->whereIn('barang_id', $movements->keys())
            ->get()
            ->groupBy('barang_id');

        return $movements->map(function ($items, $barangId) use ($priorMovements, $type, $reportDate) {
            $barang = $items->first()->barang;
            $sebelumnya = $priorMovements->get($barangId, collect());
            $stokAwal = $barang->stok_awal + $sebelumnya->sum(function ($movement) {
                return $movement->tipe === 'masuk' ? $movement->qty : -$movement->qty;
            });
            $stokMasuk = $items->where('tipe', 'masuk')->sum('qty');
            $stokKeluar = $items->where('tipe', 'keluar')->sum('qty');

            $laporan = new Laporan([
                'barang_id' => $barangId,
                'tipe_laporan' => $type,
                'tanggal_laporan' => $reportDate,
                'stok_awal' => $stokAwal,
                'stok_masuk' => $stokMasuk,
                'stok_keluar' => $stokKeluar,
                'stok_akhir' => $stokAwal + $stokMasuk - $stokKeluar,
            ]);
            $laporan->setRelation('barang', $barang);
            $laporan->setRelation('user', $items->last()->user);

            return $laporan;
        })->values();
    }

    public function generateReports()
    {
        // Generate laporan harian untuk kemarin
        $kemarin = Carbon::yesterday();
        $this->generateDailyReport($kemarin);

        // Generate laporan bulanan di akhir bulan
        if (Carbon::now()->isLastDayOfMonth()) {
            $this->generateMonthlyReport();
        }

        // Generate laporan tahunan di akhir tahun
        if (Carbon::now()->isLastDayOfYear()) {
            $this->generateYearlyReport();
        }

        return 'Reports generated successfully';
    }

    private function generateDailyReport($tanggal)
    {
        $movements = StokMovement::with(['barang', 'user'])
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->groupBy('barang_id');

        foreach ($movements as $barangId => $items) {
            $barang = Barang::find($barangId);
            $masuk = $items->where('tipe', 'masuk')->sum('qty');
            $keluar = $items->where('tipe', 'keluar')->sum('qty');

            Laporan::create([
                'barang_id' => $barangId,
                'user_id' => $items->last()->user_id,
                'tipe_laporan' => 'harian',
                'tanggal_laporan' => $tanggal,
                'stok_awal' => $barang->stok_awal,
                'stok_masuk' => $masuk,
                'stok_keluar' => $keluar,
                'stok_akhir' => $barang->qty,
            ]);
        }
    }

    private function generateMonthlyReport()
    {
        $bulanLalu = Carbon::now()->subMonth();
        $dailyReports = Laporan::where('tipe_laporan', 'harian')
            ->whereYear('tanggal_laporan', $bulanLalu->year)
            ->whereMonth('tanggal_laporan', $bulanLalu->month)
            ->get()
            ->groupBy('barang_id');

        foreach ($dailyReports as $barangId => $reports) {
            Laporan::create([
                'barang_id' => $barangId,
                'tipe_laporan' => 'bulanan',
                'tanggal_laporan' => $bulanLalu->endOfMonth(),
                'stok_awal' => $reports->first()->stok_awal,
                'stok_masuk' => $reports->sum('stok_masuk'),
                'stok_keluar' => $reports->sum('stok_keluar'),
                'stok_akhir' => $reports->last()->stok_akhir,
            ]);
        }
    }

    private function generateYearlyReport()
    {
        $tahunLalu = Carbon::now()->subYear();
        $monthlyReports = Laporan::where('tipe_laporan', 'bulanan')
            ->whereYear('tanggal_laporan', $tahunLalu->year)
            ->get()
            ->groupBy('barang_id');

        foreach ($monthlyReports as $barangId => $reports) {
            Laporan::create([
                'barang_id' => $barangId,
                'tipe_laporan' => 'tahunan',
                'tanggal_laporan' => $tahunLalu->endOfYear(),
                'stok_awal' => $reports->first()->stok_awal,
                'stok_masuk' => $reports->sum('stok_masuk'),
                'stok_keluar' => $reports->sum('stok_keluar'),
                'stok_akhir' => $reports->last()->stok_akhir,
            ]);
        }
    }
}
