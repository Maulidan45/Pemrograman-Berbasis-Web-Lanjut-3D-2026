<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $buku = Buku::with('kategoriRelasi')
            ->withCount('peminjamans')
            ->when($request->filled('cari'), function ($query) use ($request) {
                $cari = $request->cari;

                $query->where(function ($q) use ($cari) {
                    $q->where('judul', 'like', "%{$cari}%")
                        ->orWhere('penulis', 'like', "%{$cari}%");
                });
            })
            ->when($request->filled('kategori'), function ($query) use ($request) {
                $query->where('kategori_id', $request->kategori);
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('buku.index', [
            'buku' => $buku,
            'kategori' => Kategori::orderBy('nama')->get()
        ]);
    }

    public function show(Buku $buku)
    {
        $buku->load('kategoriRelasi')->loadCount('peminjamans');

        return view('buku.show', compact('buku'));
    }

    public function store(Request $request)
    {
        Buku::create($this->validated($request));

        return redirect()->route('buku.index')
            ->with('status', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        return view('buku.edit', [
            'buku' => $buku,
            'kategori' => Kategori::orderBy('nama')->get()
        ]);
    }

    public function update(Request $request, Buku $buku)
    {
        $buku->update($this->validated($request));

        return redirect()->route('buku.index')
            ->with('status', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')
            ->with('status', 'Buku berhasil dihapus.');
    }

    // Pengujian SQL Injection (A2)
    public function cariRaw(Request $request)
    {
        $judul = $request->query('judul');

        $hasil = DB::select(
            "SELECT * FROM bukus WHERE judul = '$judul'"
        );

        return response()->json($hasil);
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'judul' => ['required', 'string', 'min:3', 'max:150'],
            'penulis' => ['required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => ['required', 'integer', 'between:1900,' . date('Y')],
        ]);
    }
}