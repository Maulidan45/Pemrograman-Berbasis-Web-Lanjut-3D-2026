<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'anggota_id' => Anggota::inRandomOrder()->value('id'),
            'buku_id' => Buku::inRandomOrder()->value('id'),
            'tanggal_pinjam' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'tanggal_kembali' => fake()->boolean(70)
                ? fake()->dateTimeBetween('-20 days', 'now')->format('Y-m-d')
                : null,
        ];
    }
}