<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenBersama extends Model
{
    protected $table = 'dokumen_bersama';

    protected $fillable = [
        'id_user',
        'judul',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ─────────────────────────────────────────────
    // RELASI
    // ─────────────────────────────────────────────

    /** Pengirim dokumen (user yang membuat) */
    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    /** Daftar penerima beserta izin & status bacanya */
    public function penerimas(): HasMany
    {
        return $this->hasMany(DokumenPenerima::class, 'id_dokumen', 'id');
    }

    /** Lampiran file / foto */
    public function lampiran(): HasMany
    {
        return $this->hasMany(DokumenLampiran::class, 'id_dokumen', 'id');
    }

    /** Link-link eksternal (Drive, dsb.) */
    public function links(): HasMany
    {
        return $this->hasMany(DokumenLink::class, 'id_dokumen', 'id');
    }

    // ─────────────────────────────────────────────
    // SCOPE
    // ─────────────────────────────────────────────

    /** Hanya dokumen yang masih aktif */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    /** Hanya dokumen yang sudah diarsipkan */
    public function scopeArsip(Builder $query): Builder
    {
        return $query->where('status', 'arsip');
    }

    /**
     * Dokumen yang dikirim oleh user tertentu.
     *
     * Contoh: DokumenBersama::dikirimOleh(auth()->id())->get();
     */
    public function scopeDikirimOleh(Builder $query, int $userId): Builder
    {
        return $query->where('id_user', $userId);
    }

    /**
     * Dokumen yang diterima oleh user tertentu
     * (lewat tabel dokumen_penerima).
     *
     * Contoh: DokumenBersama::diterimaOleh(auth()->id())->get();
     */
    public function scopeDiterimaOleh(Builder $query, int $userId): Builder
    {
        return $query->whereHas('penerimas', fn ($q) => $q->where('id_user', $userId));
    }

    // ─────────────────────────────────────────────
    // HELPER
    // ─────────────────────────────────────────────

    /** Cek apakah $userId termasuk penerima dokumen ini */
    public function isPenerima(int $userId): bool
    {
        return $this->penerimas()->where('id_user', $userId)->exists();
    }

    /** Ambil record penerima untuk user tertentu (null jika bukan penerima) */
    public function getPenerima(int $userId): ?DokumenPenerima
    {
        return $this->penerimas()->where('id_user', $userId)->first();
    }

    /**
     * Tambah banyak penerima sekaligus.
     *
     * @param  array<int, array{id_user: int, izin_download?: bool, izin_lihat?: bool}>  $penerimas
     */
    public function tambahPenerimas(array $penerimas): void
    {
        foreach ($penerimas as $data) {
            $this->penerimas()->updateOrCreate(
                ['id_user' => $data['id_user']],
                [
                    'izin_download' => $data['izin_download'] ?? true,
                    'izin_lihat'    => $data['izin_lihat']    ?? true,
                    'sudah_dibaca'  => false,
                    'dibaca_at'     => null,
                ]
            );
        }
    }

    /** Jumlah penerima yang sudah membaca */
    public function jumlahSudahDibaca(): int
    {
        return $this->penerimas()->where('sudah_dibaca', true)->count();
    }

    /** Jumlah total penerima */
    public function jumlahPenerima(): int
    {
        return $this->penerimas()->count();
    }

    /** Hanya lampiran bertipe file */
    public function lampiranFile(): HasMany
    {
        return $this->hasMany(DokumenLampiran::class, 'id_dokumen', 'id')
                    ->where('tipe', 'file');
    }

    /** Hanya lampiran bertipe foto */
    public function lampiranFoto(): HasMany
    {
        return $this->hasMany(DokumenLampiran::class, 'id_dokumen', 'id')
                    ->where('tipe', 'foto');
    }
}
