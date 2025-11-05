<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';
    protected $fillable = [
        'id_sub_akun',
        'kode_akun_kegiatan',
        'nama_kegiatan',
        'jumlah_sampel',
        'satuan',
        'harga_satuan',
        'total_harga'
    ];

    public function subAkun()
    {
        return $this->belongsTo(SubAkun::class, 'id_sub_akun');
    }
}
