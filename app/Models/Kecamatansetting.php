<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatansetting extends Model
{
    use HasFactory;

    protected $table      = 'kecamatansetting';
    protected $primaryKey = 'id_kecamatan';

    public $incrementing = true;
    protected $keyType   = 'int';

    protected $fillable = [
        // Identitas
        'nama_kecamatan',
        'kode_kecamatan',
        'kode_pos_kecamatan',
        'nama_kabupaten',
        'kode_kabupaten',
        'provinsi',
        'kode_provinsi',
        'logo',
        // Relasi pegawai
        'id_pegawai',
        // Kontak
        'alamat_kecamatan',
        'email_kecamatan',
        'nomor_telepon_kecamatan',
        // Media Sosial
        'social_facebook',
        'social_instagram',
        'social_twitter',
        // Pengantar Camat
        'title_pengantar',
        'paragraf_pengantar',
        'gambar_pengantar',
        // Profil teks
        'visi_misi',
        'sejarah',
        'geografis',
        // Organisasi
        'tugas_pokok',
        'fungsi',          // ← ditambahkan (ada di migrasi, sebelumnya tidak di fillable)
        'uraian_tugas',
        'gambar_struktur',
        // Lainnya
        'lintang_peta',
    ];

    // ── Relasi ke camat ──────────────────────────────────────
    public function camat()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai')
            ->where('role', 'camat');
    }
}
