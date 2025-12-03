<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKontrak extends Model
{
	protected $table = 'detail_kontrak';

	protected $fillable = [
		'id_mitra',
		'id_kontrak',
		'id_kegiatan',
		'id_output',
		'id_komponen',
		'jumlah_target_dokumen',
		'jumlah_dokumen',
		'total_honor'
	];

	public function kontrak()
	{
		return $this->belongsTo(Kontrak::class, 'id_kontrak');
	}

	public function output()
	{
		return $this->belongsTo(Output::class, 'id_output');
	}

	public function komponen()
	{
		return $this->belongsTo(Komponen::class, 'id_komponen');
	}

	public function kegiatan()
	{
		return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
	}
}
