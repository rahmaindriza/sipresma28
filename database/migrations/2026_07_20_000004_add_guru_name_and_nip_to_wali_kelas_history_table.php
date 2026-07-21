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
        Schema::table('wali_kelas_history', function (Blueprint $table) {
            $table->string('guru_name')->nullable()->after('guru_id');
            $table->string('guru_nip')->nullable()->after('guru_name');
        });

        // Populate existing records
        $histories = DB::table('wali_kelas_history')->whereNotNull('guru_id')->get();
        foreach ($histories as $hist) {
            $guru = DB::table('gurus')->where('id', $hist->guru_id)->first();
            if ($guru) {
                DB::table('wali_kelas_history')
                    ->where('id', $hist->id)
                    ->update([
                        'guru_name' => $guru->nama,
                        'guru_nip' => $guru->nip,
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wali_kelas_history', function (Blueprint $table) {
            $table->dropColumn(['guru_name', 'guru_nip']);
        });
    }
};
