<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\SubAkun;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SubAkunController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('sub-akun.index', [
      'title' => 'Sub Akun',
      'subAkun' => SubAkun::with('akun')->paginate(10)
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('sub-akun.create', [
      'title' => 'Tambah Sub Akun',
      'akun' => Akun::get()
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
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
      'id_akun' => 'required',
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
        'id_akun' => $validated['id_akun'],
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

      DB::commit();
      return redirect()->route('sub-akun.index')->with('success', 'Sub Akun dan kegiatan berhasil disimpan.');
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
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $uuid)
  {
    return view('sub-akun.edit', [
      'title' => 'Edit Sub Akun',
      'akun' => Akun::all(),
      'subAkun' => SubAkun::with('kegiatan')->where('uuid', $uuid)->firstOrFail(),
    ]);
  }


  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $uuid)
  {
    $subAkun = SubAkun::where('uuid', $uuid)->firstOrFail();

    if ($request->has('kegiatan')) {
      $kegiatan = collect($request->kegiatan)->map(function ($item) {
        $item['harga_satuan'] = preg_replace('/[^\d]/', '', $item['harga_satuan'] ?? '');
        $item['harga_satuan'] = $item['harga_satuan'] === '' ? 0 : $item['harga_satuan'];
        return $item;
      })->toArray();
      $request->merge(['kegiatan' => $kegiatan]);
    }

    $validated = $request->validate([
      'id_akun' => 'required',
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

      $subAkun->update([
        'id_akun' => $validated['id_akun'],
        'kode_sub_akun' => $validated['kode_sub_akun'],
        'nama_sub_akun' => $validated['nama_sub_akun'],
        'nama_kegiatan_sub_akun' => $validated['nama_kegiatan_sub_akun'],
      ]);

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

      DB::commit();
      return redirect()->route('sub-akun.index')->with('success', 'Data Sub Akun berhasil diperbarui.');
    } catch (\Throwable $e) {
      DB::rollBack();
      return back()->with('error', 'Terjadi kesalahan.');
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $uuid)
  {
    try {
      DB::beginTransaction();

      SubAkun::where('uuid', $uuid)->firstOrFail()->delete();
      DB::commit();
      return redirect()->route('sub-akun.index')->with('success', 'Data Sub Akun berhasil dihapus.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'Terjadi kesalahan.');
    }
  }
}
