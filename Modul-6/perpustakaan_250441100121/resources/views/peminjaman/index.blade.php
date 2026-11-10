@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')

    <div class="book-page">

        <h2>Data Peminjaman</h2>

        <p>Memproses pengajuan peminjaman dan pengembalian buku.</p>

        @include('partials.flash')

        <form method="GET" action="{{ route('peminjaman.index') }}" class="filter-bar">
            <input
                type="text"
                name="cari"
                value="{{ request('cari') }}"
                placeholder="Cari nama atau judul buku"
            >

            <select name="status">
                <option value="">Semua status</option>

                @foreach ($statusList as $status)
                    <option
                        value="{{ $status }}"
                        @selected(request('status') === $status)
                    >
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>

            <button class="btn-primary" type="submit">Cari</button>

            <a class="btn-secondary btn-link" href="{{ route('peminjaman.index') }}">
                Reset
            </a>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjaman as $item)
                        <tr>
                            <td>{{ $item->user?->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('peminjaman.show', $item) }}">
                                    {{ $item->buku?->judul ?? '-' }}
                                </a>
                            </td>
                            <td>{{ $item->tanggal_pinjam?->format('d-m-Y') ?? '-' }}</td>
                            <td>{{ $item->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $item->status }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="aksi">
                                @if ($item->status === 'menunggu')
                                    @can('memproses persetujuan peminjaman')
                                        <form method="POST" action="{{ route('peminjaman.setujui', $item) }}">
                                            @csrf
                                            <button class="btn-edit" type="submit">Setujui</button>
                                        </form>

                                        <form method="POST" action="{{ route('peminjaman.tolak', $item) }}">
                                            @csrf
                                            <button class="btn-delete" type="submit">Tolak</button>
                                        </form>
                                    @endcan
                                @endif

                                @if ($item->status === 'disetujui')
                                    @can('mencatat pengembalian buku')
                                        <form method="POST" action="{{ route('peminjaman.kembalikan', $item) }}">
                                            @csrf
                                            <button class="btn-success" type="submit">Kembalikan</button>
                                        </form>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada data peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $peminjaman->links() }}
        </div>

    </div>

@endsection
