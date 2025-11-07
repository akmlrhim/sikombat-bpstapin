<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Kegiatan;
use App\Models\SubAkun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    foreach ($request->sub_akun as $i => $sub) {
      foreach ($sub['kegiatan'] as $j => $kegiatan) {
        $request->merge([
          "sub_akun.$i.kegiatan.$j.harga_satuan" => preg_replace('/[^0-9]/', '', $kegiatan['harga_satuan']),
        ]);
      }
    }

    // validasi input 
    $request->validate([
      'kode_akun' => 'required|unique:akun,kode_akun',
      'nama_akun' => 'required',
      'pagu_anggaran' => 'required|numeric',

      'sub_akun.*.kode_sub_akun' => 'required|unique:sub_akun,kode_sub_akun',
      'sub_akun.*.nama_sub_akun' => 'required|unique:sub_akun,nama_sub_akun',
      'sub_akun.*.nama_kegiatan_sub_akun' => 'required|unique:sub_akun,nama_kegiatan_sub_akun',
      'sub_akun.*.kegiatan.*.kode_akun_kegiatan' => 'required',
      'sub_akun.*.kegiatan.*.nama_kegiatan' => 'required',
      'sub_akun.*.kegiatan.*.jumlah_sampel' => 'required|numeric',
      'sub_akun.*.kegiatan.*.satuan' => 'required',
      'sub_akun.*.kegiatan.*.harga_satuan' => 'required|numeric',
    ]);

    try {
      DB::beginTransaction();

      $akun = Akun::create([
        'uuid' => Str::uuid(),
        'kode_akun' => $request->kode_akun,
        'nama_akun' => $request->nama_akun,
        'pagu_anggaran' => $request->pagu_anggaran,
        'sisa_anggaran' => $request->pagu_anggaran,
      ]);

      foreach ($request->sub_akun as $sub) {
        $subAkun = $akun->subAkun()->create([
          'kode_sub_akun' => $sub['kode_sub_akun'],
          'nama_sub_akun' => $sub['nama_sub_akun'],
          'nama_kegiatan_sub_akun' => $sub['nama_kegiatan_sub_akun'],
        ]);

        foreach ($sub['kegiatan'] as $kegiatan) {
          $subAkun->kegiatan()->create([
            'kode_akun_kegiatan' => $kegiatan['kode_akun_kegiatan'],
            'nama_kegiatan' => $kegiatan['nama_kegiatan'],
            'jumlah_sampel' => $kegiatan['jumlah_sampel'],
            'satuan' => $kegiatan['satuan'],
            'harga_satuan' => $kegiatan['harga_satuan'],
            'total_harga' => $kegiatan['jumlah_sampel'] * $kegiatan['harga_satuan'],
          ]);
        }
      }

      DB::commit();
      return redirect()->route('akun.index')->with('success', 'Akun berhasil disimpan.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'Terjadi kesalahan.');
    }
  }


  /**
   * Display the specified resource.
   */
  public function show(string $uuid)
  {
    return view('akun.show', [
      'title' => 'Detail Akun',
      'akun' => Akun::where('uuid', $uuid)->firstOrFail()
    ]);
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
      'sub_akun.kode_sub_akun' => 'required|string',
      'sub_akun.nama_sub_akun' => 'required|string',
      'sub_akun.nama_kegiatan_sub_akun' => 'required|string',
      'sub_akun.kegiatan.*.kode_akun_kegiatan' => 'required|string',
      'sub_akun.kegiatan.*.nama_kegiatan' => 'required|string',
      'sub_akun.kegiatan.*.jumlah_sampel' => 'required|numeric',
      'sub_akun.kegiatan.*.satuan' => 'required|string',
      'sub_akun.kegiatan.*.harga_satuan' => 'required|numeric',
    ]);

    try {
      DB::beginTransaction();

      $akun = Akun::where('uuid', $uuid)->with('subAkun.kegiatan')->firstOrFail();

      // Update data Akun utama
      $akun->update([
        'kode_akun' => $validated['kode_akun'],
        'nama_akun' => $validated['nama_akun'],
        'pagu_anggaran' => $validated['pagu_anggaran'],
        'sisa_anggaran' => $validated['sisa_anggaran'],
      ]);


      $subAkunData = $validated['sub_akun'];
      $subAkun = $akun->subAkun()->first();

      if ($subAkun) {
        $subAkun->update([
          'kode_sub_akun' => $subAkunData['kode_sub_akun'],
          'nama_sub_akun' => $subAkunData['nama_sub_akun'],
          'nama_kegiatan_sub_akun' => $subAkunData['nama_kegiatan_sub_akun'] ?? null,
        ]);
      }

      // Hapus semua kegiatan lama lalu buat ulang (lebih aman untuk edit dinamis)
      $subAkun->kegiatan()->delete();

      foreach ($subAkunData['kegiatan'] as $keg) {
        $subAkun->kegiatan()->create([
          'kode_akun_kegiatan' => $keg['kode_akun_kegiatan'],
          'nama_kegiatan' => $keg['nama_kegiatan'],
          'jumlah_sampel' => $keg['jumlah_sampel'],
          'satuan' => $keg['satuan'],
          'harga_satuan' => $keg['harga_satuan'],
          'total_harga' => $keg['jumlah_sampel'] * $keg['harga_satuan'],
        ]);
      }

      DB::commit();
      return redirect()->route('akun.index')->with('success', 'Akun berhasil diperbarui.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'Terjadi kesalahan');
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
      return redirect()->back()->with('success', 'Akun berhasil dihapus.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'Terjadi kesalahan.');
    }
  }
}
