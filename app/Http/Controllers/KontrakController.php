<?php

namespace App\Http\Controllers;

use App\Models\DetailKontrak;
use App\Models\Kegiatan;
use App\Models\Komponen;
use App\Models\Kontrak;
use App\Models\Mitra;
use App\Models\Settings;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class KontrakController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$query = Kontrak::with('mitra');

		// cari nama mitra 
		if ($request->keyword) {
			$query->whereHas('mitra', function ($q) use ($request) {
				$q->where('nama_lengkap', 'LIKE', '%' . $request->keyword . '%');
			});
		}

		// filter periode 
		if ($request->periode) {
			$year = date('Y', strtotime($request->periode));
			$month = date('m', strtotime($request->periode));

			$query->whereYear('periode', $year)->whereMonth('periode', $month);
		}

		return view('kontrak.index', [
			'title' => 'Kontrak Mitra',
			'kontrak' => $query->paginate(10)->withQueryString()
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
			'kegiatan' => Kegiatan::get(),
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$request->validate([
			// validasi data kontrak 
			'id_mitra' => [
				'required',
				Rule::unique('kontrak')
					->where(function ($query) use ($request) {
						$periode = $request->periode;
						return $query->whereMonth('periode', date('m', strtotime($periode)))
							->whereYear('periode', date('Y', strtotime($periode)));
					})
			],
			'periode' => 'required',
			'sebagai' => 'required',
			'tanggal_bast' => 'required|date',
			'tanggal_surat' => 'required|date',
			'tanggal_kontrak' => 'required|date',
			'tanggal_mulai' => 'required|date|before_or_equal:tanggal_berakhir',
			'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
			'keterangan' => 'nullable',

			// validasi detail kegiatan kontrak 
			'detail' => 'required|array|distinct',
			'detail.*.id_kegiatan' => 'required|exists:kegiatan,id',
			'detail.*.id_output' => 'required|exists:output,id',
			'detail.*.id_komponen' => 'required|exists:komponen,id|distinct',
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
				'sebagai' => $request->sebagai,
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

				$komponen = Komponen::findOrFail($d['id_komponen']);
				$totalHonorPerKomponen = $d['jumlah_dokumen'] * $komponen->harga_satuan;
				$totalHonorKontrak += $totalHonorPerKomponen;

				DetailKontrak::create([
					'id_kontrak' => $kontrak->id,
					'id_kegiatan' => $d['id_kegiatan'],
					'id_output' => $d['id_output'],
					'id_komponen' => $d['id_komponen'],
					'jumlah_target_dokumen' => $d['jumlah_target_dokumen'] ?? $komponen->jumlah_sampel,
					'jumlah_dokumen' => $d['jumlah_dokumen'],
					'total_honor' => $totalHonorPerKomponen
				]);
			}

			$batasHonor = (int) Settings::where('key', 'batas_honor')->value('value');

			if ($totalHonorKontrak > $batasHonor) {
				DB::rollBack();
				Alert::warning('Pemberitahuan', 'Total Honor tidak boleh melebihi Rp ' . number_format($batasHonor, 0, ',', '.'));
				return back()->withInput();
			}

			$kontrak->update(['total_honor' => $totalHonorKontrak]);

			DB::commit();
			Alert::success('Berhasil', 'Kontrak berhasil dibuat.');
			return redirect()->route('kontrak.index');
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::warning('Error', 'Terjadi kesalahan.');
			return back()->withInput();
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
				['mitra', 'detail', 'detail.kegiatan', 'detail.output', 'detail.komponen']
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
			'kegiatan' => Kegiatan::get(),
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
			'id_mitra' => [
				'required',
				Rule::unique('kontrak')->ignore($kontrak->id)
					->where(function ($query) use ($request) {
						$periode = $request->periode;
						return $query->whereMonth('periode', date('m', strtotime($periode)))
							->whereYear('periode', date('Y', strtotime($periode)));
					})
			],
			'periode' => 'required',
			'tanggal_bast' => 'required|date',
			'tanggal_surat' => 'required|date',
			'tanggal_kontrak' => 'required|date',
			'tanggal_mulai' => 'required|date|before_or_equal:tanggal_berakhir',
			'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
			'keterangan' => 'nullable',

			// validasi detail kegiatan kontrak 
			'detail' => 'required|array|distinct',
			'detail.*.id_kegiatan' => 'required|exists:kegiatan,id',
			'detail.*.id_output' => 'required|exists:output,id',
			'detail.*.id_komponen' => 'required|exists:komponen,id|distinct',
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
				$komponen = Komponen::findOrFail($d['id_komponen']);
				$total = $d['jumlah_dokumen'] * $komponen->harga_satuan;
				$totalHonor += $total;

				$kontrak->detail()->create([
					'id_kegiatan' => $d['id_kegiatan'],
					'id_output' => $d['id_output'],
					'id_komponen' => $d['id_komponen'],
					'jumlah_target_dokumen' => $d['jumlah_target_dokumen'],
					'jumlah_dokumen' => $d['jumlah_dokumen'],
					'total_honor' => $total
				]);
			}

			$batasHonor = Settings::where('key', 'batas_honor')->value('value');
			if ($totalHonor > $batasHonor) {
				DB::rollBack();
				Alert::warning('Pemberitahuan', 'Total honor tidak boleh melebihi Rp ' . number_format($batasHonor, 0, ',', '.'));
				return back()->withInput();
			}

			$kontrak->update(['total_honor' => $totalHonor]);

			DB::commit();
			Alert::success('Berhasil', 'Kontrak berhasil diperbarui.');
			return redirect()->route('kontrak.index');
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.' . $e->getMessage());
			return back()->withInput();
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
			Alert::success('Berhasil', 'Kontrak berhasil dihapus.');
			return back();
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back()->withInput();
		}
	}

	public function fileKontrak(string $uuid)
	{
		$kepalaBps = Settings::where('key', 'kepala_bps_tapin')->value('value');
		$pjbPembuatKomit = Settings::where('key', 'pejabat_pembuat_komitmen')->value('value');

		$kontrak = Kontrak::with(
			['mitra', 'detail', 'detail.kegiatan', 'detail.output', 'detail.komponen']
		)->where('uuid', $uuid)->firstOrFail();

		$pdf = Pdf::loadView('kontrak.file-kontrak', compact('kepalaBps', 'pjbPembuatKomit', 'kontrak'))->setPaper('A4', 'potrait');

		$pdf->output();
		$dompdf = $pdf->getDomPDF();
		$canvas = $dompdf->getCanvas();

		$canvas->page_text(
			280, //posisi x
			20, //posisi y
			"- {PAGE_NUM} -", //nomor halaman
			null, //font default
			12, //font size 12
			[0, 0, 0] //warna hitam
		);

		return $pdf->stream('File Kontrak-' . time() . '.pdf');
	}
}
