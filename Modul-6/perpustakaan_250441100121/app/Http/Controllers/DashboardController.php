<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $kartu = [
            ['label' => 'Total Buku', 'nilai' => Buku::count()],
            ['label' => 'Peminjaman Saya', 'nilai' => Peminjaman::milik($user->id)->count()],
        ];

        if ($user->can('kelola peminjaman')) {
            $kartu[] = ['label' => 'Menunggu Persetujuan', 'nilai' => Peminjaman::where('status', 'menunggu')->count()];
            $kartu[] = ['label' => 'Sedang Dipinjam', 'nilai' => Peminjaman::where('status', 'disetujui')->count()];
            $kartu[] = ['label' => 'Sudah Dikembalikan', 'nilai' => Peminjaman::where('status', 'dikembalikan')->count()];
        }

        if ($user->can('kelola data pengguna')) {
            $kartu[] = ['label' => 'Total Pengguna', 'nilai' => User::count()];
        }

        return view('dashboard', compact('kartu'));
    }
}

