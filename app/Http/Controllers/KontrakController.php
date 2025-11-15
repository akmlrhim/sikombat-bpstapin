<?php

namespace App\Http\Controllers;

use App\Models\Kontrak;
use App\Models\Mitra;
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
      'mitra' => Mitra::get()
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
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
