<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman awal: wajib login
|--------------------------------------------------------------------------
| Pengunjung yang belum punya akun diarahkan ke halaman login (dari sana
| tersedia tautan registrasi). Pengguna yang sudah login masuk dashboard.
*/
Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Halaman untuk pengguna yang sudah login
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profil hanya untuk data milik pengguna yang sedang login.
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat profil sendiri')
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.update');

    // Daftar & detail buku dapat dilihat semua role.
    Route::get('/buku', [BukuController::class, 'index'])
        ->middleware('permission:lihat daftar buku')
        ->name('buku.index');

    Route::get('/buku/{buku}', [BukuController::class, 'show'])
        ->middleware('permission:lihat daftar buku')
        ->name('buku.show');

    // Kelola data buku: hanya admin & petugas.
    Route::middleware('permission:kelola data buku')->group(function () {
        Route::post('/buku', [BukuController::class, 'store'])
            ->name('buku.store');

        Route::get('/buku/{buku}/edit', [BukuController::class, 'edit'])
            ->name('buku.edit');

        Route::put('/buku/{buku}', [BukuController::class, 'update'])
            ->name('buku.update');

        Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])
            ->name('buku.destroy');
    });

    // Peminjaman milik sendiri.
    Route::get('/peminjaman-saya', [PeminjamanController::class, 'saya'])
        ->middleware('permission:melihat status peminjaman sendiri')
        ->name('peminjaman.saya');

    Route::post('/peminjaman', [PeminjamanController::class, 'store'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('peminjaman.store');

    // Detail peminjaman: pemilik data atau admin/petugas (dicek di controller).
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])
        ->name('peminjaman.show');
});

/*
|--------------------------------------------------------------------------
| Admin & Petugas: peminjaman, pengembalian, laporan
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| Admin: kelola akun & role pengguna
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class)
        ->except(['show']);
});

require __DIR__ . '/auth.php';
