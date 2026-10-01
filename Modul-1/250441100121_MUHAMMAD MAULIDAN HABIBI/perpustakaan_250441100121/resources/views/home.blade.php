@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

    <section class="hero">

        <div class="hero-text">
            <p class="small-title">SELAMAT DATANG DI MYPERPUS</p>

            <h2>Temukan Buku yang Menarik untuk Dibaca</h2>

            <p>
                Jelajahi berbagai koleksi buku yang tersedia
                di perpustakaan kami.
            </p>

            <a class="button" href="{{ route('buku.index') }}">
                Lihat Daftar Buku
            </a>
        </div>

        <div class="hero-image">
            <img src="{{ asset('images/perpustakaan.png') }}" alt="Perpustakaan">
        </div>

    </section>

    <section class="info">
        <h2>Pusat dan jendela ilmu</h2>

        <p>
            Di Myperpus anda akan menemukan berbagai koleksi buku yang menarik dan dapat diakses
            secara gratis tanpa berlangganan bulanan.
        </p>
    </section>

@endsection