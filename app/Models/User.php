<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nip_nik',
        'password',
        'role',
        'sekolah',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // ─────────────────────────────────────────────
    // RELASI LAMA (tidak berubah)
    // ─────────────────────────────────────────────

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'id_user', 'id');
    }

    public function masyarakat()
    {
        return $this->hasOne(Masyarakat::class, 'id_user', 'id');
    }

    public function reaksi()
    {
        return $this->hasMany(Reaksi::class, 'id_user', 'id');
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'id_user', 'id');
    }

    /**
     * Relasi ke data Sekolah yang dikelola user ini.
     * Dinamai dataSekolah() untuk menghindari konflik dengan
     * kolom 'sekolah' (enum: 'admin'|'siswa'|null) di tabel users.
     *
     * Gunakan: $user->dataSekolah  (bukan $user->sekolah)
     */
    public function dataSekolah()
    {
        return $this->hasOne(Sekolah::class, 'id_user', 'id');
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id_user', 'id');
    }

    // ─────────────────────────────────────────────
    // RELASI DOKUMEN BERSAMA (baru)
    // ─────────────────────────────────────────────

    /**
     * Dokumen-dokumen yang DIKIRIM oleh user ini.
     *
     * Contoh: auth()->user()->dokumenDikirim()->aktif()->latest()->get();
     */
    public function dokumenDikirim(): HasMany
    {
        return $this->hasMany(DokumenBersama::class, 'id_user', 'id');
    }

    /**
     * Record penerima (pivot) untuk dokumen yang DITERIMA user ini.
     * Berisi info izin & status baca.
     *
     * Contoh: auth()->user()->dokumenDiterima()->with('dokumen')->get();
     */
    public function dokumenDiterima(): HasMany
    {
        return $this->hasMany(DokumenPenerima::class, 'id_user', 'id');
    }

    // ─────────────────────────────────────────────
    // HELPER ROLE (tidak berubah)
    // ─────────────────────────────────────────────

    public function hasRole($roles): bool
    {
        if (is_string($roles)) {
            return strtolower(trim($this->role)) === strtolower(trim($roles));
        }

        foreach ($roles as $role) {
            if (strtolower(trim($this->role)) === strtolower(trim($role))) {
                return true;
            }
        }

        return false;
    }

    public function isSuperAdmin(): bool
    {
        if ($this->role === 'camat') return true;
        if ($this->role === 'pegawai') {
            return is_null($this->pegawai?->id_nagari);
        }
        return false;
    }

    public function getRoleLabel(): string
    {
        if ($this->role === 'camat') return 'camat';
        if ($this->role === 'pegawai') {
            $pegawai = $this->pegawai;
            if (! $pegawai || is_null($pegawai->id_nagari)) return 'staf_camat';
            return $pegawai->jabatan_nagari === 'kepala_nagari' ? 'wali_nagari' : 'pegawai_nagari';
        }
        if ($this->role === 'sekolah') return 'sekolah';
        if ($this->role === 'siswa')   return 'siswa';
        if ($this->role === 'masyarakat') {
            if ($this->sekolah === 'admin') return 'admin_sekolah';
            if ($this->sekolah === 'siswa') return 'siswa_sekolah';
            return 'masyarakat';
        }
        return 'masyarakat';
    }

    // ─────────────────────────────────────────────
    // HELPER SUB ROLE SEKOLAH
    // ─────────────────────────────────────────────

    public function isAdminSekolah(): bool
    {
        return $this->role === 'masyarakat' && $this->sekolah === 'admin';
    }

    public function isSiswaSekolah(): bool
    {
        return $this->role === 'masyarakat' && $this->sekolah === 'siswa';
    }

    public function isMasyarakatBiasa(): bool
    {
        return $this->role === 'masyarakat' && is_null($this->sekolah);
    }

    // ─────────────────────────────────────────────
    // HELPER DOKUMEN BERSAMA (baru)
    // ─────────────────────────────────────────────

    /**
     * Nama tampilan user berdasarkan role-nya.
     * Berguna saat menampilkan daftar penerima di UI.
     */
    public function namaTampil(): string
    {
        return match ($this->role) {
            'camat'      => $this->pegawai?->nama_pegawai       ?? $this->nip_nik,
            'pegawai'    => $this->pegawai?->nama_pegawai       ?? $this->nip_nik,
            'masyarakat' => $this->masyarakat?->nama_masyarakat ?? $this->nip_nik,
            'sekolah'    => $this->dataSekolah?->nama_sekolah   ?? $this->nip_nik, // legacy, role 'sekolah' sudah tidak aktif
            'siswa'      => $this->siswa?->nama_siswa           ?? $this->nip_nik,
            default      => $this->nip_nik,
        };
    }

    /**
     * Cek apakah user ini penerima dari sebuah dokumen.
     *
     * Contoh: auth()->user()->isPenerimaDokumen($dokumen->id);
     */
    public function isPenerimaDokumen(int $idDokumen): bool
    {
        return $this->dokumenDiterima()->where('id_dokumen', $idDokumen)->exists();
    }

    /**
     * Ambil record izin penerima untuk satu dokumen tertentu.
     * Mengembalikan null jika user bukan penerima dokumen itu.
     */
    public function izinDokumen(int $idDokumen): ?DokumenPenerima
    {
        return $this->dokumenDiterima()->where('id_dokumen', $idDokumen)->first();
    }
}
