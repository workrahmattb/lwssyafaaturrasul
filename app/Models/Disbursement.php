<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'wakaf_pembangunan' => 'Wakaf Pembangunan',
        'wakaf_produktif' => 'Wakaf Produktif',
        'donasi_pendidikan' => 'Donasi Pendidikan',
    ];

    protected $fillable = ['description', 'amount', 'type', 'disbursed_at'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'disbursed_at' => 'date'];
    }
}
