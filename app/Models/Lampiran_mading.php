<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lampiran_mading extends Model
{
    use HasFactory;

    protected $table = 'lampiran_mading';

    protected $fillable = [
        'id_mading',
        'tipe',
        'path',
    ];

    // Relasi ke Mading
    public function mading()
    {
        return $this->belongsTo(Mading::class, 'id_mading', 'id_mading');
    }

    // Accessor URL file
    protected $appends = ['url'];

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }
}
