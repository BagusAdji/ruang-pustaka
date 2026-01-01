<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Users\HomeController;
use App\Http\Controllers\Users\UserKoleksiController;
use App\Http\Controllers\Users\UserProfileController;
use Illuminate\Support\Facades\Auth;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'detail'])->name('peminjaman.detail');
    Route::patch('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::patch('/peminjaman/{id}/complete', [PeminjamanController::class, 'complete'])->name('peminjaman.complete');
    Route::post('/peminjaman/{id}/denda', [PeminjamanController::class, 'dendaketerlambatan'])->name('peminjaman.denda');
});

Route::name('user.')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/koleksi', [UserKoleksiController::class, 'index'])->name('koleksi');
    Route::get('/buku/{id}', [UserKoleksiController::class, 'show'])->name('buku.show');
});

Route::middleware(['auth'])->name('user.')->group(function () {
    Route::post('/peminjaman/store', [UserKoleksiController::class, 'store'])->name('peminjaman.store');
    Route::get('/profil', [UserProfileController::class, 'index'])->name('profil');
    Route::post('/profil/photo', [UserProfileController::class, 'updatePhoto'])->name('profil.photo');
});

Route::get('/', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.home');
});
