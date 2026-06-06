<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenLink extends Model
{
    protected $table = 'dokumen_link';

    protected $fillable = [
        'id_dokumen',
        'judul',
        'url',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ─────────────────────────────────────────────
    // RELASI
    // ─────────────────────────────────────────────

    /** Dokumen induk */
    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(DokumenBersama::class, 'id_dokumen', 'id');
    }

    // ─────────────────────────────────────────────
    // HELPER
    // ─────────────────────────────────────────────

    /**
     * Label yang ditampilkan di UI.
     * Jika kolom judul kosong, tampilkan URL-nya saja.
     */
    public function labelTampil(): string
    {
        return $this->judul ?: $this->url;
    }

    /**
     * Cek apakah URL ini berasal dari Google Drive.
     */
    public function isGoogleDrive(): bool
    {
        return str_contains($this->url, 'drive.google.com');
    }
}
