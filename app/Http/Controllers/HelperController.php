<?php

namespace App\Http\Controllers;

use App\Models\Komponen;
use App\Models\Output;

class HelperController extends Controller
{
	public function getOutput($id_kegiatan)
	{
		$data = Output::where('id_kegiatan', $id_kegiatan)->get();
		return response()->json($data);
	}

	public function getKomponen($id_output)
	{
		$data = Komponen::where('id_output', $id_output)->get();
		return response()->json($data);
	}
}
