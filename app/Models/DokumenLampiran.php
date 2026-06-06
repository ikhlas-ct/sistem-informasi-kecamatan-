<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DokumenLampiran extends Model
{
    protected $table = 'dokumen_lampiran';

    protected $fillable = [
        'id_dokumen',
        'tipe',
        'nama_asli',
        'nama_simpan',
        'path',
        'mime_type',
        'ukuran_kb',
    ];

    protected $casts = [
        'ukuran_kb'  => 'integer',
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
    // SCOPE
    // ─────────────────────────────────────────────

    /** Hanya lampiran bertipe file */
    public function scopeFile(Builder $query): Builder
    {
        return $query->where('tipe', 'file');
    }

    /** Hanya lampiran bertipe foto */
    public function scopeFoto(Builder $query): Builder
    {
        return $query->where('tipe', 'foto');
    }

    // ─────────────────────────────────────────────
    // HELPER
    // ─────────────────────────────────────────────

    /**
     * Kembalikan URL publik lampiran (gunakan disk yang sesuai).
     *
     * Contoh: $lampiran->url()
     *         $lampiran->url('public')
     */
    public function url(string $disk = 'public'): string
    {
        return Storage::disk($disk)->url($this->path);
    }

    /** Ukuran dalam satuan MB (dibulatkan 2 desimal) */
    public function ukuranMb(): float
    {
        return round(($this->ukuran_kb ?? 0) / 1024, 2);
    }

    /** Apakah lampiran ini berupa foto/gambar? */
    public function isFoto(): bool
    {
        return $this->tipe === 'foto';
    }

    /** Apakah lampiran ini berupa file (non-foto)? */
    public function isFile(): bool
    {
        return $this->tipe === 'file';
    }

    /**
     * Hapus record sekaligus file fisiknya dari storage.
     *
     * Contoh: $lampiran->hapusDenganFile('public');
     */
    public function hapusDenganFile(string $disk = 'public'): bool
    {
        Storage::disk($disk)->delete($this->path);

        return $this->delete();
    }
}
