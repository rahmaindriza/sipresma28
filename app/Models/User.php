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
