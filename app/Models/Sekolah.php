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

    // ── Relasi ke User (akun login sekolah) ──────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // ── Relasi ke Nagari ─────────────────────────────────────────────────

    public function nagari()
    {
        return $this->belongsTo(Nagari::class, 'id_nagari', 'id');
    }

    // ── Relasi ke Siswa ──────────────────────────────────────────────────

    /**
     * Semua siswa di sekolah ini.
     * Eager-load user.masyarakat agar accessor seperti
     * $siswa->nama_siswa, $siswa->nik, $siswa->foto_profil_url tersedia
     * tanpa query N+1.
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_sekolah', 'id_sekolah')
            ->with('user.masyarakat');
    }

    /**
     * Siswa aktif → user.status = 'aktif'.
     *
     * PERBAIKAN: kolom 'status_verifikasi' tidak ada di tabel siswa.
     * Status aktif/nonaktif siswa diambil dari kolom users.status.
     */
    public function siswaAktif()
    {
        return $this->hasMany(Siswa::class, 'id_sekolah', 'id_sekolah')
            ->whereHas('user', fn($q) => $q->where('status', 'aktif'));
    }

    /**
     * Siswa nonaktif/pending → user.status = 'nonaktif'.
     *
     * PERBAIKAN: sama dengan siswaAktif, diganti ke users.status.
     */
    public function siswaPending()
    {
        return $this->hasMany(Siswa::class, 'id_sekolah', 'id_sekolah')
            ->whereHas('user', fn($q) => $q->where('status', 'nonaktif'));
    }

    // ── Relasi ke Mading ─────────────────────────────────────────────────

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
