<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kategori' => $this->kategoriRelasi?->nama,
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'tahun' => $this->tahun_terbit,
        ];
    }
}





