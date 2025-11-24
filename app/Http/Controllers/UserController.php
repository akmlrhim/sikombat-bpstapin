<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$title = 'User';

		$query = User::where('id', '!=', Auth::id())->where('role', '!=', 'admin');

		if ($request->filled('role')) {
			$query->where('role', $request->role);
		}

		if ($request->filled('keyword')) {
			$query->where(function ($q) use ($request) {
				$q->where('nama_lengkap', 'like', '%' . $request->keyword . '%')
					->orWhere('nip', 'like', '%' . $request->keyword . '%');
			});
		}

		$user = $query->latest()->paginate(10)->onEachSide(1)->withQueryString();

		return view('user.index', compact('title', 'user'));
	}


	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		$title = 'Tambah User';
		return view('user.create', compact('title'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$request->validate([
			'nama_lengkap' => 'required|unique:users,nama_lengkap,except,id',
			'email' => 'email:dns|required|unique:users,email,except,id',
			'nip' => 'required|numeric|unique:users,nip,except,id',
			'role' => 'required'
		]);

		try {
			DB::beginTransaction();

			User::create([
				'nama_lengkap' => $request->nama_lengkap,
				'email' => $request->email,
				'nip' => $request->nip,
				'role' => $request->role,
				'password' => Hash::make('bpstapin25')
			]);
			DB::commit();
			Alert::success('Berhasil', 'User berhasil ditambahkan.');
			return redirect()->route('user.index');
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back()->withInput();
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(int $id)
	{
		$title = 'Edit User';
		$user = User::findOrFail($id);

		return view('user.edit', compact('title', 'user'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, int $id)
	{
		$request->validate([
			'nama_lengkap' => 'required|unique:users,nama_lengkap,' . $id,
			'email' => 'email:dns|required|unique:users,email,' . $id,
			'nip' => 'required|numeric|unique:users,nip,' . $id,
			'role' => 'required'
		]);

		try {
			DB::beginTransaction();

			$user = User::findOrFail($id);
			$user->nama_lengkap = $request->nama_lengkap;
			$user->email = $request->email;
			$user->nip = $request->nip;
			$user->role = $request->role;
			$user->save();

			DB::commit();
			Alert::success('Berhasil', 'User berhasil diperbarui.');
			return redirect()->route('user.index');
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back()->withInput();
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(int $id)
	{
		try {
			DB::beginTransaction();

			User::findOrFail($id)->delete();
			DB::commit();
			Alert::success('Berhasil', 'User berhasil dihapus.');
			return redirect()->route('user.index');
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Terjadi kesalahan.');
			return back();
		}
	}
}
