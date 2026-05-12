<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lampiran_balasan extends Model
{
    use HasFactory;

    protected $table = 'lampiran_balasan';

    protected $fillable = [
        'id_balasanpengaduan',
        'tipe',
        'path',
    ];

    public function balasanpengaduan()
    {
        return $this->belongsTo(Balasanpengaduan::class, 'id_balasanpengaduan', 'id_balasanpengaduan');
    }
}
