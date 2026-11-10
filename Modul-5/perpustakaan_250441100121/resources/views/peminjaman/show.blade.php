@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')

    <div class="book-page">

        <h2>Detail Peminjaman</h2>

        <section class="book-section">
            <div class="book-detail">
                <h3>{{ $peminjaman->buku?->judul ?? '-' }}</h3>

                <p><strong>Peminjam:</strong> {{ $peminjaman->user?->name ?? '-' }}</p>
                <p><strong>Email:</strong> {{ $peminjaman->user?->email ?? '-' }}</p>
                <p><strong>Tanggal Pinjam:</strong> {{ $peminjaman->tanggal_pinjam?->format('d-m-Y') ?? '-' }}</p>
                <p><strong>Tanggal Kembali:</strong> {{ $peminjaman->tanggal_kembali?->format('d-m-Y') ?? '-' }}</p>
                <p>
                    <strong>Status:</strong>
                    <span class="badge badge-{{ $peminjaman->status }}">
                        {{ ucfirst($peminjaman->status) }}
                    </span>
                </p>
                <p><strong>Disetujui Pada:</strong> {{ $peminjaman->disetujui_pada?->format('d-m-Y H:i') ?? '-' }}</p>
                <p><strong>Dikembalikan Pada:</strong> {{ $peminjaman->dikembalikan_pada?->format('d-m-Y H:i') ?? '-' }}</p>
            </div>

            <a class="button" href="{{ url()->previous() }}">Kembali</a>
        </section>

    </div>

@endsection
