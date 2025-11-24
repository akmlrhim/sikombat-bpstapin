<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\SubAkun;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class SubAkunController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return view('sub-akun.index', [
			'title' => 'Sub Akun',
			'subAkun' => SubAkun::with(['akun', 'kegiatan'])->paginate(10)
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(Akun $akun)
	{
		return view('sub-akun.create', [
			'title' => 'Tambah Sub Akun',
			'akun' => $akun
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request, Akun $akun)
	{
		$cleaned = $request->all();

		if (isset($cleaned['kegiatan'])) {
			foreach ($cleaned['kegiatan'] as $i => $kegiatan) {
				if (isset($kegiatan['harga_satuan'])) {
					$harga_satuan = preg_replace('/[^\d]/', '', $kegiatan['harga_satuan']);
					$cleaned['kegiatan'][$i]['harga_satuan'] = $harga_satuan ?: 0;
				}
			}
		}

		$validated = validator($cleaned, [
			'kode_sub_akun' => 'required|unique:sub_akun,kode_sub_akun',
			'nama_sub_akun' => 'required',
			'nama_kegiatan_sub_akun' => 'required',

			'kegiatan.*.kode_akun_kegiatan' => 'required',
			'kegiatan.*.nama_kegiatan' => 'required',
			'kegiatan.*.jumlah_sampel' => 'required|numeric|min:0',
			'kegiatan.*.satuan' => 'required',
			'kegiatan.*.harga_satuan' => 'required|numeric|min:0',
		])->validate();

		try {
			DB::beginTransaction();

			$subAkun = SubAkun::create([
				'uuid' => Str::uuid(),
				'id_akun' => $akun->id,
				'kode_sub_akun' => $validated['kode_sub_akun'],
				'nama_sub_akun' => $validated['nama_sub_akun'],
				'nama_kegiatan_sub_akun' => $validated['nama_kegiatan_sub_akun']
			]);

			foreach ($validated['kegiatan'] as $kegiatan) {
				$harga_satuan = (int) $kegiatan['harga_satuan'];
				$jumlah_sampel = (int) $kegiatan['jumlah_sampel'];

				$subAkun->kegiatan()->create([
					'kode_akun_kegiatan' => $kegiatan['kode_akun_kegiatan'],
					'nama_kegiatan' => $kegiatan['nama_kegiatan'],
					'jumlah_sampel' => $jumlah_sampel,
					'satuan' => $kegiatan['satuan'],
					'harga_satuan' => $harga_satuan,
					'total_harga' => $jumlah_sampel * $harga_satuan
				]);
			}

			// hitung total harga semua kegiatan 
			$totalKegiatan = $subAkun->kegiatan()->sum('total_harga');

			if ($akun->sisa_anggaran < $totalKegiatan) {
				DB::rollBack();
				return back()->with('error', 'Sisa anggaran tidak mencukupi.')->withInput();
			}

			// perbarui sisa anggaran bdsrkn pengurangan total harga 
			$akun->update(['sisa_anggaran' => $akun->sisa_anggaran - $totalKegiatan]);

			DB::commit();
			Alert::success('Berhasil', 'Sub Akun berhasil dibuat.');
			return redirect()->route('akun.sub-akun', $akun->uuid);
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back()->withInput();
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Akun $akun, SubAkun $subAkun)
	{
		$subAkun->load('kegiatan');

		return view('sub-akun.show', [
			'title' => 'Detail Sub Akun',
			'akun' => $akun,
			'subAkun' => $subAkun
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Akun $akun, SubAkun $subAkun)
	{
		return view('sub-akun.edit', [
			'title' => 'Edit Sub Akun',
			'akun' => $akun,
			'subAkun' => $subAkun
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Akun $akun, SubAkun $subAkun)
	{
		if ($request->has('kegiatan')) {
			$kegiatan = collect($request->kegiatan)->map(function ($item) {
				$item['harga_satuan'] = preg_replace('/[^\d]/', '', $item['harga_satuan'] ?? '');
				$item['harga_satuan'] = $item['harga_satuan'] === '' ? 0 : $item['harga_satuan'];
				return $item;
			})->toArray();
			$request->merge(['kegiatan' => $kegiatan]);
		}

		$validated = $request->validate([
			'kode_sub_akun' => ['required', Rule::unique('sub_akun', 'kode_sub_akun')->ignore($subAkun->id)],
			'nama_sub_akun' => 'required',
			'nama_kegiatan_sub_akun' => 'required',

			'kegiatan.*.kode_akun_kegiatan' => 'required',
			'kegiatan.*.nama_kegiatan' => 'required',
			'kegiatan.*.jumlah_sampel' => 'required|numeric',
			'kegiatan.*.satuan' => 'required',
			'kegiatan.*.harga_satuan' => 'required|numeric',
		]);

		try {
			DB::beginTransaction();

			// ambil total kegiatan lama 
			$totalKegiatanLama = $subAkun->kegiatan()->sum('total_harga');

			$subAkun->update([
				'id_akun' => $akun->id,
				'kode_sub_akun' => $validated['kode_sub_akun'],
				'nama_sub_akun' => $validated['nama_sub_akun'],
				'nama_kegiatan_sub_akun' => $validated['nama_kegiatan_sub_akun'],
			]);

			// hapus kegiatan lama terlebih dahulu 
			$subAkun->kegiatan()->delete();

			foreach ($validated['kegiatan'] as $k) {
				$harga_satuan = (int) $k['harga_satuan'];
				$jumlah_sampel = (int) $k['jumlah_sampel'];

				$subAkun->kegiatan()->create([
					'kode_akun_kegiatan' => $k['kode_akun_kegiatan'],
					'nama_kegiatan' => $k['nama_kegiatan'],
					'jumlah_sampel' => $jumlah_sampel,
					'satuan' => $k['satuan'],
					'harga_satuan' => $harga_satuan,
					'total_harga' => $jumlah_sampel * $harga_satuan,
				]);
			}

			// hitung total kegiatan harga semua kegiatan 
			$totalKegiatanBaru = $subAkun->kegiatan()->sum('total_harga');
			$selisih = $totalKegiatanBaru - $totalKegiatanLama; //hitung selisih

			if ($akun->sisa_anggaran < $selisih) {
				DB::rollBack();
				return back()->with('error', 'Sisa anggaran tidak mencukupi.')->withInput();
			}

			// perbarui bdsrkn selisih total harga baru - lama 
			$akun->update(['sisa_anggaran' => $akun->sisa_anggaran - $selisih]);

			DB::commit();
			Alert::success('Berhasil', 'Sub Akun berhasil diperbarui.');
			return redirect()->route('akun.sub-akun', $akun->uuid);
		} catch (\Throwable $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back()->withInput();
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Akun $akun, SubAkun $subAkun)
	{
		try {
			DB::beginTransaction();
			$totalKegiatan = $subAkun->kegiatan()->sum('total_harga');

			$akun->update(['sisa_anggaran' => $akun->sisa_anggaran + $totalKegiatan]);
			$subAkun->delete();

			DB::commit();
			Alert::success('Berhasil', 'Sub Akun berhasil dihapus.');
			return redirect()->route('akun.sub-akun', $akun->uuid);
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back()->withInput();
		}
	}
}
