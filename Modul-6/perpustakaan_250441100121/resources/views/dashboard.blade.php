@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="book-page">

        <h2>Dashboard</h2>

        <p>
            Selamat datang, {{ auth()->user()->name }}.
            Anda login sebagai {{ ucfirst(auth()->user()->getRoleNames()->first() ?? '-') }}.
        </p>

        <div class="stat-grid">
            @foreach ($kartu as $item)
                <div class="stat-card">
                    <span>{{ $item['label'] }}</span>
                    <strong>{{ $item['nilai'] }}</strong>
                </div>
            @endforeach
        </div>

    </div>

@endsection
