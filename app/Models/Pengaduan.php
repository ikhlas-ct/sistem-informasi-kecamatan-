<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';
    protected $primaryKey = 'id_pengaduan';

    protected $fillable = [
        'id_masyarakat',
        'judul_pengaduan',
        'hal_pengaduan',
        'deskripsi',
        'alamat',
        'latitude',
        'longitude',
        'tanggal_pengaduan',
        'status',
    ];

    public function masyarakat()
    {
        return $this->belongsTo(Masyarakat::class, 'id_masyarakat', 'id_masyarakat');
    }

    public function balasanpengaduan()
    {
        return $this->hasOne(Balasanpengaduan::class, 'id_pengaduan', 'id_pengaduan');
    }

    public function lampiran_pengaduan()
    {
        return $this->hasMany(Lampiran_pengaduan::class, 'id_pengaduan', 'id_pengaduan');
    }
}
