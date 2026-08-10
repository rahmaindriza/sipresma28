<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'password_plain',
        'role',
        'status_akun',
        'kelas_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the Guru profile linked to this user.
     */
    public function guru()
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    /**
     * Helper to resolve the Guru profile linked to this User with fallbacks.
     */
    public function getGuruProfileAttribute()
    {
        if ($this->role === 'wali_kelas') {
            return $this->kelas?->guru;
        }
        $guru = $this->guru;
        if (!$guru) {
            $guru = Guru::where('nip', $this->username)->first();
        }
        if (!$guru) {
            $guru = Guru::where('nama', $this->name)->first();
        }
        return $guru;
    }

    /**
     * Accessor for Kelas dynamic relation.
     */
    public function getKelasAttribute()
    {
        if ($this->role === 'wali_kelas') {
            return $this->kelas_id ? Kelas::find($this->kelas_id) : null;
        }
        $guru = $this->guru_profile;
        if ($guru) {
            return Kelas::where('wali_kelas_id', $guru->id)->first();
        }
        return null;
    }

    /**
     * Accessor for Kelas ID.
     */
    public function getKelasIdAttribute()
    {
        if ($this->role === 'wali_kelas') {
            return $this->attributes['kelas_id'] ?? null;
        }
        return $this->kelas?->id;
    }

    /**
     * Accessor for dynamic display name (nama_tampil).
     */
    public function getNamaTampilAttribute()
    {
        if (in_array(strtolower($this->role), ['wali_kelas', 'wali kelas']) && $this->kelas) {
            return $this->kelas->guru->nama_lengkap ?? 'Wali Kelas ' . $this->kelas->nama_kelas;
        }
        return $this->guru_profile->nama_lengkap ?? $this->name;
    }

    /**
     * Accessor for display_name (alias for nama_tampil).
     */
    public function getDisplayNameAttribute()
    {
        return $this->nama_tampil;
    }

    /**
     * Check if the user is Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is Guru Mata Pelajaran.
     */
    public function isGuruMapel(): bool
    {
        return $this->role === 'guru_mapel';
    }

    /**
     * Check if the user is Wali Kelas.
     */
    public function isWaliKelas(): bool
    {
        return $this->role === 'wali_kelas';
    }

    /**
     * Check if the user is Kepala Sekolah.
     */
    public function isKepalaSekolah(): bool
    {
        return $this->role === 'kepala_sekolah';
    }
}
