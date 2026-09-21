<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::orderBy('created_at', 'desc')->paginate(15);
        return view('notifikasi.index', compact('notifikasi'));
    }

    public function markAsRead($id)
    {
        $notif = Notifikasi::findOrFail($id);
        $notif->markAsRead();
        return back()->with('success', 'Notifikasi ditandai sebagai sudah dibaca');
    }

    public function markAllAsRead()
    {
        Notifikasi::where('status', 'belum_dibaca')->update([
            'status' => 'sudah_dibaca',
            'dibaca_pada' => now(),
        ]);
        return back()->with('success', 'Semua notifikasi ditandai sebagai sudah dibaca');
    }

    public function countUnread()
    {
        $count = Notifikasi::where('status', 'belum_dibaca')->count();
        return response()->json(['count' => $count]);
    }

    public function getUnread()
    {
        $notifikasi = Notifikasi::where('status', 'belum_dibaca')
            ->with('barang')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        return response()->json($notifikasi);
    }
}
