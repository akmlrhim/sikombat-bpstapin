<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\DetailKontrak;
use App\Models\Kegiatan;
use App\Models\Kontrak;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KontrakController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return view('kontrak.index', [
			'title' => 'Kontrak Mitra',
			'kontrak' => Kontrak::paginate(10)
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		return view('kontrak.create', [
			'title' => 'Tambah Kontrak',
			'mitra' => Mitra::get(),
			'akun' => Akun::get(),
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$request->validate([
			// validasi data kontrak 
			'id_mitra' => 'required',
			'periode' => 'required',
			'tanggal_bast' => 'required|date',
			'tanggal_surat' => 'required|date',
			'tanggal_kontrak' => 'required|date',
			'tanggal_mulai' => 'required|date|before_or_equal:tanggal_berakhir',
			'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
			'keterangan' => 'nullable',

			// validasi detail kegiatan kontrak 
			'detail' => 'required|array|distinct',
			'detail.*.id_akun' => 'required|exists:akun,id',
			'detail.*.id_sub_akun' => 'required|exists:sub_akun,id',
			'detail.*.id_kegiatan' => 'required|exists:kegiatan,id|distinct',
			'detail.*.jumlah_target_dokumen' => 'required|numeric|min:0',
			'detail.*.jumlah_dokumen' => [
				'required',
				'numeric',
				'min:0',
				function ($attr, $value, $fail) use ($request) {
					$index = explode('.', $attr)[1];
					$target = $request->detail[$index]['jumlah_target_dokumen'];
					if ($value > $target) {
						$fail('Jumlah dokumen tidak boleh melebihi target');
					}
				}
			],
		]);

		try {
			DB::beginTransaction();

			// generate nomor kontrak 
			$lastNum = Kontrak::where('periode', $request->periode)
				->orderBy('nomor_kontrak', 'DESC')
				->value('nomor_kontrak');

			if ($lastNum) {
				$nextNum = str_pad(((int)$lastNum) + 1, 3, '0', STR_PAD_LEFT);
			} else {
				$nextNum = '001';
			}

			$kontrak = Kontrak::create([
				'uuid' => Str::uuid(),
				'nomor_kontrak' => $nextNum,
				'id_mitra' => $request->id_mitra,
				'periode' => $request->periode,
				'tanggal_kontrak' => $request->tanggal_kontrak,
				'tanggal_bast' => $request->tanggal_bast,
				'tanggal_surat' => $request->tanggal_surat,
				'tanggal_mulai' => $request->tanggal_mulai,
				'tanggal_berakhir' => $request->tanggal_berakhir,
				'keterangan' => $request->keterangan,
				'total_honor' => 0
			]);

			$totalHonorKontrak = 0;

			foreach ($request->detail as $d) {

				$kegiatan = Kegiatan::findOrFail($d['id_kegiatan']);
				$totalHonorPerKegiatan = $d['jumlah_dokumen'] * $kegiatan->harga_satuan;
				$totalHonorKontrak += $totalHonorPerKegiatan;

				DetailKontrak::create([
					'id_kontrak' => $kontrak->id,
					'id_akun' => $d['id_akun'],
					'id_sub_akun' => $d['id_sub_akun'],
					'id_kegiatan' => $d['id_kegiatan'],
					'jumlah_target_dokumen' => $d['jumlah_target_dokumen'],
					'jumlah_dokumen' => $d['jumlah_dokumen'],
					'total_honor' => $totalHonorPerKegiatan
				]);
			}

			$kontrak->update(['total_honor' => $totalHonorKontrak]);

			DB::commit();
			return redirect()->route('kontrak.index')->with('success', 'Kontrak berhasil dibuat.');
		} catch (\Exception $e) {
			DB::rollBack();
			return back()->with('error', 'Terjadi kesalahan.' . $e->getMessage());
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $uuid)
	{
		return view('kontrak.show', [
			'title' => 'Detail Kontrak',
			'kontrak' => Kontrak::with(
				['mitra', 'detail', 'detail.akun', 'detail.subAkun', 'detail.kegiatan']
			)->where('uuid', $uuid)->firstOrFail()
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $uuid)
	{
		return view('kontrak.edit', [
			'title' => 'Edit Kontrak',
			'akun' => Akun::get(),
			'kontrak' => Kontrak::with('detail')->where('uuid', $uuid)->firstOrFail(),
			'mitra' => Mitra::get()
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $uuid)
	{
		$kontrak = Kontrak::where('uuid', $uuid)->firstOrFail();

		$request->validate([
			// validasi data kontrak 
			'id_mitra' => 'required',
			'periode' => 'required',
			'tanggal_bast' => 'required|date',
			'tanggal_surat' => 'required|date',
			'tanggal_kontrak' => 'required|date',
			'tanggal_mulai' => 'required|date|before_or_equal:tanggal_berakhir',
			'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
			'keterangan' => 'nullable',

			// validasi detail kegiatan kontrak 
			'detail' => 'required|array|distinct',
			'detail.*.id_akun' => 'required|exists:akun,id',
			'detail.*.id_sub_akun' => 'required|exists:sub_akun,id',
			'detail.*.id_kegiatan' => 'required|exists:kegiatan,id|distinct',
			'detail.*.jumlah_target_dokumen' => 'required|numeric|min:0',
			'detail.*.jumlah_dokumen' => [
				'required',
				'numeric',
				'min:0',
				function ($attr, $value, $fail) use ($request) {
					$index = explode('.', $attr)[1];
					$target = $request->detail[$index]['jumlah_target_dokumen'];
					if ($value > $target) {
						$fail('Jumlah dokumen tidak boleh melebihi target');
					}
				}
			],
		]);

		try {
			DB::beginTransaction();

			$kontrak->update([
				'periode' => $request->periode,
				'tanggal_kontrak' => $request->tanggal_kontrak,
				'tanggal_bast' => $request->tanggal_bast,
				'tanggal_surat' => $request->tanggal_surat,
				'tanggal_mulai' => $request->tanggal_mulai,
				'tanggal_berakhir' => $request->tanggal_berakhir,
				'keterangan' => $request->keterangan,
			]);

			$kontrak->detail()->delete();

			$totalHonor = 0;

			foreach ($request->detail as $d) {
				$kegiatan = Kegiatan::findOrFail($d['id_kegiatan']);
				$total = $d['jumlah_dokumen'] * $kegiatan->harga_satuan;
				$totalHonor += $total;

				$kontrak->detail()->create([
					'id_akun' => $d['id_akun'],
					'id_sub_akun' => $d['id_sub_akun'],
					'id_kegiatan' => $d['id_kegiatan'],
					'jumlah_target_dokumen' => $d['jumlah_target_dokumen'],
					'jumlah_dokumen' => $d['jumlah_dokumen'],
					'total_honor' => $total
				]);
			}

			if ($request->jumlah_dokumen > $request->jumlah_target_dokumen) {
				DB::rollBack();
				return back()->withErrors(
					['jumlah_dokumen' => 'Jumlah dokumen tidak boleh melebihi target.']
				)->withInput();
			}

			$kontrak->update(['total_honor' => $totalHonor]);

			DB::commit();
			return redirect()->route('kontrak.index')->with('success', 'Kontrak berhasil diperbarui.');
		} catch (\Exception $e) {
			DB::rollBack();
			return back()->with('error', 'Terjadi kesalahan.' . $e->getMessage());
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Kontrak $kontrak)
	{
		try {
			DB::beginTransaction();
			$kontrak->delete();

			DB::commit();
			return back()->with('success', 'Kontrak berhasil dihapus.');
		} catch (\Exception $e) {
			DB::rollBack();
			return back()->with('error', 'Terjadi kesalahan.');
		}
	}
}
