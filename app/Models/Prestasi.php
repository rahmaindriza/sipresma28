<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasis';

    protected $fillable = [
        'siswa_id',
        'jenis_prestasi',
        'keterangan',
        'tanggal',
        'nama_lomba',
        'kategori',
        'jenis_pelaksanaan',
        'tingkat',
        'juara',
        'poin',
        'sertifikat',
        'tanggal_penghargaan',
    ];

    /**
     * Get the student who achieved this.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Model boot function to handle auto-synchronization and backward compatibility.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // 1. Sync from old columns to new columns if old ones are populated
            if ($model->jenis_prestasi && !$model->kategori) {
                $model->kategori = $model->jenis_prestasi;
            }
            if ($model->keterangan && !$model->nama_lomba) {
                $model->nama_lomba = $model->keterangan;
            }
            if ($model->tanggal && !$model->tanggal_penghargaan) {
                $model->tanggal_penghargaan = $model->tanggal;
            }

            // 2. Sync from new columns to old columns if new ones are populated
            if ($model->kategori && !$model->jenis_prestasi) {
                $model->jenis_prestasi = $model->kategori;
            }
            if ($model->nama_lomba && !$model->keterangan) {
                $model->keterangan = $model->nama_lomba;
            }
            if ($model->tanggal_penghargaan && !$model->tanggal) {
                $model->tanggal = $model->tanggal_penghargaan;
            }

            // 3. Fallbacks for missing columns in new schema
            if (!$model->jenis_pelaksanaan) {
                $model->jenis_pelaksanaan = 'Luar Sekolah';
            }
            if (!$model->tingkat) {
                $model->tingkat = 'Kecamatan';
            }
            if (!$model->juara) {
                $model->juara = 'Harapan';
            }
            if (!$model->poin) {
                $model->poin = 2;
            }
        });
    }
}
