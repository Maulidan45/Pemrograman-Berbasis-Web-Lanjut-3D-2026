
@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

    <div class="book-page">

        <h2>Daftar Buku</h2>

        <p>Koleksi buku Perpustakaan Myperpus.</p>

        @include('partials.flash')

        <form method="GET" action="{{ route('buku.index') }}" class="filter-bar">
            <input
                type="text"
                name="cari"
                value="{{ request('cari') }}"
                placeholder="Cari judul atau penulis"
            >

            <select name="kategori">
                <option value="">Semua kategori</option>

                @foreach ($kategori as $item)
                    <option
                        value="{{ $item->id }}"
                        @selected(request('kategori') == $item->id)
                    >
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>

            <button class="btn-primary" type="submit">Cari</button>

            <a class="btn-secondary btn-link" href="{{ route('buku.index') }}">
                Reset
            </a>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Tahun</th>
                        <th>Dipinjam</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($buku as $item)
                        <tr>
                            <td>{{ $item->kategoriRelasi?->nama ?? '-' }}</td>
                            <td>
                                <a href="{{ route('buku.show', $item) }}">
                                    {{ $item->judul }}
                                </a>
                            </td>
                            <td>{{ $item->penulis }}</td>
                            <td>{{ $item->tahun_terbit }}</td>
                            <td>{{ $item->peminjamans_count }}x</td>
                            <td class="aksi">
                                @can('kelola-buku')
                                    <a class="btn-edit" href="{{ route('buku.edit', $item) }}">
                                        Ubah
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('buku.destroy', $item) }}"
                                        onsubmit="return confirm('Hapus buku ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn-delete" type="submit">
                                            Hapus
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada buku.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $buku->links() }}
        </div>

        @can('kelola-buku')
            <section class="book-section">
                <h3>Tambah Buku</h3>

                <form method="POST" action="{{ route('buku.store') }}">
                    @csrf

                    <div class="form-grid">
                        <div>
                            <label for="kategori_id">Kategori</label>

                            <select id="kategori_id" name="kategori_id" required>
                                <option value="">Pilih kategori</option>

                                @foreach ($kategori as $item)
                                    <option
                                        value="{{ $item->id }}"
                                        @selected(old('kategori_id') == $item->id)
                                    >
                                        {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>

                            @error('kategori_id')
                                <span class="input-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="judul">Judul Buku</label>

                            <input
                                id="judul"
                                name="judul"
                                value="{{ old('judul') }}"
                                minlength="3"
                                maxlength="150"
                                placeholder="Judul buku"
                                required
                            >

                            @error('judul')
                                <span class="input-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="penulis">Penulis</label>

                            <input
                                id="penulis"
                                name="penulis"
                                value="{{ old('penulis') }}"
                                minlength="3"
                                maxlength="100"
                                placeholder="Nama penulis"
                                required
                            >

                            @error('penulis')
                                <span class="input-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="tahun_terbit">Tahun Terbit</label>

                            <input
                                id="tahun_terbit"
                                name="tahun_terbit"
                                type="number"
                                value="{{ old('tahun_terbit') }}"
                                min="1900"
                                max="{{ date('Y') }}"
                                placeholder="Contoh: 2025"
                                required
                            >

                            @error('tahun_terbit')
                                <span class="input-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button class="btn-primary" type="submit">
                        Tambah Buku
                    </button>
                </form>
            </section>
        @endcan

    </div>

@endsection
