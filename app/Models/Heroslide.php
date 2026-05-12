<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Heroslide extends Model
{
    use HasFactory;
    protected $table = 'hero_slides';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'title',
        'description',
        'button_text',
        'button_link',
    ];




}
