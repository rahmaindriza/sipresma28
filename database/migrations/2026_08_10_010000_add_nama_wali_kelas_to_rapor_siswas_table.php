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
        Schema::table('rapor_siswas', function (Blueprint $table) {
            $table->string('nama_wali_kelas')->nullable()->after('ekstrakurikuler');
            $table->string('nip_wali_kelas')->nullable()->after('nama_wali_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapor_siswas', function (Blueprint $table) {
            $table->dropColumn(['nama_wali_kelas', 'nip_wali_kelas']);
        });
    }
};
