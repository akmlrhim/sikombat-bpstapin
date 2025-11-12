<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kontrak extends Model
{
    protected $table = 'kontrak';

    protected $fillable = [
        'uuid',
        'nomor_kontrak',
        'mitra_id',
        'periode',
        'tanggal_kontrak',
        'tanggal_bast',
        'tanggal_surat',
        'tanggal_mulai',
        'tanggal_berakhir',
        'keterangan',
        'total_honor'
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }
}
