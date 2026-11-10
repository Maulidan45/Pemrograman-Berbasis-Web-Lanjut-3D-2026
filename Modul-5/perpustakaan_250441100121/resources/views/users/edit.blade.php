@extends('layouts.app')

@section('title', 'Ubah Pengguna')

@section('content')

    <div class="book-page">

        <h2>Ubah Pengguna &amp; Role</h2>

        <p>{{ $pengguna->email }}</p>

        @include('partials.flash')

        <section class="book-section">
            <h3>Form Pengguna</h3>

            <form method="POST" action="{{ route('users.update', $pengguna) }}">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div>
                        <label for="name">Nama</label>

                        <input id="name" name="name" value="{{ old('name', $pengguna->name) }}" required>

                        @error('name')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="email">Email</label>

                        <input id="email" name="email" type="email" value="{{ old('email', $pengguna->email) }}" required>

                        @error('email')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="no_telepon">No. Telepon</label>

                        <input id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $pengguna->no_telepon) }}">

                        @error('no_telepon')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="role">Role</label>

                        <select id="role" name="role" required>
                            @foreach ($roles as $role)
                                <option
                                    value="{{ $role->name }}"
                                    @selected(old('role', $pengguna->getRoleNames()->first()) === $role->name)
                                >
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>

                        @error('role')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password">Password Baru (opsional)</label>

                        <input id="password" name="password" type="password" autocomplete="new-password">

                        @error('password')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation">Konfirmasi Password Baru</label>

                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                    </div>
                </div>

                <button class="btn-primary" type="submit">
                    Simpan Perubahan
                </button>

                <a class="btn-secondary btn-link" href="{{ route('users.index') }}">
                    Batal
                </a>
            </form>
        </section>

    </div>

@endsection
