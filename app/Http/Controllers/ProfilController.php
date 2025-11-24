<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ProfilController extends Controller
{
	public function index()
	{
		$title = 'Profil Saya';
		return view('profil', compact('title'));
	}

	public function info(Request $request)
	{
		$user = User::findOrFail(Auth::id());

		$request->validate([
			'nama_lengkap' => 'required|unique:users,nama_lengkap,' . $user->id,
			'email' => 'required|email:dns|unique:users,email,' . $user->id,
			'nip' => 'required|unique:users,nip,' . $user->id,
		]);

		try {
			DB::beginTransaction();

			$user->nama_lengkap = $request->nama_lengkap;
			$user->nip = $request->nip;
			$user->email = $request->email;
			$user->save();

			DB::commit();
			Alert::success('Berhasil', 'Informasi pribadi berhasil diperbarui.');
			return back();
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back()->withInput();
		}
	}

	public function password(Request $request)
	{
		$user = User::findOrFail(Auth::id());

		$request->validate([
			'current_password' => 'required',
			'new_password' => 'required|min:8|confirmed',
		]);

		try {
			DB::beginTransaction();

			if (!Hash::check($request->current_password, $user->password)) {
				return back()->withErrors([
					'current_password' => 'Password saat ini tidak valid.',
				])->withInput();
			}

			if ($request->current_password === $request->new_password) {
				return back()->withErrors([
					'new_password' => 'Password baru tidak boleh sama dengan password saat ini.',
				])->withInput();
			}

			$user->update([
				'password' => Hash::make($request->new_password),
			]);

			DB::commit();
			Alert::success('Berhasil', 'Password berhasil diperbarui.');
			return back();
		} catch (\Exception $e) {
			DB::rollBack();
			Alert::error('Error', 'Terjadi kesalahan.');
			return back()->withInput();
		}
	}
}
