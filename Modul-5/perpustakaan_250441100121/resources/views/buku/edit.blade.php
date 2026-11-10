@extends('layouts.app')

@section('title', 'Ubah Data Buku')

@section('content')

    <div class="book-page">

        <h2>Ubah Data Buku</h2>

        <p>{{ $buku->judul }}</p>

        @include('partials.flash')

        <section class="book-section">
            <h3>Form Ubah Buku</h3>

            <form method="POST" action="{{ route('buku.update', $buku) }}">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div>
                        <label for="kategori_id">Kategori</label>

                        <select id="kategori_id" name="kategori_id" required>
                            @foreach ($kategori as $item)
                                <option
                                    value="{{ $item->id }}"
                                    @selected(old('kategori_id', $buku->kategori_id) == $item->id)
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
                            value="{{ old('judul', $buku->judul) }}"
                            minlength="3"
                            maxlength="150"
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
                            value="{{ old('penulis', $buku->penulis) }}"
                            minlength="3"
                            maxlength="100"
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
                            value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                            min="1900"
                            max="{{ date('Y') }}"
                            required
                        >

                        @error('tahun_terbit')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <button class="btn-primary" type="submit">
                    Simpan Perubahan
                </button>

                <a class="btn-secondary btn-link" href="{{ route('buku.index') }}">
                    Batal
                </a>
            </form>
        </section>

    </div>

@endsection
