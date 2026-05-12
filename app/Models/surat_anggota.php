<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class surat_anggota extends Model
{
    protected $table = 'surat_anggota';
    protected $fillable = [
        'id_pelayanan','nama','jk','umur','hubungan','pekerjaan'
    ];

    public function surat()
    {
        return $this->belongsTo(SuratKeteranganMiskin::class, 'id_pelayanan', 'id_pelayanan');
    }

}
