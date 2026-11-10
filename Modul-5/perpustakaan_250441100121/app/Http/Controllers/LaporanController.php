<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;

class LaporanController extends Controller
{
    public function index()
    {
        $rekapStatus = Peminjaman::selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $bukuTerpopuler = Buku::with('kategoriRelasi')
            ->withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->take(10)
            ->get();

        $bukuPerKategori = Kategori::withCount('bukus')
            ->orderByDesc('bukus_count')
            ->get();

        $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])
            ->latest('id')
            ->take(10)
            ->get();

        $total = [
            'buku' => Buku::count(),
            'pengguna' => User::count(),
            'peminjaman' => Peminjaman::count(),
            'terlambat' => Peminjaman::where('status', 'disetujui')
                ->whereNotNull('tanggal_kembali')
                ->whereDate('tanggal_kembali', '<', now()->toDateString())
                ->count(),
        ];

        return view('laporan', compact(
            'rekapStatus',
            'bukuTerpopuler',
            'bukuPerKategori',
            'peminjamanTerbaru',
            'total',
        ));
    }
}
