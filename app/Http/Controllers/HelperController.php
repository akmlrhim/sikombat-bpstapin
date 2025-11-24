<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\SubAkun;

class HelperController extends Controller
{
	public function getSubAkun($id_akun)
	{
		$data = SubAkun::where('id_akun', $id_akun)->get();
		return response()->json($data);
	}

	public function getKegiatan($id_sub_akun)
	{
		$data = Kegiatan::where('id_sub_akun', $id_sub_akun)->get();
		return response()->json($data);
	}
}
