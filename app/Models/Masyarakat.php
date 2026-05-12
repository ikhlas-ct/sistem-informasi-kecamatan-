<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masyarakat extends Model
{
    use HasFactory;

    protected $table = 'masyarakat';
    protected $primaryKey = 'id_masyarakat';

    protected $fillable = [
        'nik',
        'kk',
        'jenis_kelamin',
        'no_hp',
        'nama_masyarakat',
        'nama_ibu',
        'alamat',
        'scan_ktp',
        'foto_diri_ktp',
        'scan_kk',
        'akta_kelahiran',
        'foto_diri_akta',
        'foto_profil',
        'id_user',
        'instagram',
        'twitter',
        'facebook',
        'deskripsi',
        'pekerjaan',
        'id_nagari',
    ];

    // Relasi ke User (BelongsTo)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Relasi ke PelayananAdministrasi (HasMany)
    public function pelayananadministrasi()
    {
        return $this->hasMany(Suratketeranganmiskin::class, 'id_masyarakat', 'id_masyarakat');
    }

    // Relasi ke Pengaduan (HasMany)
    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'id_masyarakat', 'id_masyarakat');
    }

    public function konten()
    {
        return $this->hasMany(Konten::class, 'id_user', 'id_user');
    }
    public function nagari()
    {
        return $this->belongsTo(Nagari::class, 'id_nagari', 'id');
    }
}
