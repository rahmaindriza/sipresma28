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
        // Link gurus to users by NIP matching username or name matching name
        $gurusList = DB::table('gurus')->whereNull('user_id')->get();
        foreach ($gurusList as $g) {
            $user = DB::table('users')->where('username', $g->nip)->first();
            if (!$user) {
                $user = DB::table('users')->where('name', $g->nama)->first();
            }

            if ($user) {
                DB::table('gurus')->where('id', $g->id)->update(['user_id' => $user->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Down not required as it only populates missing relationships
    }
};
