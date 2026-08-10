<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetDataNilai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-data-nilai {--force : Memaksa jalannya reset tanpa konfirmasi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset seluruh data nilai dan data rapor di database secara aman tanpa menghapus data master.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses pengosongan (reset) data nilai dan data rapor...');

        if (!$this->option('force') && !$this->confirm('Apakah Anda yakin ingin mengosongkan seluruh data nilai dan data rapor? Aksi ini tidak dapat dibatalkan!')) {
            $this->info('Proses dibatalkan.');
            return Command::SUCCESS;
        }

        try {
            // Disable foreign key checks depending on database driver
            $driver = DB::connection()->getDriverName();

            if ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = OFF;');
            } else {
                DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
            }

            // 1. Truncate / Delete table 'nilais'
            if (Schema::hasTable('nilais')) {
                DB::table('nilais')->truncate();
                $this->line('Tabel <info>nilais</info> berhasil dikosongkan.');
            }

            // 2. Truncate / Delete table 'nilai' (old/alternative table)
            if (Schema::hasTable('nilai')) {
                DB::table('nilai')->truncate();
                $this->line('Tabel <info>nilai</info> berhasil dikosongkan.');
            }

            // 3. Truncate / Delete table 'rapor_siswas'
            if (Schema::hasTable('rapor_siswas')) {
                DB::table('rapor_siswas')->truncate();
                $this->line('Tabel <info>rapor_siswas</info> berhasil dikosongkan.');
            }

            // Re-enable foreign key checks
            if ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON;');
            } else {
                DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
            }

            $this->info('Reset data nilai dan data rapor selesai dilakukan secara sukses!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan saat mengosongkan data: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
