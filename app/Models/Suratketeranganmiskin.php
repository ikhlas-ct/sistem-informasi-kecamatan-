<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suratketeranganmiskin extends Model
{

    use HasFactory;
    protected $table = 'suratketeranganmiskin';
    protected $primaryKey = 'id_pelayanan';

    protected $fillable = [
        'id_masyarakat',
        'id_pegawai',
        'tanggal_pengajuan',
        'alasan_pembuatan',
        'surat_pengantar_rt_rw',
        'tanggal_pengajuan',
        'surat_pernyataan_pribadi',
        'validasi_pegawai',
        'validasi_nagari',
        'alasan_penolakan',
        'tanggal_selesai',
        'nomor_surat',
        'status',
        'arsip',
        'pendapatan',
        'validasi_pengantar',
        'validasi_pernyataan',
        'bantuan_untuk',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'pekerjaan',
        'alamat',
    ];

    // Relasi ke Masyarakat (BelongsTo)
    public function masyarakat()
    {
        return $this->belongsTo(Masyarakat::class, 'id_masyarakat', 'id_masyarakat');
    }

    // Relasi ke Pegawai (BelongsTo)
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function anggota()
    {
        return $this->hasMany(Surat_Anggota::class, 'id_pelayanan', 'id_pelayanan');
    }

}
