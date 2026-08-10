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
        // 1. Add 'ekstrakurikuler' text column to 'rapor_siswas' table
        Schema::table('rapor_siswas', function (Blueprint $table) {
            $table->text('ekstrakurikuler')->nullable()->after('catatan_walas');
        });

        // 2. Migrate existing data from old columns to the new column
        $records = DB::table('rapor_siswas')->get();
        foreach ($records as $record) {
            $ekskuls = [];
            if (!empty($record->ekskul_1_nama)) {
                $ekskuls[] = [
                    'nama' => $record->ekskul_1_nama,
                    'ket' => $record->ekskul_1_ket ?? '',
                ];
            }
            if (!empty($record->ekskul_2_nama)) {
                $ekskuls[] = [
                    'nama' => $record->ekskul_2_nama,
                    'ket' => $record->ekskul_2_ket ?? '',
                ];
            }

            if (!empty($ekskuls)) {
                DB::table('rapor_siswas')->where('id', $record->id)->update([
                    'ekstrakurikuler' => json_encode($ekskuls),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapor_siswas', function (Blueprint $table) {
            $table->dropColumn('ekstrakurikuler');
        });
    }
};
