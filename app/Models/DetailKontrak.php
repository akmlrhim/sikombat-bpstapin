<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKontrak extends Model
{
	protected $table = 'detail_kontrak';

	protected $fillable = [
		'id_mitra',
		'id_kontrak',
		'id_akun',
		'id_sub_akun',
		'id_kegiatan',
		'jumlah_target_dokumen',
		'jumlah_dokumen',
		'total_honor'
	];

	public function kontrak()
	{
		return $this->belongsTo(Kontrak::class, 'id_kontrak');
	}

	public function subAkun()
	{
		return $this->belongsTo(SubAkun::class, 'id_sub_akun');
	}

	public function kegiatan()
	{
		return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
	}

	public function akun()
	{
		return $this->belongsTo(Akun::class, 'id_akun');
	}
}
