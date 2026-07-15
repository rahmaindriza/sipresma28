<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'siswa_id',
        'mapel_id',
        'tahun_ajaran_id',
        'guru_id',
        'tugas',
        'uh',
        'uts',
        'uas',
        'nilai_akhir',
        'status_remedial',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically calculate final score and remedial status on save
        static::saving(function ($nilai) {
            $nilai->nilai_akhir = ($nilai->tugas * 0.2) + 
                                  ($nilai->uh * 0.3) + 
                                  ($nilai->uts * 0.2) + 
                                  ($nilai->uas * 0.3);
            
            // Logika KKM standard is 75 (if < 75, remedial is 'Ya', otherwise 'Tidak')
            $nilai->status_remedial = ($nilai->nilai_akhir < 75) ? 'Ya' : 'Tidak';
        });
    }

    /**
     * Get the student who owns this score.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Get the subject of this score.
     */
    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    /**
     * Get the academic year of this score.
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * Get the teacher who graded this score.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
