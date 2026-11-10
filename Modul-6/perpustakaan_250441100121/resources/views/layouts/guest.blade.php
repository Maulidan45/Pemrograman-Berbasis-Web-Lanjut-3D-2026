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

    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2026 Myperpus</p>
    </footer>

</body>

</html>
