<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Mading extends Model
{
    use HasFactory;

    protected $table = 'mading';
    protected $primaryKey = 'id_mading';

    protected $fillable = [
        'id_user',
        'id_sekolah',
        'judul',
        'isi',
        'gambar',
        'slug',
        'jenis',
        'status',
        'approval_status',
        'alasan_penolakan',
        'tanggal_publikasi',
        'views',
    ];

    protected $casts = [
        'tanggal_publikasi' => 'datetime',
    ];

    // ── Relasi ──────────────────────────────────────────────────

    // Relasi ke User (bisa siswa atau akun sekolah)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Relasi ke Sekolah
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah', 'id_sekolah');
    }

    // Relasi ke Lampiran (foto/file tambahan)
    public function lampiran()
    {
        return $this->hasMany(Lampiran_mading::class, 'id_mading', 'id_mading');
    }

    // Relasi ke Komentar (reuse model existing)
    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'id_mading', 'id_mading');
    }

    // Relasi ke Reaksi (reuse model existing)
    public function reaksi()
    {
        return $this->hasMany(Reaksi::class, 'id_mading', 'id_mading');
    }

    // ── Helper / Accessor ────────────────────────────────────────

    // Cek apakah mading ini diposting oleh siswa
    public function isPostedBySiswa(): bool
    {
        return $this->user?->role === 'siswa';
    }

    // Cek apakah sudah approved
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    // Cek apakah sedang pending review
    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    // Accessor URL gambar utama
    protected $appends = ['gambar_url'];

    public function getGambarUrlAttribute(): ?string
    {
        if ($this->gambar) {
            return Storage::url($this->gambar);
        }

        return null;
    }

    // ── Scope ────────────────────────────────────────────────────

    // Hanya yang tampil di landing page
    public function scopePublik($query)
    {
        return $query->where('status', 'publish')
            ->where('approval_status', 'approved');
    }

    // Filter berdasarkan sekolah
    public function scopeBySekolah($query, $id_sekolah)
    {
        return $query->where('id_sekolah', $id_sekolah);
    }

    // Filter berdasarkan jenis
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    // Mading pending yang perlu direview sekolah
    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }
}
