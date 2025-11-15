<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubAkun extends Model
{
    protected $table = 'sub_akun';
    protected $fillable = [
        'uuid',
        'id_akun',
        'kode_sub_akun',
        'nama_sub_akun',
        'nama_kegiatan_sub_akun'
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'id_sub_akun');
    }
}
