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
        'status',
        'durasi' 
    ];

    /**
     * Casting atribut ke tipe data Carbon/DateTime.
     * Ini memungkinkan Anda melakukan: $parking->waktu_masuk->diffInHours()
     */
    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
        'total_bayar' => 'integer',
    ];

    /**
     * SCOPE: Memudahkan pemanggilan data yang statusnya masih aktif
     * Penggunaan: Parking::aktif()->get();
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * SCOPE: Memudahkan pemanggilan data yang sudah selesai
     * Penggunaan: Parking::selesai()->get();
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }
}