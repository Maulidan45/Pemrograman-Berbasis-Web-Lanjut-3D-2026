<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Perpustakaan')</title>

    @vite(['resources/css/app.css'])
</head>

<body>

    <header class="header">

        <div class="logo">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="Logo Perpustakaan"
            >

            <div>
                <h1>Myperpus</h1>
                <span>Ruang Baca Digital</span>
            </div>
        </div>

        <div class="menu">
            <input
                type="checkbox"
                id="menu-toggle"
            >

            <label
                for="menu-toggle"
                class="hamburger"
            >
                ☰
            </label>

            <div class="menu-list">
                <a href="{{ route('dashboard') }}">
                    Dashboard
                </a>

                @can('lihat daftar buku')
                    <a href="{{ route('buku.index') }}">
                        Daftar Buku
                    </a>
                @endcan

                @can('kelola peminjaman')
                    <a href="{{ route('peminjaman.index') }}">
                        Data Peminjaman
                    </a>
                @endcan

                @can('melihat status peminjaman sendiri')
                    <a href="{{ route('peminjaman.saya') }}">
                        Peminjaman Saya
                    </a>
                @endcan

                @can('lihat laporan perpustakaan')
                    <a href="{{ route('laporan.index') }}">
                        Laporan
                    </a>
                @endcan

                @can('kelola data pengguna')
                    <a href="{{ route('users.index') }}">
                        Data Pengguna
                    </a>
                @endcan

                <a href="{{ route('profile.edit') }}">
                    Profil ({{ auth()->user()->name }})
                </a>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button type="submit" class="menu-logout">
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2026 Myperpus</p>
    </footer>

</body>

</html>
