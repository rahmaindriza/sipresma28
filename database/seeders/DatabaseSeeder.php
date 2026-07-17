<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Prestasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tahun Ajaran Default
        TahunAjaran::create([
            'tahun' => '2025/2026',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);

        // 2. Akun Master Admin
        User::create([
            'name' => 'Administrator Utama',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'password_plain' => 'password',
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        // 3. Data Master Mata Pelajaran Awal (KKM 75)
        Mapel::create(['nama_mapel' => 'Matematika', 'kode_mapel' => 'MTK', 'jenis_mapel' => 'umum', 'kkm' => 75]);
        Mapel::create(['nama_mapel' => 'Bahasa Indonesia', 'kode_mapel' => 'BINDO', 'jenis_mapel' => 'umum', 'kkm' => 75]);
        Mapel::create(['nama_mapel' => 'Ilmu Pengetahuan Alam', 'kode_mapel' => 'IPA', 'jenis_mapel' => 'umum', 'kkm' => 75]);
        Mapel::create(['nama_mapel' => 'Ilmu Pengetahuan Sosial', 'kode_mapel' => 'IPS', 'jenis_mapel' => 'umum', 'kkm' => 75]);
        Mapel::create(['nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan', 'kode_mapel' => 'PKN', 'jenis_mapel' => 'umum', 'kkm' => 75]);
        Mapel::create(['nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'kode_mapel' => 'PJOK', 'jenis_mapel' => 'khusus', 'kkm' => 75]);
        Mapel::create(['nama_mapel' => 'Pendidikan Agama Islam', 'kode_mapel' => 'PAI', 'jenis_mapel' => 'khusus', 'kkm' => 75]);
        Mapel::create(['nama_mapel' => 'Seni Budaya dan Prakarya', 'kode_mapel' => 'SBDP', 'jenis_mapel' => 'khusus', 'kkm' => 75]);

        // 4. Seed Wali Kelas Zukira
        $userWali = User::create([
            'name' => 'zukira',
            'email' => 'zukira@gmail.com',
            'username' => 'zukira',
            'password' => Hash::make('password'),
            'password_plain' => 'password',
            'role' => 'wali_kelas',
            'status_akun' => 'aktif',
        ]);

        $guruWali = Guru::create([
            'user_id' => $userWali->id,
            'nip' => 'zukira',
            'nama' => 'zukira',
            'jabatan' => 'Wali Kelas',
            'jk' => 'Perempuan',
        ]);

        // 5. Seed Kelas 6-A managed by Zukira
        $kelas = Kelas::create([
            'nama_kelas' => 'Kelas 6-A',
            'wali_kelas_id' => $guruWali->id,
        ]);

        // 6. Seed Students (Siswa) for Kelas 6-A
        $siswa1 = Siswa::create([
            'kelas_id' => $kelas->id,
            'nisn' => '0112233441',
            'nama' => 'Rian Hidayat',
            'jk' => 'Laki-laki',
            'tempat_lahir' => 'Kinali',
            'tanggal_lahir' => '2014-05-12',
            'nik' => '1201123456789001',
            'agama' => 'Islam',
            'alamat' => 'Jln. Raya Kinali No. 12',
        ]);

        $siswa2 = Siswa::create([
            'kelas_id' => $kelas->id,
            'nisn' => '0112233442',
            'nama' => 'Siti Rahma',
            'jk' => 'Perempuan',
            'tempat_lahir' => 'Kinali',
            'tanggal_lahir' => '2014-09-22',
            'nik' => '1201123456789002',
            'agama' => 'Islam',
            'alamat' => 'Jln. Raya Kinali No. 45',
        ]);

        // 7. Seed Achievements (Prestasi)
        Prestasi::create([
            'siswa_id' => $siswa1->id,
            'nama_lomba' => 'Lomba OSN Matematika Kabupaten',
            'kategori' => 'Akademik',
            'jenis_pelaksanaan' => 'Luar Sekolah',
            'tingkat' => 'Kabupaten',
            'juara' => 'Juara 1',
            'poin' => 30,
            'tanggal_penghargaan' => '2026-02-15',
        ]);

        Prestasi::create([
            'siswa_id' => $siswa2->id,
            'nama_lomba' => 'FLS2N Seni Tari Provinsi',
            'kategori' => 'Non-Akademik',
            'jenis_pelaksanaan' => 'Luar Sekolah',
            'tingkat' => 'Provinsi',
            'juara' => 'Juara 2',
            'poin' => 50,
            'tanggal_penghargaan' => '2026-03-10',
        ]);
    }
}
