@extends('layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')

    <div class="book-page">

        <h2>Peminjaman Saya</h2>

        <p>Daftar riwayat peminjaman milik Anda sendiri.</p>

        @include('partials.flash')

        @auth
            <section class="book-section">
                <h3>Buku yang Tersedia untuk Dipinjam</h3>

                <form method="POST" action="{{ route('peminjaman.store') }}" class="pinjam-form">
                    @csrf

                    <div class="select-row">
                        <label for="buku_id">Pilih Buku</label>

                        <select id="buku_id" name="buku_id" required>
                            <option value="">-- Pilih buku --</option>

                            @foreach ($buku as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->judul }} ({{ $item->kategoriRelasi?->nama ?? '-' }})
                                </option>
                            @endforeach
                        </select>

                        <button class="btn-primary" type="submit">Ajukan Peminjaman</button>
                    </div>
                </form>
            </section>
        @endauth

        <section class="book-section">
            <h3>Riwayat Peminjaman</h3>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjaman as $item)
                            <tr>
                                <td>{{ $item->buku?->judul ?? '-' }}</td>
                                <td>{{ $item->tanggal_pinjam?->format('d-m-Y') ?? '-' }}</td>
                                <td>{{ $item->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                                <td><span class="badge badge-{{ $item->status }}">{{ ucfirst($item->status) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Anda belum memiliki riwayat peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </div>

@endsection
