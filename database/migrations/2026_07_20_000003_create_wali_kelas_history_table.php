<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wali_kelas_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            $table->foreignId('guru_id')->nullable()->constrained('gurus')->nullOnDelete();
            $table->timestamps();

            $table->unique(['kelas_id', 'tahun_ajaran_id'], 'wkh_kelas_ta_unique');
        });

        // Pre-populate with current wali kelas assignments for the active academic year
        $activeTa = DB::table('tahun_ajarans')->where('status', 'aktif')->first()
                 ?? DB::table('tahun_ajarans')->first();

        if ($activeTa) {
            $kelasList = DB::table('kelas')->whereNotNull('wali_kelas_id')->get();
            foreach ($kelasList as $kls) {
                DB::table('wali_kelas_history')->insert([
                    'kelas_id' => $kls->id,
                    'tahun_ajaran_id' => $activeTa->id,
                    'guru_id' => $kls->wali_kelas_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wali_kelas_history');
    }
};
