<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kontrak extends Model
{
	protected $table = 'kontrak';

	protected $fillable = [
		'uuid',
		'nomor_kontrak',
		'id_mitra',
		'periode',
		'sebagai',
		'tanggal_kontrak',
		'tanggal_bast',
		'tanggal_surat',
		'tanggal_mulai',
		'tanggal_berakhir',
		'keterangan',
		'total_honor'
	];

	protected $casts = [
		'tanggal_kontrak' => 'date',
		'tanggal_bast' => 'date',
		'tanggal_surat' => 'date',
		'tanggal_mulai' => 'date',
		'tanggal_berakhir' => 'date',
		'periode' => 'date'
	];

	public function getRouteKeyName()
	{
		return 'uuid';
	}

	public function mitra()
	{
		return $this->belongsTo(Mitra::class, 'id_mitra');
	}

	public function detail()
	{
		return $this->hasMany(DetailKontrak::class, 'id_kontrak');
	}
}
