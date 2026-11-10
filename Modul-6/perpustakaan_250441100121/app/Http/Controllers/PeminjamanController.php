<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Data peminjaman seluruh pengguna (admin & petugas).
     */
    public function index(Request $request)
    {
        $peminjaman = Peminjaman::with(['user', 'buku'])
            ->when($request->filled('cari'), function ($query) use ($request) {
                $cari = $request->cari;

                $query->where(function ($q) use ($cari) {
                    $q->whereHas('user', function ($user) use ($cari) {
                        $user->where('name', 'like', "%{$cari}%")
                            ->orWhere('email', 'like', "%{$cari}%");
                    })->orWhereHas('buku', function ($buku) use ($cari) {
                        $buku->where('judul', 'like', "%{$cari}%");
                    });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('peminjaman.index', [
            'peminjaman' => $peminjaman,
            'statusList' => ['menunggu', 'disetujui', 'ditolak', 'dikembalikan'],
        ]);
    }

    /**
     * Status dan riwayat peminjaman milik pengguna yang sedang login.
     */
    public function saya(Request $request)
    {
        $peminjaman = Peminjaman::with('buku')
            ->milik($request->user()->id)
            ->latest('id')
            ->get();

        return view('peminjaman.saya', [
            'peminjaman' => $peminjaman,
            'buku' => Buku::with('kategoriRelasi')->orderBy('judul')->get(),
        ]);
    }

    /**
     * Pengujian IDOR (A4) - SENGAJA RENTAN.
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'buku', 'anggota'])
            ->findOrFail($id);

        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Pengajuan peminjaman oleh pengguna yang sedang login.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'buku_id' => ['required', 'exists:bukus,id'],
        ]);

        $sudahDipinjam = Peminjaman::milik($request->user()->id)
            ->where('buku_id', $validated['buku_id'])
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();

        if ($sudahDipinjam) {
            return redirect()
                ->route('peminjaman.saya')
                ->with('error', 'Buku tersebut masih dalam proses peminjaman Anda.');
        }

        $anggotaId = $request->user()->anggota?->id;

        if (! $anggotaId) {
            $anggotaId = DB::table('anggotas')->insertGetId([
                'nama' => $request->user()->name,
                'email' => $request->user()->email,
                'no_telepon' => $request->user()->no_telepon ?? '-',
            ]);
        }

        Peminjaman::create([
            'user_id' => $request->user()->id,
            'anggota_id' => $anggotaId,
            'buku_id' => $validated['buku_id'],
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'menunggu',
        ]);

        return redirect()
            ->route('peminjaman.saya')
            ->with('status', 'Pengajuan peminjaman berhasil dikirim.');
    }

    /**
     * Persetujuan atau penolakan pengajuan peminjaman.
     */
    public function setujui(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Hanya pengajuan berstatus menunggu yang dapat diproses.');
        }

        $peminjaman->update([
            'status' => 'disetujui',
            'tanggal_pinjam' => now()->toDateString(),
            'disetujui_pada' => now(),
        ]);

        return back()->with('status', 'Peminjaman disetujui.');
    }

    public function tolak(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Hanya pengajuan berstatus menunggu yang dapat diproses.');
        }

        $peminjaman->update([
            'status' => 'ditolak',
            'disetujui_pada' => now(),
        ]);

        return back()->with('status', 'Pengajuan peminjaman ditolak.');
    }

    /**
     * Pencatatan pengembalian buku.
     */
    public function kembalikan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Hanya peminjaman yang telah disetujui yang dapat dikembalikan.');
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
            'dikembalikan_pada' => now(),
        ]);

        return back()->with('status', 'Pengembalian buku berhasil dicatat.');
    }

    protected function milikSendiri(Request $request, Peminjaman $peminjaman): bool
    {
        return (int) $peminjaman->user_id === (int) $request->user()->id;
    }
}
