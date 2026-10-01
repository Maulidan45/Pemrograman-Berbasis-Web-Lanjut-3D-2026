@extends('layouts.app')
@section('title', 'Daftar Buku')
@section('content')

    <div class="page-title">
        <p class="small-title">KOLEKSI</p>

        <h2>Daftar Buku</h2>

        <p>
            Pilih buku yang ingin kamu lihat informasinya.
        </p>
    </div>

    <div class="book-list">

        @foreach ($buku as $item)
            <x-book-card
                :id="$item['id']"
                :judul="$item['judul']"
                :penulis="$item['penulis']"
                :tahun="$item['tahun']"
                :kategori="$item['kategori']"
            >
                <a
                    class="button"
                    href="{{ route('buku.show', $item['id']) }}"
                >
                    Lihat Detail
                </a>
            </x-book-card>

        @endforeach

    </div>

@endsection