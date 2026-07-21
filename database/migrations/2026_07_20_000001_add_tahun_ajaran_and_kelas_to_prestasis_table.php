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
        Schema::table('prestasis', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->after('siswa_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            $table->foreignId('kelas_id')->nullable()->after('tahun_ajaran_id')->constrained('kelas')->cascadeOnDelete();
        });

        // Populate existing records
        $activeTa = DB::table('tahun_ajarans')->where('status', 'aktif')->first()
                 ?? DB::table('tahun_ajarans')->first();

        $prestasis = DB::table('prestasis')->get();
        foreach ($prestasis as $p) {
            $siswa = DB::table('siswas')->where('id', $p->siswa_id)->first();
            $kelasId = $siswa ? $siswa->kelas_id : null;
            
            DB::table('prestasis')
                ->where('id', $p->id)
                ->update([
                    'tahun_ajaran_id' => $activeTa ? $activeTa->id : null,
                    'kelas_id' => $kelasId,
                ]);
        }

        Schema::disableForeignKeyConstraints();
        Schema::table('prestasis', function (Blueprint $table) {
            $table->unsignedBigInteger('tahun_ajaran_id')->nullable(false)->change();
            $table->unsignedBigInteger('kelas_id')->nullable(false)->change();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('prestasis', function (Blueprint $table) {
            try {
                $table->dropForeign(['tahun_ajaran_id']);
            } catch (\Exception $e) {}
            try {
                $table->dropColumn('tahun_ajaran_id');
            } catch (\Exception $e) {}
            try {
                $table->dropForeign(['kelas_id']);
            } catch (\Exception $e) {}
            try {
                $table->dropColumn('kelas_id');
            } catch (\Exception $e) {}
        });
        Schema::enableForeignKeyConstraints();
    }
};
