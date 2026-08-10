<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaporSiswa extends Model
{
    use HasFactory;

    protected $table = 'rapor_siswas';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'tahun_ajaran_id',
        'sakit',
        'izin',
        'alpha',
        'catatan_walas',
        'keterangan_kelulusan',
        'ekskul_1_nama',
        'ekskul_1_ket',
        'ekskul_2_nama',
        'ekskul_2_ket',
        'ekstrakurikuler',
        'nama_wali_kelas',
        'nip_wali_kelas',
    ];

    protected $casts = [
        'ekstrakurikuler' => 'array',
    ];

    /**
     * Get the student who owns this report.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Get the class of this report.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Get the academic year of this report.
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
