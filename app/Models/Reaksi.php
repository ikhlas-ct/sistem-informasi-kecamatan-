<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaksi extends Model
{
    use HasFactory;

    protected $table = 'reaksi';
    protected $primaryKey = 'id_reaksi';

    protected $fillable = [
        'id_konten',
        'id_mading',
        'id_user',
        'jenis',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function konten()
    {
        return $this->belongsTo(Konten::class, 'id_konten', 'id_konten');
    }

    // Relasi ke Mading (jika reaksi ini untuk mading)
    public function mading()
    {
        return $this->belongsTo(Mading::class, 'id_mading', 'id_mading');
    }
}
