<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RbacPerpustakaanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    protected function user(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    protected function buku(): Buku
    {
        $kategori = Kategori::create(['nama' => 'Teknologi']);

        return Buku::create([
            'kategori_id' => $kategori->id,
            'judul' => 'Belajar Laravel',
            'penulis' => 'Penulis Uji',
            'tahun_terbit' => 2024,
        ]);
    }

    protected function anggota(User $user): Anggota
    {
        return Anggota::create([
            'nama' => $user->name,
            'email' => $user->email,
            'no_telepon' => '0812',
        ]);
    }

    protected function peminjaman(User $user, Buku $buku, string $status = 'disetujui'): Peminjaman
    {
        return Peminjaman::create([
            'user_id' => $user->id,
            'anggota_id' => $this->anggota($user)->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => $status,
        ]);
    }

    public function test_kata_sandi_disimpan_dengan_hashing(): void
    {
        $this->post('/register', [
            'name' => 'Siswa Baru',
            'email' => 'siswabaru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/dashboard');

        $user = User::where('email', 'siswabaru@example.com')->first();

        $this->assertNotNull($user);
        $this->assertNotSame('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_registrasi_tidak_dapat_memilih_role(): void
    {
        $this->post('/register', [
            'name' => 'Penyusup',
            'email' => 'penyusup@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $user = User::where('email', 'penyusup@example.com')->first();

        $this->assertTrue($user->hasRole('siswa'));
        $this->assertFalse($user->hasRole('admin'));
    }

    public function test_login_kredensial_valid_berhasil(): void
    {
        $user = $this->user('siswa');

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_kredensial_tidak_valid_gagal(): void
    {
        $user = $this->user('siswa');

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'salah-password',
        ])->assertSessionHasErrors();

        $this->assertGuest();
    }

    public function test_logout_mengakhiri_session(): void
    {
        $user = $this->user('siswa');

        $this->actingAs($user)->post('/logout')->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_belum_login_dialihkan_ke_halaman_login(): void
    {
        $this->get('/')->assertRedirect('/login');
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/buku')->assertRedirect('/login');
        $this->get('/peminjaman-saya')->assertRedirect('/login');
        $this->get('/profile')->assertRedirect('/login');
    }

    public function test_guest_tidak_dapat_membuka_halaman_login_dan_registrasi(): void
    {
        $user = $this->user('siswa');

        $this->actingAs($user)->get('/login')->assertRedirect('/dashboard');
        $this->actingAs($user)->get('/register')->assertRedirect('/dashboard');
    }

    public function test_admin_dapat_membuka_dashboard_admin(): void
    {
        $this->actingAs($this->user('admin'))
            ->get('/dashboard')
            ->assertOk();
    }

    public function test_petugas_dapat_membuka_dashboard_petugas(): void
    {
        $this->actingAs($this->user('petugas'))
            ->get('/dashboard')
            ->assertOk();
    }

    public function test_siswa_dapat_melihat_daftar_dan_detail_buku(): void
    {
        $siswa = $this->user('siswa');
        $buku = $this->buku();

        $this->actingAs($siswa)->get('/buku')->assertOk();
        $this->actingAs($siswa)->get("/buku/{$buku->id}")->assertOk();
    }

    public function test_admin_dapat_mengelola_data_buku(): void
    {
        $admin = $this->user('admin');
        $buku = $this->buku();

        $this->actingAs($admin)
            ->post('/buku', [
                'kategori_id' => $buku->kategori_id,
                'judul' => 'Buku Baru',
                'penulis' => 'Penulis Baru',
                'tahun_terbit' => 2025,
            ])->assertRedirect('/buku');

        $this->assertDatabaseHas('bukus', ['judul' => 'Buku Baru']);

        $this->actingAs($admin)->get("/buku/{$buku->id}/edit")->assertOk();

        $this->actingAs($admin)
            ->put("/buku/{$buku->id}", [
                'kategori_id' => $buku->kategori_id,
                'judul' => 'Judul Diubah',
                'penulis' => $buku->penulis,
                'tahun_terbit' => $buku->tahun_terbit,
            ])->assertRedirect('/buku');

        $this->assertDatabaseHas('bukus', ['judul' => 'Judul Diubah']);

        $this->actingAs($admin)
            ->delete("/buku/{$buku->id}")
            ->assertRedirect('/buku');

        $this->assertDatabaseMissing('bukus', ['id' => $buku->id]);
    }

    public function test_admin_dapat_mengelola_akun_dan_role_pengguna(): void
    {
        $admin = $this->user('admin');

        $this->actingAs($admin)
            ->post('/users', [
                'name' => 'Petugas Baru',
                'email' => 'petugasbaru@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'petugas',
            ])->assertRedirect('/users');

        $baru = User::where('email', 'petugasbaru@example.com')->first();

        $this->assertTrue($baru->hasRole('petugas'));

        $this->actingAs($admin)->get("/users/{$baru->id}/edit")->assertOk();

        $this->actingAs($admin)
            ->put("/users/{$baru->id}", [
                'name' => 'Petugas Diubah',
                'email' => 'petugasbaru@example.com',
                'role' => 'siswa',
            ])->assertRedirect('/users');

        $this->assertTrue($baru->fresh()->hasRole('siswa'));
        $this->assertFalse($baru->fresh()->hasRole('petugas'));
    }

    public function test_admin_dapat_mengakses_peminjaman_pengembalian_dan_laporan(): void
    {
        $admin = $this->user('admin');

        $this->actingAs($admin)->get('/peminjaman')->assertOk();
        $this->actingAs($admin)->get('/laporan')->assertOk();
    }

    public function test_petugas_dapat_mengelola_buku(): void
    {
        $petugas = $this->user('petugas');
        $buku = $this->buku();

        $this->actingAs($petugas)
            ->post('/buku', [
                'kategori_id' => $buku->kategori_id,
                'judul' => 'Buku Petugas',
                'penulis' => 'Penulis Uji',
                'tahun_terbit' => 2023,
            ])->assertRedirect('/buku');

        $this->assertDatabaseHas('bukus', ['judul' => 'Buku Petugas']);
    }

    public function test_petugas_dapat_memproses_peminjaman_dan_pengembalian(): void
    {
        $petugas = $this->user('petugas');
        $siswa = $this->user('siswa');
        $buku = $this->buku();

        $peminjaman = $this->peminjaman($siswa, $buku, 'menunggu');

        $this->actingAs($petugas)
            ->patch("/peminjaman/{$peminjaman->id}/setujui")
            ->assertRedirect();

        $this->assertSame('disetujui', $peminjaman->fresh()->status);

        $this->actingAs($petugas)
            ->patch("/peminjaman/{$peminjaman->id}/kembalikan")
            ->assertRedirect();

        $this->assertSame('dikembalikan', $peminjaman->fresh()->status);
    }

    public function test_petugas_tidak_dapat_membuka_pengelolaan_akun_atau_role(): void
    {
        $petugas = $this->user('petugas');

        $this->actingAs($petugas)->get('/users')->assertForbidden();
        $this->actingAs($petugas)->get('/users/create')->assertForbidden();
    }

    public function test_siswa_dapat_melihat_dan_mengubah_profil_sendiri(): void
    {
        $siswa = $this->user('siswa');

        $this->actingAs($siswa)->get('/profile')->assertOk();

        $this->actingAs($siswa)
            ->patch('/profile', [
                'name' => 'Nama Baru Siswa',
                'email' => $siswa->email,
            ])->assertRedirect('/profile');

        $this->assertSame('Nama Baru Siswa', $siswa->fresh()->name);
    }

    public function test_siswa_dapat_melihat_riwayat_peminjaman_sendiri(): void
    {
        $siswa = $this->user('siswa');
        $buku = $this->buku();

        $this->peminjaman($siswa, $buku);

        $this->actingAs($siswa)->get('/dashboard')->assertOk();

        $this->actingAs($siswa)
            ->get('/peminjaman-saya')
            ->assertOk()
            ->assertSee('Belajar Laravel');
    }

    public function test_siswa_tidak_dapat_membuka_kelola_buku_pengguna_dan_laporan(): void
    {
        $siswa = $this->user('siswa');
        $buku = $this->buku();

        // Tidak boleh mengelola (tambah/ubah/hapus) buku.
        $this->actingAs($siswa)
            ->post('/buku', [
                'kategori_id' => $buku->kategori_id,
                'judul' => 'Buku Siswa',
                'penulis' => 'Penulis Uji',
                'tahun_terbit' => 2024,
            ])->assertForbidden();

        $this->actingAs($siswa)->get("/buku/{$buku->id}/edit")->assertForbidden();
        $this->actingAs($siswa)->delete("/buku/{$buku->id}")->assertForbidden();

        // Tidak boleh membuka halaman admin.
        $this->actingAs($siswa)->get('/users')->assertForbidden();
        $this->actingAs($siswa)->get('/laporan')->assertForbidden();
        $this->actingAs($siswa)->get('/peminjaman')->assertForbidden();
    }

    public function test_siswa_tidak_dapat_melihat_riwayat_peminjaman_siswa_lain(): void
    {
        $siswa = $this->user('siswa');
        $lain = $this->user('siswa');
        $buku = $this->buku();

        $peminjaman = $this->peminjaman($lain, $buku);

        $this->actingAs($siswa)
            ->get("/peminjaman/{$peminjaman->id}")
            ->assertForbidden();

        $this->actingAs($lain)
            ->get("/peminjaman/{$peminjaman->id}")
            ->assertOk();
    }

    public function test_setelah_logout_harus_login_kembali(): void
    {
        $user = $this->user('admin');

        $this->actingAs($user)->post('/logout');
        $this->assertGuest();

        $this->get('/buku')->assertRedirect('/login');
    }
}
