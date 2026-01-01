<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\User;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi Query dengan Relasi
        $query = Peminjaman::with(['buku', 'users']);

        // 2. Fitur PENCARIAN (Search)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                // Cari berdasarkan ID Peminjaman atau Status
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    // Cari berdasarkan Judul Buku (Relasi)
                    ->orWhereHas('buku', function ($buku) use ($search) {
                        $buku->where('judul_buku', 'like', "%{$search}%");
                    })
                    // Cari berdasarkan Nama User (Relasi)
                    ->orWhereHas('users', function ($user) use ($search) {
                        $user->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->get();

        foreach ($peminjaman as $p) {
            if ($p->isExpired()) {
                $p->update(['status' => 'batal']);
                $p->status = 'batal';
                $p->buku->increment('jumlah');
            }
        }

        return view('admin.peminjaman.index', [
            'peminjaman' => $peminjaman
        ]);
    }

    public function detail(Peminjaman $peminjaman)
    {
        $peminjaman->load(['users', 'buku.kategori', 'denda']);

        return view('admin.peminjaman.detail', [
            'peminjaman' => $peminjaman
        ]);
    }

    public function approve($id)
    {
        $p = Peminjaman::findOrFail($id);

        if ($p->status !== 'booking') {
            return back()->with('error', 'Status peminjaman tidak valid.');
        }

        $p->update([
            'status' => 'dipinjam',
            'tanggal_peminjaman' => now(),
            'tanggal_pengembalian' => now()->addDays(7)
        ]);

        return back()->with('success', 'Buku berhasil dipinjam.');
    }

    public function complete($id)
    {
        $p = Peminjaman::findOrFail($id);

        if (!in_array($p->status, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Status peminjaman tidak valid untuk dikembalikan.');
        }

        if ($p->denda) {
            $p->denda->update([
                'status_denda' => 'sudah_bayar'
            ]);
        }

        $p->update([
            'status' => 'dikembalikan',
            'tanggal_dikembalikan' => now()
        ]);

        $p->buku->increment('jumlah');

        return back()->with('success', 'Buku dikembalikan & status denda diperbarui');
    }

    public function dendaketerlambatan($id)
    {
        $p = Peminjaman::findOrFail($id);

        if ($p->status !== 'dipinjam') {
            return back()->with('error', 'Status tidak valid.');
        }

        $due = Carbon::parse($p->tanggal_pengembalian)->startOfDay();
        $now = now()->startOfDay();

        if ($now->lessThanOrEqualTo($due)) {
            return back()->with('info', 'Tidak ada keterlambatan.');
        }

        $hari_telat = $now->diffInDays($due);

        $p->update([
            'status' => 'terlambat'
        ]);

        Denda::create([
            'user_id' => $p->user_id,
            'peminjaman_id' => $p->id,
            'jumlah_denda' => abs($hari_telat * 1000),
            'status_denda' => 'belum_bayar'
        ]);

        $tampil_hari_telat= abs($hari_telat);
        return back()->with('success', "Pengembailan terlambat  $tampil_hari_telat hari & denda dibuat.");
    }
}
