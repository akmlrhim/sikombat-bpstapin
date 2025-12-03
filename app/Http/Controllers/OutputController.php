<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Output;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class OutputController extends Controller
{
	/**
	 * Show the form for creating a new resource.
	 */
	public function create(Kegiatan $kegiatan)
	{
		return view('output.create', [
			'title' => 'Tambah Output',
			'kegiatan' => $kegiatan
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request, Kegiatan $kegiatan)
	{
		$cleaned = $request->all();

		if (isset($cleaned['komponen'])) {
			foreach ($cleaned['komponen'] as $i => $komponen) {
				if (isset($komponen['harga_satuan'])) {
					$harga_satuan = preg_replace('/[^\d]/', '', $komponen['harga_satuan']);
					$cleaned['komponen'][$i]['harga_satuan'] = $harga_satuan ?: 0;
				}
			}
		}

		$validated = validator($cleaned, [
			'kode_output' => 'required|unique:output,kode_output',
			'nama_output' => 'required',
			'deskripsi' => 'required',

			'komponen.*.kode_komponen' => 'required',
			'komponen.*.nama_komponen' => 'required',
			'komponen.*.jumlah_sampel' => 'required|numeric|min:0',
			'komponen.*.satuan' => 'required',
			'komponen.*.harga_satuan' => 'required|numeric|min:0',
		])->validate();

		try {
			DB::beginTransaction();

			$output = Output::create([
				'uuid' => Str::uuid(),
				'id_kegiatan' => $kegiatan->id,
				'kode_output' => $validated['kode_output'],
				'nama_output' => $validated['nama_output'],
				'deskripsi' => $validated['deskripsi']
			]);

			foreach ($validated['komponen'] as $komponen) {
				$harga_satuan = (int) $komponen['harga_satuan'];
				$jumlah_sampel = (int) $komponen['jumlah_sampel'];

				$output->komponen()->create([
					'kode_komponen' => $komponen['kode_komponen'],
					'nama_komponen' => $komponen['nama_komponen'],
					'jumlah_sampel' => $jumlah_sampel,
					'satuan' => $komponen['satuan'],
					'harga_satuan' => $harga_satuan,
					'total_harga' => $jumlah_sampel * $harga_satuan
				]);
			}

			// hitung total harga semua kegiatan 
			$totalOutput = $output->komponen()->sum('total_harga');

			if ($kegiatan->sisa_anggaran < $totalOutput) {
				DB::rollBack();
				Alert::error('Error', 'Sisa anggaran tidak mencukupi !.');
				return back()->withInput();
			}

			// perbarui sisa anggaran bdsrkn pengurangan total harga 
			$kegiatan->update(['sisa_anggaran' => $kegiatan->sisa_anggaran - $totalOutput]);

			DB::commit();
			Alert::success('Berhasil', 'Output berhasil dibuat.');
			return redirect()->route('kegiatan.output', $kegiatan->uuid);
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back()->withInput();
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Kegiatan $kegiatan, Output $output)
	{
		$output->load('komponen');

		return view('output.show', [
			'title' => 'Detail Output',
			'kegiatan' => $kegiatan,
			'output' => $output
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Kegiatan $kegiatan, Output $output)
	{
		return view('output.edit', [
			'title' => 'Edit Komponen',
			'kegiatan' => $kegiatan,
			'output' => $output
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Kegiatan $kegiatan, Output $output)
	{
		if ($request->has('komponen')) {
			$komponen = collect($request->komponen)->map(function ($item) {
				$item['harga_satuan'] = preg_replace('/[^\d]/', '', $item['harga_satuan'] ?? '');
				$item['harga_satuan'] = $item['harga_satuan'] === '' ? 0 : $item['harga_satuan'];
				return $item;
			})->toArray();
			$request->merge(['komponen' => $komponen]);
		}

		$validated = $request->validate([
			'kode_output' => ['required', Rule::unique('output', 'kode_output')->ignore($output->id)],
			'nama_output' => 'required',
			'deskripsi' => 'required',

			'komponen.*.kode_komponen' => 'required',
			'komponen.*.nama_komponen' => 'required',
			'komponen.*.jumlah_sampel' => 'required|numeric',
			'komponen.*.satuan' => 'required',
			'komponen.*.harga_satuan' => 'required|numeric',
		]);

		try {
			DB::beginTransaction();

			// ambil total output lama 
			$totalOutputLama = $output->komponen()->sum('total_harga');

			$output->update([
				'id_output' => $output->id,
				'kode_output' => $validated['kode_output'],
				'nama_output' => $validated['nama_output'],
				'deskripsi' => $validated['deskripsi'],
			]);

			// hapus kegiatan lama terlebih dahulu 
			$output->komponen()->delete();

			foreach ($validated['komponen'] as $k) {
				$harga_satuan = (int) $k['harga_satuan'];
				$jumlah_sampel = (int) $k['jumlah_sampel'];

				$output->komponen()->create([
					'kode_komponen' => $k['kode_komponen'],
					'nama_komponen' => $k['nama_komponen'],
					'jumlah_sampel' => $jumlah_sampel,
					'satuan' => $k['satuan'],
					'harga_satuan' => $harga_satuan,
					'total_harga' => $jumlah_sampel * $harga_satuan,
				]);
			}

			// hitung total output harga semua kegiatan 
			$totalOutputBaru = $output->komponen()->sum('total_harga');
			$selisih = $totalOutputBaru - $totalOutputLama; //hitung selisih

			if ($kegiatan->sisa_anggaran < $selisih) {
				DB::rollBack();
				Alert::error('Error', 'Sisa anggaran tidak mencukupi !.');
				return back()->withInput();
			}

			// perbarui bdsrkn selisih total harga baru - lama 
			$kegiatan->update(['sisa_anggaran' => $kegiatan->sisa_anggaran - $selisih]);

			DB::commit();
			Alert::success('Berhasil', 'Output berhasil diperbarui.');
			return redirect()->route('kegiatan.output', $kegiatan->uuid);
		} catch (\Throwable $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.' . $e->getMessage());
			return back()->withInput();
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Kegiatan $kegiatan, Output $output)
	{
		try {
			DB::beginTransaction();
			$totalKomponen = $output->komponen()->sum('total_harga');

			$kegiatan->update(['sisa_anggaran' => $kegiatan->sisa_anggaran + $totalKomponen]);
			$output->delete();

			DB::commit();
			Alert::success('Berhasil', 'Output berhasil dihapus.');
			return redirect()->route('kegiatan.output', $kegiatan->uuid);
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back()->withInput();
		}
	}
}
