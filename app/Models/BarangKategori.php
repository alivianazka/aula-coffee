<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangKategori extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang_kategori';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }
}
