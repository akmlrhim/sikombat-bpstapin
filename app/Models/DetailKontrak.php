<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKontrak extends Model
{
    protected $table = 'detail_kontrak';

    protected $fillable = [
        'kontrak_id',
        'akun_id',
        'jumlah_target_dokumen',
        'jumlah_dokumen',
        'total_honor'
    ];

    public function kontrak()
    {
        return $this->belongsTo(Kontrak::class, 'kontrak_id');
    }

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'akun_id');
    }
}
