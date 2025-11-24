<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
	protected $table = 'akun';
	protected $fillable = [
		'uuid',
		'kode_akun',
		'nama_akun',
		'pagu_anggaran',
		'sisa_anggaran'
	];

	public function getRouteKeyName()
	{
		return 'uuid';
	}

	public function subAkun()
	{
		return $this->hasMany(SubAkun::class, 'id_akun');
	}
}
