<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'foto',
        'jabatan',
        'jk',
    ];

    /**
     * Get the user account for the guru.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the classes where this guru is wali kelas.
     */
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }

    /**
     * Get the teaching assignments for the guru.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(GuruMapelKelas::class, 'guru_id');
    }

    /**
     * Get the grades entered by this guru.
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'guru_id');
    }
}
