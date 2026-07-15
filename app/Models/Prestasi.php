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
    ];

    /**
     * Get the student who achieved this.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
