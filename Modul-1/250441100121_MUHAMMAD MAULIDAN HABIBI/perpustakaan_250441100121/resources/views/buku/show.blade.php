@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')

    <h2>Detail Buku</h2>

    @if ($detailBuku)

        <div class="book-detail">

            <h3>{{ $detailBuku['judul'] }}</h3>

            <p>
                <strong>Penulis:</strong>
                {{ $detailBuku['penulis'] }}
            </p>

            <p>
                <strong>Tahun Terbit:</strong>
                {{ $detailBuku['tahun'] }}
            </p>

            <p>
                <strong>Kategori:</strong>
                {{ $detailBuku['kategori'] }}
            </p>

        </div>

    @else

        <div class="book-detail">
            <h3>Buku Tidak Ditemukan</h3>

            <p>
                Maaf, data buku yang kamu cari tidak tersedia.
            </p>
        </div>

    @endif

    <a class="button" href="{{ route('buku.index') }}">
        Kembali ke Daftar Buku
    </a>

@endsection