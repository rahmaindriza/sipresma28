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
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            
            // Old columns for compatibility with Wali Kelas controller/views
            $table->string('jenis_prestasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tanggal')->nullable();

            // New columns for Admin and advanced prestasi features
            $table->string('nama_lomba')->nullable();
            $table->enum('kategori', ['Akademik', 'Non-Akademik'])->nullable();
            $table->enum('jenis_pelaksanaan', ['Dalam Sekolah', 'Luar Sekolah'])->nullable();
            $table->enum('tingkat', ['Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional'])->nullable();
            $table->enum('juara', ['Juara 1', 'Juara 2', 'Juara 3', 'Harapan'])->nullable();
            $table->integer('poin')->nullable();
            $table->string('sertifikat')->nullable();
            $table->date('tanggal_penghargaan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
