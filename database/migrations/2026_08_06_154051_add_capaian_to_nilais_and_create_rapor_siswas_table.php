<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add fields to nilais table
        Schema::table('nilais', function (Blueprint $table) {
            $table->text('capaian_tertinggi')->nullable();
            $table->text('capaian_terendah')->nullable();
        });

        // 2. Create rapor_siswas table
        Schema::create('rapor_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            $table->integer('sakit')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('alpha')->default(0);
            $table->text('catatan_walas')->nullable();
            $table->string('ekskul_1_nama')->nullable();
            $table->text('ekskul_1_ket')->nullable();
            $table->string('ekskul_2_nama')->nullable();
            $table->text('ekskul_2_ket')->nullable();
            $table->timestamps();

            // Unique index to prevent duplicate records for a student in a class & semester
            $table->unique(['siswa_id', 'kelas_id', 'tahun_ajaran_id'], 'siswa_kelas_ta_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapor_siswas');

        Schema::table('nilais', function (Blueprint $table) {
            $table->dropColumn(['capaian_tertinggi', 'capaian_terendah']);
        });
    }
};
