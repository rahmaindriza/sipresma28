<?php

namespace App\Models;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasis';

    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'kelas_id',
        'jenis_prestasi',
        'keterangan',
        'tanggal',
        'nama_lomba',
        'kategori',
        'jenis_pelaksanaan',
        'tingkat',
        'juara',
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
     * Get the academic year when this achievement was recorded.
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * Get the class when this achievement was recorded.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
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

            // Ensure required foreign keys are populated for legacy or partial creates.
            if (!$model->tahun_ajaran_id) {
                $activeTa = TahunAjaran::active();
                if ($activeTa) {
                    $model->tahun_ajaran_id = $activeTa->id;
                }
            }
            if (!$model->kelas_id && $model->siswa_id) {
                $siswa = Siswa::find($model->siswa_id);
                if ($siswa) {
                    $model->kelas_id = $siswa->kelas_id;
                }
            }
        });
    }

    /**
     * Get the dynamic point value based on tingkat and juara.
     */
    public function getPoinAttribute(): int
    {
        if ($this->juara === 'Harapan') {
            return 2;
        }

        switch ($this->tingkat) {
            case 'Kecamatan':
                if ($this->juara === 'Juara 1') return 15;
                if ($this->juara === 'Juara 2') return 10;
                if ($this->juara === 'Juara 3') return 5;
                break;
            case 'Kabupaten':
                if ($this->juara === 'Juara 1') return 30;
                if ($this->juara === 'Juara 2') return 25;
                if ($this->juara === 'Juara 3') return 20;
                break;
            case 'Provinsi':
                if ($this->juara === 'Juara 1') return 60;
                if ($this->juara === 'Juara 2') return 50;
                if ($this->juara === 'Juara 3') return 40;
                break;
            case 'Nasional':
                if ($this->juara === 'Juara 1') return 100;
                if ($this->juara === 'Juara 2') return 90;
                if ($this->juara === 'Juara 3') return 80;
                break;
        }
        return 2; // default fallback
    }
}
