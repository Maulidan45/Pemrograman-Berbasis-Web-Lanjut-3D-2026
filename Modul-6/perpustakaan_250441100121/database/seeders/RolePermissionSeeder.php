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

        $passwordAdminAwal = config('app.admin_initial_password');

        if (blank($passwordAdminAwal) && ! User::where('email', 'ikiadmin@gmail.com')->exists()) {
            throw new \RuntimeException('ADMIN_INITIAL_PASSWORD wajib diatur sebelum membuat akun admin awal.');
        }

        $akun = [
            [
                'name' => 'Admin Perpustakaan',
                'email' => 'ikiadmin@gmail.com',
                'no_telepon' => '081200000001',
                'role' => 'admin',
                'password' => $passwordAdminAwal,
            ],
            [
                'name' => 'Petugas Perpustakaan',
                'email' => 'ikipetugas@gmail.com',
                'no_telepon' => '081200000002',
                'role' => 'petugas',
                'password' => 'opojarekowe',
            ],
            [
                'name' => 'Siswa Perpustakaan',
                'email' => 'akuseto@gmail.com',
                'no_telepon' => '081200000003',
                'role' => 'siswa',
                'password' => 'opojarekowe',
            ],
        ];

        foreach ($akun as $data) {
            $user = User::firstOrNew(['email' => $data['email']]);
            $isNewUser = ! $user->exists;

            $user->fill([
                'name' => $data['name'],
                'no_telepon' => $data['no_telepon'],
                'email_verified_at' => now(),
            ]);

            if ($isNewUser) {
                $password = $data['password'];

                if ($data['role'] === 'admin' && blank($password)) {
                    throw new \RuntimeException('ADMIN_INITIAL_PASSWORD wajib diatur saat membuat akun admin awal.');
                }

                $user->password = Hash::make($password);
            }

            $user->save();

            $user->syncRoles([$data['role']]);
        }
    }
}
