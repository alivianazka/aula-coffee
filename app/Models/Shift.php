<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shift';

    protected $fillable = [
        'user_id',
        'awal_shift',
        'akhir_shift',
        'catatan',
    ];

    protected $casts = [
        'awal_shift' => 'datetime',
        'akhir_shift' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive()
    {
        return is_null($this->akhir_shift);
    }
}
