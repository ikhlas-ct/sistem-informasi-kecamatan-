<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nagari extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'nagari';

    protected $fillable = [

        'nama_nagari',
        'status',
        'alamat',
        'singkatan',
        'surat_keterangan_tidak_mampu',

    ];

    protected $casts = [
        'surat_keterangan_tidak_mampu' => 'boolean',
    ];

    public function masyarakat()
    {
        return $this->hasOne(Masyarakat::class, 'id_nagari', 'id');
    }
    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'id_nagari', 'id');
    }


}
