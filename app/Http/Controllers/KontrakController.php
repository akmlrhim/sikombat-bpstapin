<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Kegiatan;
use App\Models\Kontrak;
use App\Models\Mitra;
use App\Models\SubAkun;
use Illuminate\Http\Request;

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
      'subAkun' => SubAkun::get(),
      'kegiatan' => Kegiatan::get()
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      // validasi data kontrak 
      'mitra_id' => 'required',
      'periode' => 'required',
      'tanggal_kontrak' => 'required',
      'tanggal_bast' => 'required',
      'tanggal_surat' => 'required',
      'tanggal_mulai' => 'required',
      'tanggal_berakhir' => 'required',
      'keterangan' => 'nullable'

      // validasi detail kegiatan kontrak 
    ]);
  }

  /**
   * Display the specified resource.
   */
  public function show(Kontrak $kontrak)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Kontrak $kontrak)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Kontrak $kontrak)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Kontrak $kontrak)
  {
    //
  }
}
