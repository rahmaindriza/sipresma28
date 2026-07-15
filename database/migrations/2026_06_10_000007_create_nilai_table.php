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
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained('mapels')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            $table->foreignId('guru_id')->nullable()->constrained('gurus')->nullOnDelete();
            $table->decimal('tugas', 5, 2)->default(0.00);
            $table->decimal('uh', 5, 2)->default(0.00);
            $table->decimal('uts', 5, 2)->default(0.00);
            $table->decimal('uas', 5, 2)->default(0.00);
            $table->decimal('nilai_akhir', 5, 2)->default(0.00);
            $table->enum('status_remedial', ['Ya', 'Tidak'])->default('Tidak');
            $table->timestamps();

            $table->unique(['siswa_id', 'mapel_id', 'tahun_ajaran_id'], 'siswa_mapel_ta_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
