<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Picqer\Barcode\BarcodeGeneratorSVG;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'judul_buku',
        'penulis',
        'penerbit',
        'sinopsis',
        'tahun_terbit',
        'jumlah',
        'cover',
        'id_kategori'
    ];


    public function getBarcodeAttribute()
    {
        // Cek jika ISBN kosong agar tidak error
        if (empty($this->id)) {
            return null;
        }

        $generator = new BarcodeGeneratorSVG();

        // Return string SVG barcode (Format EAN-13 untuk Buku)
        return $generator->getBarcode($this->id, $generator::TYPE_EAN_13);
    }

    public function getIsbnFormattedAttribute()
    {
        $isbn = $this->attributes['id'];

        // Pastikan panjang ISBN 13 digit sebelum diformat
        if (strlen($isbn) !== 13) {
            return $isbn;
        }

        // Format: 978-1-23-456789-7
        // substr(string, start, length)
        return substr($isbn, 0, 3) . '-' .
            substr($isbn, 3, 1) . '-' .
            substr($isbn, 4, 2) . '-' .
            substr($isbn, 6, 6) . '-' .
            substr($isbn, 12, 1);
    }
    // Buku milik satu kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    // Buku dapat dipinjam berkali-kali
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_buku');
    }
}
