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
        // 1. Add nis to siswas table
        Schema::table('siswas', function (Blueprint $table) {
            if (!Schema::hasColumn('siswas', 'nis')) {
                $table->string('nis', 50)->nullable()->unique()->after('nama');
            }
        });

        // 2. Add capaian_perlu_peningkatan to nilais table and drop old capaian_terendah
        Schema::table('nilais', function (Blueprint $table) {
            if (Schema::hasColumn('nilais', 'capaian_terendah')) {
                $table->dropColumn('capaian_terendah');
            }
            if (!Schema::hasColumn('nilais', 'capaian_perlu_peningkatan')) {
                $table->text('capaian_perlu_peningkatan')->nullable();
            }
        });

        // 3. Add keterangan_kelulusan to rapor_siswas table
        Schema::table('rapor_siswas', function (Blueprint $table) {
            if (!Schema::hasColumn('rapor_siswas', 'keterangan_kelulusan')) {
                $table->string('keterangan_kelulusan')->nullable()->after('catatan_walas');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapor_siswas', function (Blueprint $table) {
            if (Schema::hasColumn('rapor_siswas', 'keterangan_kelulusan')) {
                $table->dropColumn('keterangan_kelulusan');
            }
        });

        Schema::table('nilais', function (Blueprint $table) {
            if (Schema::hasColumn('nilais', 'capaian_perlu_peningkatan')) {
                $table->dropColumn('capaian_perlu_peningkatan');
            }
            if (!Schema::hasColumn('nilais', 'capaian_terendah')) {
                $table->text('capaian_terendah')->nullable();
            }
        });

        Schema::table('siswas', function (Blueprint $table) {
            if (Schema::hasColumn('siswas', 'nis')) {
                $table->dropColumn('nis');
            }
        });
    }
};
