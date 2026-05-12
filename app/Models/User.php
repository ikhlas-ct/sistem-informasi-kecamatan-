<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *
     */
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nip_nik',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
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
    public function hasRole($roles)
    {
        // Jika hanya satu role yang diberikan sebagai string
        if (is_string($roles)) {
            return strtolower(trim($this->role)) === strtolower(trim($roles));
        }

        // Jika array of roles
        foreach ($roles as $role) {
            if (strtolower(trim($this->role)) === strtolower(trim($role))) {
                return true;
            }
        }

        return false;
    }


    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'id_user', 'id');
    }

    public function masyarakat()
    {
        return $this->hasOne(Masyarakat::class, 'id_user', 'id');
    }

    public function reaksi()
    {
        return $this->hasMany(Reaksi::class, 'id_user', 'id');
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'id_user', 'id');
    }

    public function isSuperAdmin(): bool
    {
        if ($this->role === 'camat') return true;
        if ($this->role === 'pegawai') {
            return is_null($this->pegawai?->id_nagari);
        }
        return false;
    }

    public function getRoleLabel(): string
    {
        if ($this->role === 'camat') return 'camat';
        if ($this->role === 'pegawai') {
            $pegawai = $this->pegawai;
            if (! $pegawai || is_null($pegawai->id_nagari)) return 'staf_camat';
            return $pegawai->jabatan_nagari === 'kepala_nagari' ? 'wali_nagari' : 'staf_nagari';
        }
        return 'masyarakat';
    }
}

