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
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->integer('nilai_tugas')->nullable();
            $table->integer('nilai_uh')->nullable();
            $table->integer('nilai_uts')->nullable();
            $table->integer('nilai_uas')->nullable();
            $table->decimal('nilai_akhir', 5, 2)->default(0.00);
            $table->enum('status_kkm', ['Lulus', 'Remedial'])->default('Remedial');
            $table->timestamps();

            // Unique key to prevent duplicates
            $table->unique(['siswa_id', 'mapel_id', 'kelas_id'], 'siswa_mapel_kelas_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
