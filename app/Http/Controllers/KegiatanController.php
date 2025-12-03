<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class KegiatanController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return view('kegiatan.index', [
			'title' => 'Kegiatan',
			'kegiatan' => Kegiatan::paginate(10)
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		return view('kegiatan.create', [
			'title' => 'Tambah Kegiatan'
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
			'kode_kegiatan' => 'required|unique:kegiatan,kode_kegiatan',
			'nama_kegiatan' => 'required',
			'pagu_anggaran' => 'required|numeric',
		]);

		try {
			DB::beginTransaction();

			Kegiatan::create([
				'uuid' => Str::uuid(),
				'kode_kegiatan' => $request->kode_kegiatan,
				'nama_kegiatan' => $request->nama_kegiatan,
				'pagu_anggaran' => $request->pagu_anggaran,
				'sisa_anggaran' => $request->pagu_anggaran,
			]);

			DB::commit();
			Alert::success('Berhasil', 'Kegiatan berhasil disimpan.');
			return redirect()->route('kegiatan.index');
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
		return view('kegiatan.edit', [
			'title' => 'Edit Kegiatan',
			'kegiatan' => Kegiatan::where('uuid', $uuid)->first(),
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

		// Validasi input
		$validated = $request->validate([
			'kode_kegiatan' => 'required|string',
			'nama_kegiatan' => 'required|string',
			'pagu_anggaran' => 'required|numeric',
			'sisa_anggaran' => 'required|numeric',
		]);

		try {
			DB::beginTransaction();

			$kegiatan = Kegiatan::where('uuid', $uuid)->firstOrFail();

			$kegiatan->update([
				'kode_kegiatan' => $validated['kode_kegiatan'],
				'nama_kegiatan' => $validated['nama_kegiatan'],
				'pagu_anggaran' => $validated['pagu_anggaran'],
				'sisa_anggaran' => $validated['sisa_anggaran'],
			]);

			DB::commit();
			Alert::success('Berhasil', 'kegiatan berhasil diperbarui.');
			return redirect()->route('kegiatan.index');
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

			Kegiatan::findOrFail($id)->delete();

			DB::commit();
			Alert::success('Berhasil', 'kegiatan berhasil dihapus.');
			return redirect()->back();
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back()->withInput();
		}
	}

	public function output($uuid)
	{
		return view('kegiatan.output', [
			'title' => 'Output',
			'kegiatan' => Kegiatan::with('output')->where('uuid', $uuid)->first()
		]);
	}
}
