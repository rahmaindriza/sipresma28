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
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->enum('jenis_kegiatan', ['akademik', 'non-akademik']);
            $table->enum('kategori', ['ekstrakurikuler', 'organisasi', 'perlombaan', 'resmi', 'lainnya']);
            $table->text('deskripsi');
            $table->string('gambar')->nullable();
            $table->date('tanggal_kegiatan');
            $table->string('semester_aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
