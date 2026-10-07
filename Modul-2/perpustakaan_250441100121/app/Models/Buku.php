<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';

    public $timestamps = false;

    protected $fillable = [
        'kategori_id',
        'judul',
        'penulis',
        'tahun_terbit',
    ];

    public function kategoriRelasi()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function getTahunAttribute()
    {
        return $this->tahun_terbit;
    }

    public function getKategoriAttribute()
    {
        return $this->kategoriRelasi?->nama;
    }
}