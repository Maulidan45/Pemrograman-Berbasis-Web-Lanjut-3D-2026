<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
})->name('home');

// Semua pengguna yang sudah login
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat profil sendiri')
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.update');

    // Daftar dan detail buku
    Route::get('/buku', [BukuController::class, 'index'])
        ->middleware('permission:lihat daftar buku')
        ->name('buku.index');

    // Form tambah buku dibatasi Gate kelola-buku.
    Route::get('/buku/create', [BukuController::class, 'index'])
        ->middleware('can:kelola-buku')
        ->name('buku.create');

    Route::get('/buku/{buku}', [BukuController::class, 'show'])
        ->middleware('permission:lihat daftar buku')
        ->name('buku.show');

    // Pencarian buku.
    Route::get('/buku-cari', [BukuController::class, 'cari'])
        ->name('buku.cari');

    // Pengelolaan buku
    Route::middleware('can:kelola-buku')->group(function () {

        Route::post('/buku', [BukuController::class, 'store'])
            ->name('buku.store');

        Route::get('/buku/{buku}/edit', [BukuController::class, 'edit'])
            ->name('buku.edit');

        Route::put('/buku/{buku}', [BukuController::class, 'update'])
            ->name('buku.update');

        Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])
            ->name('buku.destroy');
    });

    // Peminjaman milik pengguna sendiri
    Route::get('/peminjaman-saya', [PeminjamanController::class, 'saya'])
        ->middleware('permission:melihat status peminjaman sendiri')
        ->name('peminjaman.saya');

    Route::post('/peminjaman', [PeminjamanController::class, 'store'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('peminjaman.store');

    // A4: Pengujian IDOR - SENGAJA RENTAN
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])
        ->name('peminjaman.show');
});

// Admin dan petugas
Route::middleware(['auth', 'role:admin|petugas'])->group(function () {

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->middleware('permission:kelola peminjaman')
        ->name('peminjaman.index');

    Route::patch('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.setujui');

    Route::patch('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.tolak');

    Route::patch('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])
        ->middleware('permission:mencatat pengembalian buku')
        ->name('peminjaman.kembalikan');

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:lihat laporan perpustakaan')
        ->name('laporan.index');
});

// Admin: pengelolaan akun dan role
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::resource('users', UserController::class)
        ->except(['show']);
});

require __DIR__ . '/auth.php';
