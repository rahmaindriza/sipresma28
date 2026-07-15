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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jk', ['Laki-laki', 'Perempuan']);
            $table->string('nisn', 10)->unique();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('nik', 16)->unique();
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu']);
            $table->text('alamat');
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
