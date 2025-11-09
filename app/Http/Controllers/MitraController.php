<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            ->paginate(10)
            ->onEachSide(1)
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

        $created = Mitra::create([
            'uuid' => Str::uuid(),
            'nms' => $request->nms,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
        ]);

        if ($created) {
            return redirect()->route('mitra.index')
                ->with('success', 'Mitra berhasil ditambahkan.');
        } else {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan Mitra.');
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

        $mitra->nms = $request->nms;
        $mitra->nama_lengkap = $request->nama_lengkap;
        $mitra->jenis_kelamin = $request->jenis_kelamin;
        $mitra->alamat = $request->alamat;
        $updated = $mitra->save();

        if ($updated) {
            return redirect()->route('mitra.index')->with('success', 'Mitra berhasil diupdate.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengupdate Mitra.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $mitra = Mitra::where('uuid', $uuid)->first();
        $deleted = $mitra->delete();

        if ($deleted) {
            return redirect()->route('mitra.index')->with('success', 'Mitra berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus Mitra.');
        }
    }
}
