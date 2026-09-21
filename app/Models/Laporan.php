<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laporan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'laporan';

    protected $fillable = [
        'barang_id',
        'user_id',
        'tipe_laporan',
        'tanggal_laporan',
        'stok_awal',
        'stok_masuk',
        'stok_keluar',
        'stok_akhir',
        'catatan',
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
