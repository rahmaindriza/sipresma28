<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelas',
        'wali_kelas_id',
    ];

    /**
     * Get the Wali Kelas (Guru) of this class.
     */
    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    /**
     * Get the students in this class.
     */
    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    /**
     * Get teaching assignments for this class.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(GuruMapelKelas::class, 'kelas_id');
    }
}
