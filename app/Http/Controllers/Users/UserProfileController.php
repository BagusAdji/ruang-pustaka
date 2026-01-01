<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Denda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // --- 1. DATA STATISTIK ---

        // Hitung peminjaman yang statusnya 'dipinjam' atau 'booking'
        $sedangDipinjam = Peminjaman::where('user_id', $user->id)
            ->whereIn('status', ['dipinjam', 'booking'])
            ->count();

        // Hitung total riwayat (status 'dikembalikan' atau 'batal')
        $totalRiwayat = Peminjaman::where('user_id', $user->id)
            ->whereIn('status', ['dikembalikan', 'batal', 'terlambat'])
            ->count();

        // Hitung Denda (Jika Anda punya tabel denda, sesuaikan logikanya)
        // Untuk contoh ini saya set 0 dulu, atau ambil sum dari relasi denda
        $totalDenda =  Denda::where('user_id', $user->id)->sum('jumlah_denda');

        $peminjamanAktif = Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->whereIn('status', ['dipinjam', 'booking', 'terlambat'])
            ->orderBy('tanggal_pengembalian', 'asc')
            ->get();

        // Ambil Riwayat (Selesai/Batal)
        $riwayatPeminjaman = Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->whereIn('status', ['dikembalikan', 'batal'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $statusDenda = Denda::where('user_id', $user->id)->value('status_denda');

        return view('users.profil.index', compact(
            'user',
            'sedangDipinjam',
            'totalRiwayat',
            'totalDenda',
            'peminjamanAktif',
            'statusDenda',
            'riwayatPeminjaman'
        ));
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:10048', // Max 2MB
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');

            $user->avatar = $path;
            DB::table('users')
                ->where('id', Auth::id())
                ->update(['avatar' => $path]);
        }

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
