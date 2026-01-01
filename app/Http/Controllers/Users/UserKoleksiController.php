<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Kategori;

class UserKoleksiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Mulai Query
        $query = Buku::with('kategori');

        // 2. Cek apakah ada pencarian
        if ($request->has('search') && $request->search != null) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('judul_buku', 'LIKE', "%{$keyword}%")
                    ->orWhere('penulis', 'LIKE', "%{$keyword}%");
            });
        }

        // 3. Ambil data
        $buku = $query->get();
        $kategori = Kategori::all();

        return view('users.koleksi.index', compact('buku', 'kategori'));
    }

    public function show($id)
    {
        // 1. Ambil detail buku berdasarkan ID
        $buku = Buku::with('kategori')->findOrFail($id);

        // 2. Ambil buku serupa (berdasarkan kategori yang sama, kecuali buku ini sendiri)
        $bukuSerupa = Buku::where('id_kategori', $buku->id_kategori)
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('users.koleksi.detail', compact('buku', 'bukuSerupa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_buku' => 'required|exists:buku,id',
            'durasi' => 'required|integer|in:3,5,7',
            'tanggal_pinjam' => 'required|date',
        ]);

        $buku = Buku::findOrFail($request->id_buku);
        if ($buku->jumlah < 1) {
            return redirect()->back()->with('error', 'Maaf, stok buku ini sedang habis.');
        }

        $tglPinjam = Carbon::parse($request->tanggal_pinjam);
        $tglKembali = $tglPinjam->copy()->addDays((int) $request->durasi);


        $prefix = now()->format('Ymd');

        $jumlahTransaksiHariIni = Peminjaman::whereDate('created_at', Carbon::today())->count();

        $urutan = $jumlahTransaksiHariIni + 1;

        $urutanKeren = str_pad($urutan, 3, '0', STR_PAD_LEFT);

        // 5. Gabungkan (Hasil: 20251220001)
        $idCustom = (int) ($prefix . $urutanKeren);

        // --- SIMPAN DATA ---
        Peminjaman::create([
            'id' => $idCustom, // <--- ID Custom Masuk Sini
            'user_id' => Auth::id(),
            'buku_id' => $request->id_buku,
            'tanggal_peminjaman' => $tglPinjam->format('Y-m-d'),
            'tanggal_pengembalian' => $tglKembali->format('Y-m-d'),
            'tanggal_dikembalikan' => null,
            'status' => 'booking'
        ]);

        // Kurangi Stok
        $buku->decrement('jumlah');

        return redirect()->back()->with('success', 'Berhasil booking! Kode Peminjaman Anda: ' . $idCustom);
    }
}
