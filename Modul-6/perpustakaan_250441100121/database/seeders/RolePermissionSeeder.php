<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Password untuk semua akun demo.
     */
    public const PASSWORD = 'opojarekowe';

    /**
     * Nama permission sesuai tabel Modul V.
     */
    public const PERMISSIONS = [
        'akses dashboard admin',
        'akses dashboard petugas',
        'lihat daftar buku',
        'kelola data buku',
        'kelola data pengguna',
        'kelola role pengguna',
        'kelola peminjaman',
        'kelola pengembalian',
        'mengajukan peminjaman buku',
        'memproses persetujuan peminjaman',
        'mencatat pengembalian buku',
        'melihat status peminjaman sendiri',
        'melihat riwayat peminjaman sendiri',
        'lihat laporan perpustakaan',
        'lihat profil sendiri',
        'ubah profil sendiri',
    ];

    /**
     * Pembagian hak akses setiap role.
     */
    public const HAK_AKSES = [
        'admin' => [
            'akses dashboard admin',
            'lihat daftar buku',
            'kelola data buku',
            'kelola data pengguna',
            'kelola role pengguna',
            'kelola peminjaman',
            'kelola pengembalian',
            'mengajukan peminjaman buku',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ],
        'petugas' => [
            'akses dashboard petugas',
            'lihat daftar buku',
            'kelola data buku',
            'kelola peminjaman',
            'kelola pengembalian',
            'mengajukan peminjaman buku',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ],
        'siswa' => [
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ],
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        foreach (self::HAK_AKSES as $roleName => $permissions) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions($permissions);
        }

        $akun = [
            [
                'name' => 'Admin Perpustakaan',
                'email' => 'ikiadmin@gmail.com',
                'no_telepon' => '081200000001',
                'role' => 'admin',
            ],
            [
                'name' => 'Petugas Perpustakaan',
                'email' => 'ikipetugas@gmail.com',
                'no_telepon' => '081200000002',
                'role' => 'petugas',
            ],
            [
                'name' => 'Siswa Perpustakaan',
                'email' => 'akuseto@gmail.com',
                'no_telepon' => '081200000003',
                'role' => 'siswa',
            ],
        ];

        foreach ($akun as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'no_telepon' => $data['no_telepon'],
                    'password' => Hash::make(self::PASSWORD),
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles([$data['role']]);
        }
    }
}
