<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komponen extends Model
{
	protected $table = 'komponen';
	protected $fillable = [
		'id_output',
		'kode_komponen',
		'nama_komponen',
		'jumlah_sampel',
		'satuan',
		'harga_satuan',
		'total_harga'
	];

	public function output()
	{
		return $this->belongsTo(Output::class, 'id_output');
	}
}
