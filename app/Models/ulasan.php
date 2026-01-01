<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UlasanBuku extends Model
{
    use HasFactory;

    protected $table = 'ulasan_buku';

    protected $fillable = [
        'user_id',
        'buku_id',
        'rating',
        'komentar',
    ];

    // Ulasan dibuat oleh user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Ulasan untuk sebuah buku
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
