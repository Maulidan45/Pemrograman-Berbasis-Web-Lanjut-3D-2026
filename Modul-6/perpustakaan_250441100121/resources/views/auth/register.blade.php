@extends('layouts.guest')

@section('title', 'Registrasi')

@section('content')

    <div class="auth-page">

        <h2>Registrasi</h2>

        <p>Buat akun untuk mulai menggunakan perpustakaan digital.</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-grid form-grid-single">

                <div>
                    <label for="name">Nama</label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                    >

                    @error('name')
                        <span class="input-error">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="email">Email</label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                    >

                    @error('email')
                        <span class="input-error">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password">Password</label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                    >

                    @error('password')
                        <span class="input-error">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation">Konfirmasi Password</label>

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                    >

                    @error('password_confirmation')
                        <span class="input-error">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="auth-actions">

                <a href="{{ route('login') }}">
                    Sudah punya akun?
                </a>

                <button class="btn-primary" type="submit">
                    Registrasi
                </button>

            </div>

        </form>

    </div>

@endsection
