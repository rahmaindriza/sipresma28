<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Decouple any gurus user_id field that points to users who will become fixed accounts
        // Specifically, Wali Kelas teachers should have their user_id in the gurus table cleared
        // so that their login accounts are decoupled from individual profiles.
        $waliKelasUserIds = DB::table('users')->where('role', 'wali_kelas')->pluck('id');
        DB::table('gurus')->whereIn('user_id', $waliKelasUserIds)->update(['user_id' => null]);

        // 2. Setup the 6 fixed accounts mapped to Kelas 1-6
        for ($i = 1; $i <= 6; $i++) {
            // Verify if the class exists to prevent foreign key constraint failures (e.g. in testing)
            if (!DB::table('kelas')->where('id', $i)->exists()) {
                continue;
            }

            $username = "kelas{$i}";
            $email = "kelas{$i}@gmail.com";
            $name = "Wali Kelas {$i}";

            // Find if there is an existing user account for this kelas
            $existingUser = DB::table('users')->where('kelas_id', $i)->where('role', 'wali_kelas')->first();

            if ($existingUser) {
                // Update it to be the fixed account
                DB::table('users')->where('id', $existingUser->id)->update([
                    'username' => $username,
                    'email' => $email,
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'password_plain' => 'password',
                    'status_akun' => 'aktif',
                ]);
            } else {
                // If not exists, check if username already exists to avoid duplicate
                $userByUsername = DB::table('users')->where('username', $username)->first();
                if ($userByUsername) {
                    DB::table('users')->where('id', $userByUsername->id)->update([
                        'kelas_id' => $i,
                        'email' => $email,
                        'name' => $name,
                        'password' => Hash::make('password'),
                        'password_plain' => 'password',
                        'status_akun' => 'aktif',
                    ]);
                } else {
                    // Create new
                    DB::table('users')->insert([
                        'username' => $username,
                        'email' => $email,
                        'name' => $name,
                        'password' => Hash::make('password'),
                        'password_plain' => 'password',
                        'role' => 'wali_kelas',
                        'status_akun' => 'aktif',
                        'kelas_id' => $i,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 3. Clear kelas_id on any other wali_kelas accounts to avoid multiple accounts for the same class
        $fixedUserIds = DB::table('users')
            ->where('role', 'wali_kelas')
            ->whereIn('username', ['kelas1', 'kelas2', 'kelas3', 'kelas4', 'kelas5', 'kelas6'])
            ->pluck('id');

        DB::table('users')
            ->where('role', 'wali_kelas')
            ->whereNotIn('id', $fixedUserIds)
            ->update(['kelas_id' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op or revert if necessary
    }
};
