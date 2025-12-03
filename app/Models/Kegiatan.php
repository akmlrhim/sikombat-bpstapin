<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
	protected $table = 'kegiatan';
	protected $fillable = [
		'uuid',
		'kode_kegiatan',
		'nama_kegiatan',
		'pagu_anggaran',
		'sisa_anggaran'
	];

	public function getRouteKeyName()
	{
		return 'uuid';
	}

	public function output()
	{
		return $this->hasMany(Output::class, 'id_kegiatan');
	}
}
