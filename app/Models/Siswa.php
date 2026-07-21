<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nisn',
        'nama',
        'kelas_id',
        'status',
        'jk',
        'tempat_lahir',
        'tanggal_lahir',
        'nik',
        'agama',
        'alamat',
    ];

    /**
     * Get the class this student belongs to.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Get all grades for this student.
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'siswa_id');
    }

    /**
     * Get all achievements for this student.
     */
    public function prestasis(): HasMany
    {
        return $this->hasMany(Prestasi::class, 'siswa_id');
    }
}
