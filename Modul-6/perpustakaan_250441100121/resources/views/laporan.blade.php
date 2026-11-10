@extends('layouts.app')

@section('title', 'Laporan Perpustakaan')

@section('content')

    <div class="book-page">

        <h2>Laporan Perpustakaan</h2>

        <p>Rekapitulasi data perpustakaan.</p>

        <div class="stat-grid">
            <div class="stat-card">
                <span>Total Buku</span>
                <strong>{{ $total['buku'] }}</strong>
            </div>

            <div class="stat-card">
                <span>Total Pengguna</span>
                <strong>{{ $total['pengguna'] }}</strong>
            </div>

            <div class="stat-card">
                <span>Total Peminjaman</span>
                <strong>{{ $total['peminjaman'] }}</strong>
            </div>

            <div class="stat-card">
                <span>Terlambat</span>
                <strong>{{ $total['terlambat'] }}</strong>
            </div>
        </div>

        <section class="book-section">
            <h3>Rekap Status Peminjaman</h3>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rekapStatus as $status => $jumlah)
                            <tr>
                                <td>
                                    <span class="badge badge-{{ $status }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>{{ $jumlah }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="book-section">
            <h3>Buku Terpopuler</h3>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Jumlah Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bukuTerpopuler as $item)
                            <tr>
                                <td>{{ $item->judul }}</td>
                                <td>{{ $item->kategoriRelasi?->nama ?? '-' }}</td>
                                <td>{{ $item->peminjamans_count }}x</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="book-section">
            <h3>Buku per Kategori</h3>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Jumlah Buku</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bukuPerKategori as $item)
                            <tr>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->bukus_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="book-section">
            <h3>Peminjaman Terbaru</h3>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjamanTerbaru as $item)
                            <tr>
                                <td>{{ $item->user?->name ?? '-' }}</td>
                                <td>{{ $item->buku?->judul ?? '-' }}</td>
                                <td>{{ $item->tanggal_pinjam?->format('d-m-Y') ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $item->status }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Belum ada data peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </div>

@endsection
