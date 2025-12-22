<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_tiket',
        'plat_nomor',
        'jenis',
        'waktu_masuk',
        'waktu_keluar',
        'total_bayar',
        'status'
    ];

    // TAMBAHKAN INI: Memberitahu Laravel bahwa ini adalah tanggal/waktu
    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
    ];
}