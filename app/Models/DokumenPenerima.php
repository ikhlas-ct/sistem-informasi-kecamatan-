<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class DokumenPenerima extends Model
{
    protected $table = 'dokumen_penerima';

    protected $fillable = [
        'id_dokumen',
        'id_user',
        'izin_download',
        'izin_lihat',
        'sudah_dibaca',
        'dibaca_at',
    ];

    protected $casts = [
        'izin_download' => 'boolean',
        'izin_lihat'    => 'boolean',
        'sudah_dibaca'  => 'boolean',
        'dibaca_at'     => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // ─────────────────────────────────────────────
    // RELASI
    // ─────────────────────────────────────────────

    /** Dokumen yang berkaitan */
    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(DokumenBersama::class, 'id_dokumen', 'id');
    }

    /** User penerima */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // ─────────────────────────────────────────────
    // SCOPE
    // ─────────────────────────────────────────────

    /** Hanya penerima yang sudah membaca */
    public function scopeSudahDibaca(Builder $query): Builder
    {
        return $query->where('sudah_dibaca', true);
    }

    /** Hanya penerima yang belum membaca */
    public function scopeBelumDibaca(Builder $query): Builder
    {
        return $query->where('sudah_dibaca', false);
    }

    /** Filter berdasarkan user penerima */
    public function scopeUntukUser(Builder $query, int $userId): Builder
    {
        return $query->where('id_user', $userId);
    }

    // ─────────────────────────────────────────────
    // HELPER
    // ─────────────────────────────────────────────

    /**
     * Tandai dokumen ini sudah dibaca oleh penerima.
     * Idempotent: tidak berubah jika sudah dibaca sebelumnya.
     */
    public function tandaiDibaca(): bool
    {
        if ($this->sudah_dibaca) {
            return false; // sudah pernah dibaca, tidak perlu update
        }

        $this->sudah_dibaca = true;
        $this->dibaca_at    = Carbon::now();

        return $this->save();
    }

    /** Cek apakah user ini boleh mendownload */
    public function bolehDownload(): bool
    {
        return (bool) $this->izin_download;
    }

    /** Cek apakah user ini boleh melihat/membuka dokumen */
    public function bolehLihat(): bool
    {
        return (bool) $this->izin_lihat;
    }

    /**
     * Update izin penerima.
     *
     * Contoh:
     * $penerima->updateIzin(izin_download: false, izin_lihat: true);
     */
    public function updateIzin(bool $izin_download, bool $izin_lihat): bool
    {
        $this->izin_download = $izin_download;
        $this->izin_lihat    = $izin_lihat;

        return $this->save();
    }
}
