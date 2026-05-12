<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori_konten extends Model
{
    use HasFactory;

    protected $table = 'kategori_konten';
    protected $primaryKey = 'id_kategori_konten';
    protected $fillable = [
        'id_konten',
        'id_kategori',
    ];

    public function kategori()
{
    return $this->belongsToMany(Konten::class, 'kategori_konten', 'id_kategori', 'id_konten');
}

}
