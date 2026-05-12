<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konten extends Model
{
    use HasFactory;

    protected $table = 'konten';
    protected $primaryKey = 'id_konten';

    protected $fillable = [
        'judul',
        'isi',
        'id_user',
        'slug',
        'tanggal_publikasi',
        'jenis_konten',
        'gambar',
        'status',
    ];

    protected $casts = [
        'tanggal_publikasi' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }



    public function reaksi()
    {
        return $this->hasMany(Reaksi::class, 'id_konten', 'id_konten');
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'id_konten', 'id_konten');
    }


    public function kategori()
    {
    return $this->belongsToMany(Kategori::class, 'kategori_konten', 'id_konten', 'id_kategori');
    }


}
