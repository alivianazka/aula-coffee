<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifikasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notifikasi';

    protected $fillable = [
        'barang_id',
        'judul',
        'pesan',
        'status',
        'dibaca_pada',
    ];

    protected $casts = [
        'dibaca_pada' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function markAsRead()
    {
        $this->update([
            'status' => 'sudah_dibaca',
            'dibaca_pada' => now(),
        ]);
    }
}
