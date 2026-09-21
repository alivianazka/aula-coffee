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
        
        $laporan = Laporan::with(['barang.kategori', 'user'])
            ->where('tipe_laporan', 'harian')
            ->whereDate('tanggal_laporan', $tanggal)
            ->get();

        return view('laporan.harian', compact('laporan', 'tanggal'));
    }

    public function bulanan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
        ]);

        list($tahun, $bulan) = explode('-', $request->bulan);
        
        $laporan = Laporan::with(['barang.kategori', 'user'])
            ->where('tipe_laporan', 'bulanan')
            ->whereYear('tanggal_laporan', $tahun)
            ->whereMonth('tanggal_laporan', $bulan)
            ->get();

        $periode = Carbon::parse($request->bulan . '-01')->format('F Y');

        return view('laporan.bulanan', compact('laporan', 'periode'));
    }

    public function tahunan(Request $request)
    {
        $request->validate([
            'tahun' => 'required|digits:4|integer|min:2020|max:' . date('Y'),
        ]);

        $tahun = $request->tahun;

        $laporan = Laporan::with(['barang.kategori', 'user'])
            ->where('tipe_laporan', 'tahunan')
            ->whereYear('tanggal_laporan', $tahun)
            ->get();

        return view('laporan.tahunan', compact('laporan', 'tahun'));
    }

    public function downloadHarian(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $tanggal = Carbon::parse($request->tanggal);
        
        $laporan = Laporan::with(['barang.kategori', 'user'])
            ->where('tipe_laporan', 'harian')
            ->whereDate('tanggal_laporan', $tanggal)
            ->get();

        return $this->generatePDF($laporan, 'Laporan Harian - ' . $tanggal->format('d-m-Y'));
    }

    public function downloadBulanan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
        ]);

        list($tahun, $bulan) = explode('-', $request->bulan);
        
        $laporan = Laporan::with(['barang.kategori', 'user'])
            ->where('tipe_laporan', 'bulanan')
            ->whereYear('tanggal_laporan', $tahun)
            ->whereMonth('tanggal_laporan', $bulan)
            ->get();

        $periode = Carbon::parse($request->bulan . '-01')->format('F Y');

        return $this->generatePDF($laporan, 'Laporan Bulanan - ' . $periode);
    }

    public function downloadTahunan(Request $request)
    {
        $request->validate([
            'tahun' => 'required|digits:4|integer|min:2020|max:' . date('Y'),
        ]);

        $laporan = Laporan::with(['barang.kategori', 'user'])
            ->where('tipe_laporan', 'tahunan')
            ->whereYear('tanggal_laporan', $request->tahun)
            ->get();

        return $this->generatePDF($laporan, 'Laporan Tahunan - ' . $request->tahun);
    }

    private function generatePDF($laporan, $judul)
    {
        // This will be implemented with a PDF library like mPDF or Dompdf
        // For now, we'll return a CSV export
        $csv = "Aula Coffe Club\n";
        $csv .= $judul . "\n\n";
        $csv .= "Nama Barang,Kategori,Stok Awal,Masuk,Keluar,Stok Akhir,Input Oleh\n";
        
        foreach ($laporan as $item) {
            $csv .= "{$item->barang->nama},{$item->barang->kategori->nama}," .
                    "{$item->stok_awal},{$item->stok_masuk},{$item->stok_keluar}," .
                    "{$item->stok_akhir}," . ($item->user?->name ?? 'Sistem') . "\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"" . str_replace(' ', '_', $judul) . ".csv\"",
        ]);
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
