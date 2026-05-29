<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $table = 'sekolah';
    protected $primaryKey = 'id_sekolah';

    protected $fillable = [
        'id_user',
        'id_nagari',
        'nama_sekolah',
        'npsn',
        'jenjang',
        'alamat',
        'no_hp',
        'email',
        'logo',
        'status',
    ];

    // Relasi ke User (akun login sekolah)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Relasi ke Nagari
    public function nagari()
    {
        return $this->belongsTo(Nagari::class, 'id_nagari', 'id');
    }

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_sekolah', 'id_sekolah');
    }

    // Relasi ke Siswa yang sudah approved saja
    public function siswaAktif()
    {
        return $this->hasMany(Siswa::class, 'id_sekolah', 'id_sekolah')
            ->where('status_verifikasi', 'approved');
    }

    // Relasi ke Siswa pending (menunggu approval)
    public function siswaPending()
    {
        return $this->hasMany(Siswa::class, 'id_sekolah', 'id_sekolah')
            ->where('status_verifikasi', 'pending');
    }

    // Relasi ke Mading milik sekolah ini
    public function mading()
    {
        return $this->hasMany(Mading::class, 'id_sekolah', 'id_sekolah');
    }

    // Mading yang sudah publish dan approved
    public function madingPublik()
    {
        return $this->hasMany(Mading::class, 'id_sekolah', 'id_sekolah')
            ->where('status', 'publish')
            ->where('approval_status', 'approved');
    }
}
