<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balasanpengaduan extends Model
{
    use HasFactory;

    protected $table = 'balasanpengaduan';
    protected $primaryKey = 'id_balasanpengaduan';

    protected $fillable = [
        'id_pegawai',
        'id_pengaduan',
        'balasan',
        'tanggal_balasan',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'id_pengaduan', 'id_pengaduan');
    }

    public function lampiran_balasan()
    {
        return $this->hasMany(Lampiran_balasan::class, 'id_balasanpengaduan', 'id_balasanpengaduan');
    }
}
