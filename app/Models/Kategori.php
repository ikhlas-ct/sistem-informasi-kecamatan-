<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $fillable = [
        'nama_kategori',
        'slug',
        'icon',
        'status',
    ];

    public function konten()
    {
        return $this->belongsToMany(
            Konten::class,       // ← model yang dituju
            'kategori_konten',   // ← nama tabel pivot
            'id_kategori',       // ← foreign key pivot yang mengacu ke tabel kategori
            'id_konten'          // ← foreign key pivot yang mengacu ke tabel konten
        );




    }
}
