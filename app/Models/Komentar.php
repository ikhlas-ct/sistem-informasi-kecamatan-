<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Komentar extends Model
{
    use HasFactory;

    protected $table = 'komentar';

    protected $primaryKey = 'id_komentar';

    protected $fillable = [
        'id_konten',
        'nama',
        'email',
        'no_hp',
        'isi_komentar',
        'id_user',
        'ip_address',
        'root_id',
        'parent_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function konten()
    {
        return $this->belongsTo(Konten::class, 'id_konten', 'id_konten');
    }

    public function parent()
    {
        return $this->belongsTo(Komentar::class, 'parent_id', 'id_komentar');
    }

    public function balasan()
    {
        return $this->hasMany(Komentar::class, 'parent_id', 'id_komentar');
    }

    public function root()
{
    return $this->belongsTo(Komentar::class, 'root_id', 'id_komentar');
}

protected $appends = ['avatar_url'];

public function getAvatarUrlAttribute(): string
{
    // Kalau ada user dan pegawai punya foto
    if ($this->user && $this->user->pegawai && $this->user->pegawai->foto_profil) {
        return Storage::url($this->user->pegawai->foto_profil);
    }

    // Kalau ada user dan masyarakat punya foto
    if ($this->user && $this->user->masyarakat && $this->user->masyarakat->foto_profil) {
        return Storage::url($this->user->masyarakat->foto_profil);
    }

    // Fallback default
    return asset('default-image/default-user.png');
}


}
