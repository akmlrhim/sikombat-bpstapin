<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class AkunController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return view('akun.index', [
			'title' => 'Akun',
			'akun' => Akun::paginate(10)
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		return view('akun.create', [
			'title' => 'Tambah Akun'
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$request->merge([
			'pagu_anggaran' => preg_replace('/[^0-9]/', '', $request->pagu_anggaran),
			'sisa_anggaran' => preg_replace('/[^0-9]/', '', $request->pagu_anggaran),
		]);

		$request->validate([
			'kode_akun' => 'required|unique:akun,kode_akun',
			'nama_akun' => 'required',
			'pagu_anggaran' => 'required|numeric',
		]);

		try {
			DB::beginTransaction();

			Akun::create([
				'uuid' => Str::uuid(),
				'kode_akun' => $request->kode_akun,
				'nama_akun' => $request->nama_akun,
				'pagu_anggaran' => $request->pagu_anggaran,
				'sisa_anggaran' => $request->pagu_anggaran,
			]);

			DB::commit();
			Alert::success('Berhasil', 'Akun berhasil disimpan.');
			return redirect()->route('akun.index');
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back()->withInput();
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $uuid)
	{
		return view('akun.edit', [
			'title' => 'Edit Akun',
			'akun' => Akun::where('uuid', $uuid)->first(),
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $uuid)
	{
		$request->merge([
			'pagu_anggaran' => preg_replace('/[^0-9]/', '', $request->pagu_anggaran),
			'sisa_anggaran' => preg_replace('/[^0-9]/', '', $request->pagu_anggaran),
		]);

		if (isset($request->sub_akun['kegiatan'])) {
			foreach ($request->sub_akun['kegiatan'] as $j => $kegiatan) {
				$request->merge([
					"sub_akun.kegiatan.$j.harga_satuan" => preg_replace('/[^0-9]/', '', $kegiatan['harga_satuan']),
				]);
			}
		}

		// Validasi input
		$validated = $request->validate([
			'kode_akun' => 'required|string',
			'nama_akun' => 'required|string',
			'pagu_anggaran' => 'required|numeric',
			'sisa_anggaran' => 'required|numeric',
		]);

		try {
			DB::beginTransaction();

			$akun = Akun::where('uuid', $uuid)->firstOrFail();

			$akun->update([
				'kode_akun' => $validated['kode_akun'],
				'nama_akun' => $validated['nama_akun'],
				'pagu_anggaran' => $validated['pagu_anggaran'],
				'sisa_anggaran' => $validated['sisa_anggaran'],
			]);

			DB::commit();
			Alert::success('Berhasil', 'Akun berhasil diperbarui.');
			return redirect()->route('akun.index');
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back()->withInput();
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		try {
			DB::beginTransaction();

			Akun::findOrFail($id)->delete();

			DB::commit();
			Alert::success('Berhasil', 'Akun berhasil dihapus.');
			return redirect()->back();
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back()->withInput();
		}
	}

	public function subAkun($uuid)
	{
		return view('akun.sub-akun', [
			'title' => 'Sub Akun',
			'akun' => Akun::with('subAkun')->where('uuid', $uuid)->first()
		]);
	}
}
