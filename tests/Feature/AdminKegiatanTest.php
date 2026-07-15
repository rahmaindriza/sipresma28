<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminKegiatanTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sipresma28.test',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);
    }

    public function test_admin_can_view_kegiatan_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.kegiatan.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Data Kegiatan Sekolah');
    }

    public function test_admin_can_store_kegiatan_with_image(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('photo.jpg');

        $response = $this->actingAs($this->admin)->post(route('admin.kegiatan.store'), [
            'nama_kegiatan' => 'Lomba Melukis',
            'jenis_kegiatan' => 'non-akademik',
            'kategori' => 'perlombaan',
            'deskripsi' => 'Lomba melukis antar kelas.',
            'gambar' => $file,
            'tanggal_kegiatan' => '2026-07-13',
            'semester_aktif' => '2026/2027 Ganjil',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('kegiatans', [
            'nama_kegiatan' => 'Lomba Melukis',
            'jenis_kegiatan' => 'non-akademik',
            'kategori' => 'perlombaan',
        ]);

        $kegiatan = Kegiatan::first();
        Storage::disk('public')->assertExists($kegiatan->gambar);
    }

    public function test_admin_can_fetch_kegiatan_details_for_editing(): void
    {
        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => 'Rapat Pleno',
            'jenis_kegiatan' => 'akademik',
            'kategori' => 'resmi',
            'deskripsi' => 'Rapat koordinasi guru.',
            'gambar' => 'kegiatan/dummy.png',
            'tanggal_kegiatan' => '2026-07-13',
            'semester_aktif' => '2026/2027 Ganjil',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.kegiatan.edit', $kegiatan->id));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'nama_kegiatan' => 'Rapat Pleno',
            'kategori' => 'resmi',
        ]);
    }

    public function test_admin_can_update_kegiatan(): void
    {
        Storage::fake('public');

        $oldFile = UploadedFile::fake()->image('old.jpg');
        $oldPath = $oldFile->store('kegiatan', 'public');

        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => 'Kegiatan Lama',
            'jenis_kegiatan' => 'non-akademik',
            'kategori' => 'ekstrakurikuler',
            'deskripsi' => 'Deskripsi lama.',
            'gambar' => $oldPath,
            'tanggal_kegiatan' => '2026-07-13',
            'semester_aktif' => '2026/2027 Ganjil',
        ]);

        Storage::disk('public')->assertExists($oldPath);

        $newFile = UploadedFile::fake()->image('new.jpg');

        $response = $this->actingAs($this->admin)->put(route('admin.kegiatan.update', $kegiatan->id), [
            'nama_kegiatan' => 'Kegiatan Baru',
            'jenis_kegiatan' => 'akademik',
            'kategori' => 'resmi',
            'deskripsi' => 'Deskripsi baru.',
            'gambar' => $newFile,
            'tanggal_kegiatan' => '2026-07-14',
            'semester_aktif' => '2026/2027 Genap',
        ]);

        $response->assertRedirect();
        $kegiatan->refresh();

        $this->assertEquals('Kegiatan Baru', $kegiatan->nama_kegiatan);
        $this->assertEquals('resmi', $kegiatan->kategori);
        $this->assertEquals('2026-07-14', $kegiatan->tanggal_kegiatan);

        // Assert old image was deleted and new one exists
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($kegiatan->gambar);
    }

    public function test_admin_can_delete_kegiatan(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('delete.jpg');
        $path = $file->store('kegiatan', 'public');

        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => 'Rapat Hapus',
            'jenis_kegiatan' => 'non-akademik',
            'kategori' => 'organisasi',
            'deskripsi' => 'Kegiatan dihapus.',
            'gambar' => $path,
            'tanggal_kegiatan' => '2026-07-13',
            'semester_aktif' => '2026/2027 Ganjil',
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)->delete(route('admin.kegiatan.destroy', $kegiatan->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('kegiatans', ['id' => $kegiatan->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
