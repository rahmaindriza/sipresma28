<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mapel;
use App\Models\TahunAjaran;
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

        // 2. Akun Master Admin (Satu-satunya akun awal untuk masuk sistem)
        User::create([
            'name' => 'Administrator Utama',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
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
    }
}
