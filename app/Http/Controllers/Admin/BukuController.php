<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Buku;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::query();

        // SEARCH
        if ($request->search) {
            $query->where('judul_buku', 'like', '%' . $request->search . '%');
        }

        // FILTER KATEGORI
        if ($request->kategori) {
            $query->where('id_kategori', $request->kategori);
        }

        if ($request->stok == 'tersedia') {
            $query->where('jumlah', '>', 0);
        } elseif ($request->stok == 'habis') {
            $query->where('jumlah', '=', 0);
        }

        $buku = $query->latest()->paginate(10);
        $kategori = Kategori::all();

        return view('admin.buku.index', compact('buku', 'kategori'));
    }


    public function create()
    {
        return view('admin.buku.create', [
            'kategori' => Kategori::all()
        ]);
    }

    private function generateIsbn()
    {
        do {
            // 1. Tentukan Prefix (978 adalah standar buku internasional)
            $prefix = '978';

            // 2. Generate 9 angka random untuk isi tengahnya
            // (Menggunakan loop agar memastikan kita dapat 9 digit, termasuk nol di depan jika ada)
            $random_part = '';
            for ($i = 0; $i < 9; $i++) {
                $random_part .= mt_rand(0, 9);
            }

            // 3. Gabungkan prefix dan random part untuk persiapan hitung checksum
            $temp_isbn = $prefix . $random_part;

            // 4. Hitung Check Digit (Digit ke-13) sesuai standar ISBN-13
            $sum = 0;
            for ($i = 0; $i < 12; $i++) {
                $digit = intval($temp_isbn[$i]);
                // Jika posisi ganjil (index 0, 2, dst) dikali 1
                // Jika posisi genap (index 1, 3, dst) dikali 3
                if (($i % 2) == 0) {
                    $sum += $digit * 1;
                } else {
                    $sum += $digit * 3;
                }
            }

            // Ambil sisa bagi 10
            $modulo = $sum % 10;

            // Hitung check digit
            $check_digit = ($modulo == 0) ? 0 : (10 - $modulo);

            // 5. Gabungkan semuanya menjadi ISBN final
            $isbn = $temp_isbn . $check_digit;

            // 6. Cek ke database agar tidak ada ISBN kembar (Uniqueness Check)
        } while (\App\Models\Buku::where('id', $isbn)->exists());

        return $isbn;
    }

    public function store(Request $request)
    {
        // Aturan Validasi
        $rules = [
            'judul'         => 'required|unique:buku,judul_buku',
            'penulis'       => 'required',
            'penerbit'      => 'required',
            'sinopsis'      => 'required',
            'tahun_terbit'  => 'required',
            'kategori_id'   => 'required',
            'jumlah'        => 'required',
            'cover'         => 'nullable|image|mimes:jpg,jpeg,png|max:10480',
        ];

        // Pesan Error Kustom
        $messages = [
            'required'      => 'Mohon isi semua kolom yang wajib!',
            'judul.unique'  => 'Buku dengan judul ini sudah ada di database!',
            'cover.image'   => 'File harus berupa gambar.',
            'cover.max'     => 'Ukuran gambar maksimal 10MB.'
        ];

        // Jalankan Validasi dengan pesan kustom
        $request->validate($rules, $messages);

        $path = null;
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('cover_buku', 'public');
        }

        $id_buku = $this->generateIsbn();

        Buku::create([
            'id'            => $id_buku,
            'judul_buku'    => $request->judul,
            'penulis'       => $request->penulis,
            'penerbit'      => $request->penerbit,
            'sinopsis'      => $request->sinopsis,
            'tahun_terbit'  => $request->tahun_terbit,
            'id_kategori'   => $request->kategori_id,
            'jumlah'        => $request->jumlah,
            'cover'         => $path,
        ]);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit(Buku $buku)
    {
        return view('admin.buku.edit', [
            'buku' => $buku,
            'kategori' => Kategori::all()
        ]);
    }


    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul'       => 'required|string|max:255',
            'jumlah'      => 'required|numeric',
            'cover'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        $dataToUpdate = [
            'judul_buku'  => $request->judul,
            'kategori_id' => $request->kategori_id,
            'jumlah'      => $request->jumlah,
        ];

        if ($request->hasFile('cover')) {
            if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
                Storage::disk('public')->delete($buku->cover);
            }
            $dataToUpdate['cover'] = $request->file('cover')->store('cover_buku', 'public');
        }
        $buku->update($dataToUpdate);

        return redirect()->route('admin.buku.index')->with('success', 'Data buku diperbarui!');
    }


    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->cover && file_exists(storage_path('app/public/' . $buku->cover))) {
            unlink(storage_path('app/public/' . $buku->cover));
        }
        $buku->delete();

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
