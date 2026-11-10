@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')

    <div class="book-page">

        <h2>Tambah Pengguna</h2>

        <p>Membuat akun pengguna baru beserta penetapan role.</p>

        @include('partials.flash')

        <section class="book-section">
            <h3>Form Pengguna</h3>

            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="form-grid">
                    <div>
                        <label for="name">Nama</label>

                        <input id="name" name="name" value="{{ old('name') }}" required>

                        @error('name')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="email">Email</label>

                        <input id="email" name="email" type="email" value="{{ old('email') }}" required>

                        @error('email')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="no_telepon">No. Telepon</label>

                        <input id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}">

                        @error('no_telepon')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="role">Role</label>

                        <select id="role" name="role" required>
                            <option value="">Pilih role</option>

                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(old('role') === $role->name)>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>

                        @error('role')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password">Password</label>

                        <input id="password" name="password" type="password" required autocomplete="new-password">

                        @error('password')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation">Konfirmasi Password</label>

                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
                    </div>
                </div>

                <button class="btn-primary" type="submit">
                    Simpan Pengguna
                </button>

                <a class="btn-secondary btn-link" href="{{ route('users.index') }}">
                    Batal
                </a>
            </form>
        </section>

    </div>

@endsection
