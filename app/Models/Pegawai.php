<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    protected $fillable = [
        'nama_pegawai',
        'alamat_pegawai',
        'nohp_pegawai',
        'email_pegawai',
        'deskripsi',
        'nik',
        'nip',
        'instagram',
        'twitter',
        'facebook',
        'jenis_kelamin',
        'pangkat_golongan',
        'jabatan',
        'foto_profil',
        'role',
        'id_user',
        'id_nagari',
        'jabatan_nagari',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function konten()
    {
        return $this->hasMany(Konten::class, 'id_user', 'id_user');
    }

    public function pelayananadministrasi()
    {
        return $this->hasMany(Suratketeranganmiskin::class, 'id_pegawai', 'id_pegawai');
    }

    public function kecamatansetting()
    {
        return $this->hasOne(Kecamatansetting::class, 'id_pegawai','id_pegawai')->where('role', 'camat');
    }

    // Relasi ke Balasanpengaduan (HasMany)
    public function balasanpengaduan()
    {
        return $this->hasMany(Balasanpengaduan::class, 'id_pegawai', 'id_pegawai');
    }

    public function nagari()
{
    return $this->belongsTo(Nagari::class, 'id_nagari', 'id');
}

public function layananSop()
{
    return $this->hasMany(Layanan_sop::class, 'id_pegawai', 'id_pegawai');
}

public function detailArsip()
{
    return $this->hasMany(Detail_arsip::class, 'uploaded_by', 'id_pegawai');
}


}
