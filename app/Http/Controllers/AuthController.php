<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ReCaptchaServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
	public function login(Request $request)
	{
		$request->validate([
			'email'    => 'required|email',
			'password' => 'required|string|min:6',
			'g-recaptcha-response' => 'required',
		]);

		// cek apakah email ada
		$user = User::where('email', $request->email)->first();

		if (!$user) {
			return back()->withErrors([
				'email' => 'Email tidak terdaftar.',
			])->onlyInput('email');
		}

		if (!Hash::check($request->password, $user->password)) {
			return back()->withErrors([
				'password' => 'Password salah.',
			])->onlyInput('email');
		}

		$captcha = ReCaptchaServices::verify($request->input('g-recaptcha-response'));

		if (!($captcha['success'] ?? false)) {
			return back()->with('error', 'Verifikasi ReCaptcha gagal, coba lagi.')->withInput();
		}

		if (($captcha['score'] ?? 0) < 0.5) {
			return back()->withInput()->with('error', 'Aktivitas mencurigaka terdeteksi, matikan VPN.');
		}

		Auth::login($user);
		$request->session()->regenerate();

		Alert::info('Info', 'Anda telah login.');
		return redirect()->intended('/home');
	}

	public function logout(Request $request)
	{
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return redirect('/');
	}
}
