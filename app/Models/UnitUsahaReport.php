<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitUsahaReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tanggal',
        'jumlah_uang',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_uang' => 'decimal:2',
    ];
}
