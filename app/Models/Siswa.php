<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table      = 'siswa';
    protected $primaryKey = 'id_siswa';

    // Kolom yang benar-benar ada di tabel siswa
    protected $fillable = [
        'id_user',
        'id_sekolah',
        'nis',
        'kelas',
    ];

    // ── Relasi ───────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah', 'id_sekolah');
    }

    public function mading()
    {
        return $this->hasMany(Mading::class, 'id_user', 'id_user');
    }

    // ── Accessor: ambil data dari masyarakat terkait ─────

    /**
     * Nama siswa → dari masyarakat yang terhubung ke user-nya.
     * Pemakaian: $siswa->nama_siswa
     */
    public function getNamaSiswaAttribute(): string
    {
        return $this->user?->masyarakat?->nama_masyarakat ?? $this->user?->nip_nik ?? '-';
    }

    /**
     * NIK siswa → dari nip_nik user.
     */
    public function getNikAttribute(): string
    {
        return $this->user?->nip_nik ?? '-';
    }

    /**
     * Foto profil URL → dari masyarakat, fallback ke default.
     */
    public function getFotoProfilUrlAttribute(): string
    {
        $foto = $this->user?->masyarakat?->foto_profil;
        return $foto
            ? \Illuminate\Support\Facades\Storage::url($foto)
            : asset('default-image/default-user.png');
    }

    /**
     * Data masyarakat siswa (shortcut).
     */
    public function getMasyarakatAttribute()
    {
        return $this->user?->masyarakat;
    }

    // ── Appends ──────────────────────────────────────────

    protected $appends = ['nama_siswa', 'foto_profil_url'];
}
