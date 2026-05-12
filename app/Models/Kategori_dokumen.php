<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori_dokumen extends Model
{
     use HasFactory;
    protected $table = 'kategori_dokumen';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_kategori',
        'status'

    ];
    public function arsipDokumen()
    {
        return $this->hasMany(Arsip_dokumen::class, 'id_kategori', 'id');
    }
}
