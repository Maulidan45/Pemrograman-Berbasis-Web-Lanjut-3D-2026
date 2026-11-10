@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

    <div class="book-page">

        <h2>Profil Saya</h2>

        <p>
            Data profil milik akun yang sedang login
            ({{ $user->email }}).
        </p>

        @if (session('status') === 'profile-updated')
            <div class="alert-success">
                Profil berhasil diperbarui.
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="book-section">
            <h3>Informasi Profil</h3>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="form-grid">
                    <div>
                        <label for="name">Nama</label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            autocomplete="name"
                        >
                    </div>

                    <div>
                        <label for="email">Email</label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            autocomplete="username"
                        >
                    </div>

                    <div>
                        <label for="no_telepon">No. Telepon</label>

                        <input
                            id="no_telepon"
                            type="text"
                            name="no_telepon"
                            value="{{ old('no_telepon', $user->no_telepon) }}"
                            autocomplete="tel"
                        >
                    </div>

                    <div>
                        <label>Role</label>

                        <input
                            type="text"
                            value="{{ $user->getRoleNames()->implode(', ') }}"
                            disabled
                        >
                    </div>
                </div>

                <button class="btn-primary" type="submit">
                    Simpan Perubahan
                </button>
            </form>
        </section>

    </div>

@endsection
