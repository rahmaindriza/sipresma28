<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilais';

    protected $fillable = [
        'siswa_id',
        'mapel_id',
        'kelas_id',
        'nilai_tugas',
        'nilai_uh',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir',
        'status_kkm',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically calculate final score and remedial status on save
        static::saving(function ($nilai) {
            $nilai->nilai_akhir = (($nilai->nilai_tugas ?? 0.0) * 0.20) + 
                                  (($nilai->nilai_uh ?? 0.0) * 0.20) + 
                                  (($nilai->nilai_uts ?? 0.0) * 0.30) + 
                                  (($nilai->nilai_uas ?? 0.0) * 0.30);
            
            // KKM status threshold: >= 75 is 'Lulus', otherwise 'Remedial'
            $nilai->status_kkm = ($nilai->nilai_akhir >= 75) ? 'Lulus' : 'Remedial';
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
     * Get the class of this score.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
