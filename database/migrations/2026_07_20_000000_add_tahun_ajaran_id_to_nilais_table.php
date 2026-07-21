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
        if (!Schema::hasColumn('nilais', 'tahun_ajaran_id')) {
            Schema::table('nilais', function (Blueprint $table) {
                $table->foreignId('tahun_ajaran_id')->nullable()->after('kelas_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            });
        }

        // Populate existing records with the active or first academic year ID
        $activeTa = DB::table('tahun_ajarans')->where('status', 'aktif')->first()
                 ?? DB::table('tahun_ajarans')->first();
                 
        if ($activeTa) {
            DB::table('nilais')->whereNull('tahun_ajaran_id')->update(['tahun_ajaran_id' => $activeTa->id]);
        }

        Schema::table('nilais', function (Blueprint $table) {
            // Drop foreign keys first to allow dropping the index
            try {
                $table->dropForeign(['siswa_id']);
            } catch (\Exception $e) {}
            try {
                $table->dropForeign(['mapel_id']);
            } catch (\Exception $e) {}
            try {
                $table->dropForeign(['kelas_id']);
            } catch (\Exception $e) {}
            
            // Drop unique constraint
            try {
                $table->dropUnique('siswa_mapel_kelas_unique');
            } catch (\Exception $e) {}

            // Make it non-nullable now that we populated it
            $table->unsignedBigInteger('tahun_ajaran_id')->nullable(false)->change();

            // Create new unique constraint including academic year
            try {
                $table->unique(['siswa_id', 'mapel_id', 'kelas_id', 'tahun_ajaran_id'], 'siswa_mapel_kelas_ta_unique');
            } catch (\Exception $e) {}

            // Re-add foreign keys
            try {
                $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
            } catch (\Exception $e) {}
            try {
                $table->foreign('mapel_id')->references('id')->on('mapels')->onDelete('cascade');
            } catch (\Exception $e) {}
            try {
                $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
            } catch (\Exception $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            try {
                $table->dropUnique('siswa_mapel_kelas_ta_unique');
            } catch (\Exception $e) {}
            try {
                $table->dropForeign(['tahun_ajaran_id']);
            } catch (\Exception $e) {}
            try {
                $table->dropColumn('tahun_ajaran_id');
            } catch (\Exception $e) {}
            try {
                $table->unique(['siswa_id', 'mapel_id', 'kelas_id'], 'siswa_mapel_kelas_unique');
            } catch (\Exception $e) {}
        });
    }
};
