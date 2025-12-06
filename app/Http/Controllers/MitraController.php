<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class MitraController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$title = 'Mitra';

		$keyword = $request->search;

		$mitra = Mitra::when($keyword, function ($query, $keyword) {
			return $query->where('nama_lengkap', 'like', "%{$keyword}%");
		})
			->latest()
			->simplePaginate(10)
			->appends(['search' => $keyword]);

		return view('mitra.index', compact('title', 'mitra', 'keyword'));
	}


	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		$title = 'Tambah Mitra';
		return view('mitra.create', compact('title'));
	}

	/**
	 * Store a newly created resource in storage.
	 */

	public function store(Request $request)
	{
		$request->validate([
			'nms' => 'required|unique:mitra,nms,except,id',
			'nama_lengkap' => 'required',
			'jenis_kelamin' => 'required',
			'alamat' => 'required',
		]);

		try {
			DB::beginTransaction();

			Mitra::create([
				'uuid' => Str::uuid(),
				'nms' => $request->nms,
				'nama_lengkap' => $request->nama_lengkap,
				'jenis_kelamin' => $request->jenis_kelamin,
				'alamat' => $request->alamat,
			]);

			DB::commit();
			Alert::success('Berhasil', 'Mitra berhasil dibuat.');
			return redirect()->route('mitra.index');
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
		$title = 'Edit Mitra';
		$mitra = Mitra::where('uuid', $uuid)->first();

		return view('mitra.edit', compact('title', 'mitra'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $uuid)
	{
		$mitra = Mitra::where('uuid', $uuid)->first();

		$request->validate([
			'nms' => [
				'required',
				Rule::unique('mitra', 'nms')->ignore($mitra->id),
			],
			'nama_lengkap' => 'required',
			'jenis_kelamin' => 'required',
			'alamat' => 'required',
		]);

		try {
			DB::beginTransaction();

			$mitra->nms = $request->nms;
			$mitra->nama_lengkap = $request->nama_lengkap;
			$mitra->jenis_kelamin = $request->jenis_kelamin;
			$mitra->alamat = $request->alamat;
			$mitra->save();

			DB::commit();
			Alert::success('Berhasil', 'User berhasil diperbarui.');
			return redirect()->route('mitra.index');
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan');
			return back()->withInput();
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $uuid)
	{
		$mitra = Mitra::where('uuid', $uuid)->first();

		try {
			DB::beginTransaction();

			$mitra->delete();
			DB::commit();
			Alert::success('Berhasil', 'User berhasil dihapus.');
			return redirect()->route('mitra.index');
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back();
		}
	}
}
