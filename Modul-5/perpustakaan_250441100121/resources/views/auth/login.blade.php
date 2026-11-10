@extends('layouts.guest')

@section('title', 'Login')

@section('content')

    <div class="auth-page">

        <h2>Login</h2>

        <p>Masuk untuk mengakses layanan perpustakaan.</p>

        @if (session('status'))
            <div class="auth-status">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-grid form-grid-single">

                <div>
                    <label for="email">Email</label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
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
                        autocomplete="current-password"
                    >

                    @error('password')
                        <span class="input-error">{{ $message }}</span>
                    @enderror
                </div>

                <label class="checkbox-row" for="remember_me">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>Ingat saya</span>
                </label>

            </div>

            <div class="auth-actions">

                <span class="auth-hint">Masuk untuk melanjutkan.</span>

                <button class="btn-primary" type="submit">
                    Login
                </button>

            </div>

        </form>

        <p class="auth-switch">
            Belum punya akun?
            <a href="{{ route('register') }}">Registrasi</a>
        </p>

    </div>

@endsection
