@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')

    <div class="book-page">

        <h2>Detail Buku</h2>

        <section class="book-section">
            <div class="book-detail">
                <h3>{{ $buku->judul }}</h3>

                <p>
                    <strong>Penulis:</strong>
                    {{ $buku->penulis }}
                </p>

                <p>
                    <strong>Tahun Terbit:</strong>
                    {{ $buku->tahun_terbit }}
                </p>

                <p>
                    <strong>Kategori:</strong>
                    {{ $buku->kategoriRelasi?->nama ?? '-' }}
                </p>

                <p>
                    <strong>Jumlah Dipinjam:</strong>
                    {{ $buku->peminjamans_count }}x
                </p>
            </div>

            <a class="button" href="{{ route('buku.index') }}">
                Kembali ke Daftar Buku
            </a>
        </section>

    </div>

@endsection
