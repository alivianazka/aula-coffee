<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Notifikasi;
use App\Models\Shift;
use App\Models\StokMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'karyawan') {
            $barang = Barang::with('kategori')->get()->groupBy(fn($b) => $b->kategori?->nama ?? 'Tanpa Kategori');
            return view('karyawan.stok.index', compact('barang'));
        }

        $barang = Barang::with(['kategori', 'stokMovements'])->get()->groupBy(fn($b) => $b->kategori?->nama ?? 'Tanpa Kategori');
        return view('admin.stok.index', compact('barang'));
    }

    public function create()
    {
        $barang = Barang::with('kategori')->get()->groupBy('kategori.nama');
        $shift = Shift::where('user_id', Auth::id())->whereNull('akhir_shift')->first();
        
        if (!$shift && Auth::user()->role === 'karyawan') {
            return redirect()->route('shift.create')->with('error', 'Silakan mulai shift terlebih dahulu');
        }

        return view('karyawan.stok.create', compact('barang', 'shift'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'  => 'required|exists:barang,id',
            'tipe'       => 'required|in:masuk,keluar',
            'qty'        => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $soWarningMsg = null;

        try {
            DB::transaction(function () use ($request, &$soWarningMsg) {
                $barang = Barang::findOrFail($request->barang_id);

                // Validate keluar tidak melebihi stok
                if ($request->tipe === 'keluar' && $request->qty > $barang->qty) {
                    throw new \Exception('Jumlah keluar melebihi stok tersedia (' . $barang->qty . ' ' . $barang->unit . ')');
                }

                // Create stok movement record
                StokMovement::create([
                    'barang_id'  => $request->barang_id,
                    'user_id'    => Auth::id(),
                    'tipe'       => $request->tipe,
                    'qty'        => $request->qty,
                    'keterangan' => $request->keterangan,
                    'tanggal'    => now(),
                ]);

                // Update barang qty & stok_akhir
                if ($request->tipe === 'masuk') {
                    $barang->increment('qty', $request->qty);
                    $barang->increment('stok_akhir', $request->qty);
                } else {
                    $barang->decrement('qty', $request->qty);
                    $barang->decrement('stok_akhir', $request->qty);
                }

                // Refresh model after increment/decrement
                $barang->refresh();

                // Auto-notifikasi jika stok mencapai atau di bawah SO
                if ($barang->isStockLow()) {
                    Notifikasi::where('barang_id', $barang->id)
                        ->where('status', 'belum_dibaca')
                        ->delete();

                    Notifikasi::create([
                        'barang_id' => $barang->id,
                        'judul'     => '⚠️ Stok ' . $barang->nama . ' Mencapai SO!',
                        'pesan'     => 'Stok ' . $barang->nama . ' saat ini ' . $barang->qty
                                        . ' ' . $barang->unit
                                        . ' — sudah mencapai/di bawah batas SO (' . $barang->stok_opname . ' ' . $barang->unit . ').'
                                        . ' Di-update oleh: ' . Auth::user()->name
                                        . ' pada ' . now()->format('d/m/Y H:i') . '.',
                        'status'    => 'belum_dibaca',
                    ]);

                    $soWarningMsg = 'Stok ' . $barang->nama . ' mencapai batas SO (' . $barang->qty . ' ' . $barang->unit . '). Notifikasi telah dikirim ke Admin!';
                }
            });
        } catch (\Exception $e) {
            return redirect()->route('stok.create')->with('error', $e->getMessage());
        }

        $redirect = redirect()->route('stok.create')->with('success', 'Stok berhasil diperbarui!');
        if ($soWarningMsg) {
            $redirect = $redirect->with('so_warning', $soWarningMsg);
        }
        return $redirect;
    }

    public function lihatStok()
    {
        $barang = Barang::with('kategori')->get()->groupBy('kategori.nama');
        return view('karyawan.stok.lihat', compact('barang'));
    }
}
