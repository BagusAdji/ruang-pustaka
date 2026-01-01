<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'id',
        'user_id',
        'buku_id',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'tanggal_dikembalikan',
        'status'
    ];

    public function isExpired()
    {
        if ($this->status !== 'booking') {
            return false;
        }

        $batas = Carbon::parse($this->tanggal_peminjaman)->addHours(24);

        return now()->greaterThan($batas);
    }

    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $status = $attributes['status'];
                switch ($status) {
                    case 'booking':
                        return '<span class="badge bg-booking">Booking</span>';
                    case 'dipinjam':
                        return '<span class="badge bg-pinjam">Dipinjam</span>';
                    case 'dikembalikan':
                        return '<span class="badge bg-selesai">Dikembalikan</span>';
                    case 'batal':
                        return '<span class="badge bg-batal">Dibatalkan</span>';
                    case 'terlambat':
                        return '<span class="badge bg-terlambat">Terlambat</span>';
                    default:
                        return '<span class="badge bg-secondary">' . $status . '</span>';
                }
            }
        );
    }

    // Peminjaman dimiliki oleh satu pengguna
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Peminjaman untuk satu buku
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    // Relasi ke denda (jika ada)
    public function denda()
    {
        return $this->hasOne(Denda::class, 'peminjaman_id', 'id');
    }
}
