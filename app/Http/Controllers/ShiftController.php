<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function create()
    {
        $activeShift = Shift::where('user_id', Auth::id())
            ->whereNull('akhir_shift')
            ->first();

        if ($activeShift) {
            return redirect()->route('dashboard')->with('info', 'Anda sudah memiliki shift aktif');
        }

        return view('karyawan.shift.create');
    }

    public function store(Request $request)
    {
        // Check if user already has active shift
        $activeShift = Shift::where('user_id', Auth::id())
            ->whereNull('akhir_shift')
            ->first();

        if ($activeShift) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah memiliki shift aktif');
        }

        Shift::create([
            'user_id' => Auth::id(),
            'awal_shift' => now(),
        ]);

        return redirect()->route('stok.create')->with('success', 'Shift dimulai. Silakan input stok barang.');
    }

    public function end()
    {
        $shift = Shift::where('user_id', Auth::id())
            ->whereNull('akhir_shift')
            ->first();

        if (!$shift) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada shift aktif');
        }

        return view('karyawan.shift.end', compact('shift'));
    }

    public function endShift(Request $request)
    {
        $shift = Shift::where('user_id', Auth::id())
            ->whereNull('akhir_shift')
            ->first();

        if (!$shift) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada shift aktif');
        }

        $shift->update([
            'akhir_shift' => now(),
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('dashboard')->with('success', 'Shift berakhir. Anda sudah logout.');
    }
}
