<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
	protected $table = 'output';
	protected $fillable = [
		'uuid',
		'id_kegiatan',
		'kode_output',
		'nama_output',
		'deskripsi'
	];

	public function getRouteKeyName()
	{
		return 'uuid';
	}

	public function kegiatan()
	{
		return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
	}

	public function komponen()
	{
		return $this->hasMany(Komponen::class, 'id_output');
	}
}
