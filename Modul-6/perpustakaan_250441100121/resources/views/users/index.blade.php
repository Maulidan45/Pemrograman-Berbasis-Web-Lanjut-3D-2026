@extends('layouts.app')

@section('title', 'Data Pengguna')

@section('content')

    <div class="book-page">

        <h2>Data Pengguna</h2>

        <p>Mengelola akun pengguna beserta penetapan role.</p>

        @include('partials.flash')

        <form method="GET" action="{{ route('users.index') }}" class="filter-bar">
            <input
                type="text"
                name="cari"
                value="{{ request('cari') }}"
                placeholder="Cari nama atau email"
            >

            <select name="role">
                <option value="">Semua role</option>

                @foreach ($roles as $role)
                    <option
                        value="{{ $role->name }}"
                        @selected(request('role') === $role->name)
                    >
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>

            <button class="btn-primary" type="submit">Cari</button>

            <a class="btn-secondary btn-link" href="{{ route('users.index') }}">
                Reset
            </a>

            <a class="button" href="{{ route('users.create') }}">
                Tambah Pengguna
            </a>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengguna as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->no_telepon ?? '-' }}</td>
                            <td>
                                @foreach ($item->getRoleNames() as $role)
                                    <span class="badge badge-{{ $role }}">
                                        {{ ucfirst($role) }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="aksi">
                                <a class="btn-edit" href="{{ route('users.edit', $item) }}">
                                    Ubah
                                </a>

                                @if (! auth()->user()->is($item))
                                    <form
                                        method="POST"
                                        action="{{ route('users.destroy', $item) }}"
                                        onsubmit="return confirm('Hapus akun ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn-delete" type="submit">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Belum ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $pengguna->links() }}
        </div>

    </div>

@endsection
