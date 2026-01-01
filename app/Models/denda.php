<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda';
    public const DENDA_PER_HARI = 1000;

    protected $fillable = [
        'user_id',
        'peminjaman_id',
        'jumlah_denda',
        'status_denda'
    ];

    // Denda dimiliki oleh satu pengguna
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Denda terkait satu peminjaman
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }
}
