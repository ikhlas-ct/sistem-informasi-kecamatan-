<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lampiran_pengaduan extends Model
{
    use HasFactory;

    protected $table = 'lampiran_pengaduans';

    protected $fillable = [
        'id_pengaduan',
        'tipe',
        'path',
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'id_pengaduan', 'id_pengaduan');
    }
}
