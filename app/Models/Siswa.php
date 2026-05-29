<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'id_user',
        'id_sekolah',
        'nama_siswa',
        'nis',
        'kelas',
        'foto_profil',
        'status_verifikasi',
        'alasan_penolakan',
    ];

    // Relasi ke User (akun login siswa)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Relasi ke Sekolah
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah', 'id_sekolah');
    }

    // Relasi ke Mading yang dibuat siswa ini
    public function mading()
    {
        return $this->hasMany(Mading::class, 'id_user', 'id_user');
    }

    // Helper: cek apakah siswa sudah diverifikasi
    public function isApproved(): bool
    {
        return $this->status_verifikasi === 'approved';
    }

    // Helper: cek apakah siswa masih pending
    public function isPending(): bool
    {
        return $this->status_verifikasi === 'pending';
    }

    // Accessor foto profil
    protected $appends = ['foto_profil_url'];

    public function getFotoProfilUrlAttribute(): string
    {
        if ($this->foto_profil) {
            return Storage::url($this->foto_profil);
        }

        return asset('default-image/default-user.png');
    }
}
