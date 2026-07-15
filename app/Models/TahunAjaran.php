<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajarans';

    protected $fillable = [
        'tahun',
        'semester',
        'status',
    ];

    /**
     * Get the currently active academic year.
     */
    public static function active()
    {
        return self::where('status', 'aktif')->first();
    }

    /**
     * Get all grades for this academic year.
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'tahun_ajaran_id');
    }

    /**
     * Get teaching assignments for this academic year.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(GuruMapelKelas::class, 'tahun_ajaran_id');
    }
}
