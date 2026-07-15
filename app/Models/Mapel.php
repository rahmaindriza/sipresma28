<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_mapel',
        'kode_mapel',
        'jenis_mapel',
        'kkm',
    ];

    /**
     * Get all grades for this subject.
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'mapel_id');
    }

    /**
     * Get all assignments involving this subject.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(GuruMapelKelas::class, 'mapel_id');
    }
}
