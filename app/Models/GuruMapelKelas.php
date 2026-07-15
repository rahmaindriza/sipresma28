<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuruMapelKelas extends Model
{
    use HasFactory;

    protected $table = 'guru_mapel_kelas';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mapel_id',
        'tahun_ajaran_id',
    ];

    /**
     * Get the Guru who is teaching.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /**
     * Get the Kelas being taught.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Get the Mapel being taught.
     */
    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    /**
     * Get the Tahun Ajaran of the assignment.
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
