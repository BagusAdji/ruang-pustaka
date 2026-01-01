<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Denda; // Jangan lupa import model Denda

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'total_buku' => Buku::where('jumlah', '>', 0)->count(),
            'total_anggota' => User::where('role', 'user')->count(),
            'peminjaman_aktif' => Peminjaman::where('status', 'dipinjam')->count(),
            'total_denda' => Denda::sum('jumlah_denda'), // Fitur Baru: Total Denda
            'peminjaman_terlambat' => Peminjaman::where('status', 'terlambat')->count(),
            'riwayat_peminjaman' => Peminjaman::with(['buku', 'users'])
                                    ->latest('tanggal_peminjaman')
                                    ->take(5)
                                    ->get(),
        ]);
    }
}
