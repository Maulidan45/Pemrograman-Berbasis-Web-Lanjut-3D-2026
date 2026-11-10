<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategoriRelasi')
            ->withCount('peminjamans')
            ->orderByDesc('id');

        if ($request->filled('cari')) {
            $cari = $request->cari;

            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                    ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        $buku = $query->paginate(10)->withQueryString();

        $buku->getCollection()->transform(
            fn ($item) => new BukuResource($item)
        );

        return response()->json($buku, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'judul' => ['required', 'string', 'min:3', 'max:150'],
            'penulis' => ['required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => [
                'required',
                'integer',
                'between:1900,' . date('Y'),
            ],
        ]);

        $buku = Buku::create($validated);
        $buku->load('kategoriRelasi');

        return response()->json([
            'message' => 'Buku berhasil ditambahkan.',
            'data' => new BukuResource($buku),
        ], 201);
    }

    public function show(string $id)
    {
        $buku = Buku::with([
            'kategoriRelasi',
            'peminjamans',
        ])->find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'data' => new BukuResource($buku),
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'judul' => ['required', 'string', 'min:3', 'max:150'],
            'penulis' => ['required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => [
                'required',
                'integer',
                'between:1900,' . date('Y'),
            ],
        ]);

        $buku->update($validated);
        $buku->load('kategoriRelasi');

        return response()->json([
            'message' => 'Buku berhasil diperbarui.',
            'data' => new BukuResource($buku),
        ], 200);
    }

    public function destroy(string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus.',
        ], 200);
    }
}
