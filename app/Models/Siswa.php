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

    // ── Helper ───────────────────────────────────────────

    /**
     * Siswa dianggap sudah diverifikasi/approved jika user-nya berstatus 'aktif'.
     * Status ini diubah oleh admin sekolah saat memverifikasi siswa.
     *
     * Pengecekan juga memastikan siswa sudah terdaftar di sekolah (id_sekolah tidak null).
     */
    public function isApproved(): bool
    {
        return !is_null($this->id_sekolah)
            && $this->user?->status === 'aktif';
    }

    /**
     * Pastikan siswa ini memang terdaftar di sekolah tertentu.
     * Berguna untuk memverifikasi bahwa siswa dan admin sekolah berada di sekolah yang sama.
     */
    public function belongsToSekolah(int $id_sekolah): bool
    {
        return (int) $this->id_sekolah === $id_sekolah;
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
