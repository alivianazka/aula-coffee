<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';

    protected $fillable = [
        'kategori_id',
        'nama',
        'deskripsi',
        'stok_awal',
        'stok_akhir',
        'stok_opname',
        'qty',
        'unit',
    ];

    public function kategori()
    {
        return $this->belongsTo(BarangKategori::class, 'kategori_id');
    }

    public function stokMovements()
    {
        return $this->hasMany(StokMovement::class);
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }

    public function isStockLow()
    {
        return $this->qty <= $this->stok_opname;
    }
}
