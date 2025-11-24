<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class SettingsController extends Controller
{
	public function index()
	{
		$title = 'Tambahan';
		$sett = Settings::select('uuid', 'key', 'value')->get();

		return view('tambahan.index', compact('title', 'sett'));
	}

	public function edit($uuid)
	{
		$title = 'Tambahan';
		$sett = Settings::where('uuid', $uuid)->first();
		return view('tambahan.edit', compact('sett', 'title'));
	}

	public function update($uuid, Request $req)
	{
		$sett = Settings::where('uuid', $uuid)->first();

		$req->validate([
			'value' => 'required'
		]);

		try {
			DB::beginTransaction();

			$sett->value = $req->value;
			$sett->save();

			DB::commit();
			Alert::success('Berhasil', 'Data berhasil diperbarui.');
			return redirect()->route('profil.index');
		} catch (\Exception $e) {
			Alert::error('Error', 'Terjadi kesalahan');
			return back()->withInput();
		}
	}
}
