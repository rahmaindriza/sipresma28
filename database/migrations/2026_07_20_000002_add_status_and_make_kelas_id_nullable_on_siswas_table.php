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
        Schema::disableForeignKeyConstraints();
        Schema::table('siswas', function (Blueprint $table) {
            $table->unsignedBigInteger('kelas_id')->nullable()->change();
            $table->enum('status', ['Aktif', 'Lulus', 'Keluar'])->default('Aktif')->after('kelas_id');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('siswas', function (Blueprint $table) {
            $table->unsignedBigInteger('kelas_id')->nullable(false)->change();
            $table->dropColumn('status');
        });
        Schema::enableForeignKeyConstraints();
    }
};
