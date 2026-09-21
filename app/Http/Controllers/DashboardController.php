<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Notifikasi;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'karyawan') {
            $activeShift = Shift::where('user_id', $user->id)
                ->whereNull('akhir_shift')
                ->first();

            $barangStokRendah = Barang::where(function ($query) {
                $query->where('qty', '<=', 100)
                    ->orWhereRaw('qty <= stok_opname');
            })->count();

            return view('karyawan.dashboard', compact('activeShift', 'barangStokRendah'));
        } elseif ($user->role === 'admin') {
            $totalBarang = Barang::count();
            $barangStokRendah = Barang::whereRaw('qty <= stok_opname')->count();
            $notifikasiBelumDibaca = Notifikasi::where('status', 'belum_dibaca')->count();

            return view('admin.dashboard', compact('totalBarang', 'barangStokRendah', 'notifikasiBelumDibaca'));
        } elseif ($user->role === 'owner') {
            $totalBarang = Barang::count();
            $barangStokRendah = Barang::whereRaw('qty <= stok_opname')->count();
            $notifikasiBelumDibaca = Notifikasi::where('status', 'belum_dibaca')->count();
            $totalLaporan = \App\Models\Laporan::count();
            $totalKaryawan = \App\Models\User::where('role', 'karyawan')->count();
            
            return view('owner.dashboard', compact('totalBarang', 'barangStokRendah', 'notifikasiBelumDibaca', 'totalLaporan', 'totalKaryawan'));
        }

        return view('dashboard');
    }

    public function karyawanStatus()
    {
        $karyawanStatus = \App\Models\User::where('role', 'karyawan')
            ->with(['shifts' => function($q) {
                $q->whereNull('akhir_shift');
            }])
            ->get();
            
        return view('owner.karyawan_status', compact('karyawanStatus'));
    }
}
