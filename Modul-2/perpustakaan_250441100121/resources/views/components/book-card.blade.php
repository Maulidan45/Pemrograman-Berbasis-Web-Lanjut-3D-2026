<div class="book-card">

    <div class="book-cover">
        <img src="{{ asset('images/buku-' . $id . '.jpg') }}" alt="{{ $judul }}">
    </div>

    <div class="book-info">

        <h3>{{ $judul }}</h3>

        <p>
            <strong>Penulis:</strong>
            {{ $penulis }}
        </p>

        <p>
            <strong>Tahun:</strong>
            {{ $tahun }}
        </p>

        <span class="category">
            {{ $kategori }}
        </span>

        <div>
            {{ $slot }}
        </div>

    </div>

</div>