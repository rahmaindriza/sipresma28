<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update PJOK, PAI, SBDP to 'umum' (Wajib)
        DB::table('mapels')
            ->whereIn('kode_mapel', ['PJOK', 'PAI', 'SBDP'])
            ->update(['jenis_mapel' => 'umum']);

        // 2. Insert Keminangkabauan (BAM) and Pendidikan Qur'an as 'khusus' (Pilihan)
        $bamExists = DB::table('mapels')->where('kode_mapel', 'BAM')->exists();
        if (!$bamExists) {
            DB::table('mapels')->insert([
                'nama_mapel' => 'Keminangkabauan (BAM)',
                'kode_mapel' => 'BAM',
                'jenis_mapel' => 'khusus',
                'kkm' => 75,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $quranExists = DB::table('mapels')->where('kode_mapel', 'QURAN')->exists();
        if (!$quranExists) {
            DB::table('mapels')->insert([
                'nama_mapel' => 'Pendidikan Qur\'an',
                'kode_mapel' => 'QURAN',
                'jenis_mapel' => 'khusus',
                'kkm' => 75,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('mapels')
            ->whereIn('kode_mapel', ['PJOK', 'PAI', 'SBDP'])
            ->update(['jenis_mapel' => 'khusus']);

        DB::table('mapels')->whereIn('kode_mapel', ['BAM', 'QURAN'])->delete();
    }
};
