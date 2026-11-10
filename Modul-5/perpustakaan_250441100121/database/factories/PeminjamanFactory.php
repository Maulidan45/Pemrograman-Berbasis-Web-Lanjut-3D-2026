<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\User;
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
            'user_id' => User::whereIn('id', function ($query) {
                $query->select('model_id')
                    ->from('model_has_roles')
                    ->where('model_type', User::class)
                    ->whereIn('role_id', function ($roleQuery) {
                        $roleQuery->select('id')
                            ->from('roles')
                            ->where('name', 'siswa');
                    });
            })->inRandomOrder()->value('id'),
            'buku_id' => Buku::inRandomOrder()->value('id'),
            'tanggal_pinjam' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'tanggal_kembali' => fake()->boolean(70)
                ? fake()->dateTimeBetween('-20 days', 'now')->format('Y-m-d')
                : null,
            'status' => fake()->randomElement(['menunggu', 'disetujui', 'dikembalikan']),
        ];
    }
}