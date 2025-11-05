<?php

use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KontrakController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Models\Visit;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:60,1')->group(function () {

	Route::get('/', function () {
		return redirect()->route('home');
	});

	Route::get('/login', function () {
		return view('login');
	});

	Route::post('/login', [AuthController::class, 'login'])->name('login');

	Route::middleware('auth')->group(function () {
		Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

		Route::get('/home', [HomeController::class, 'index'])->name('home');

		Route::get('mitra', [MitraController::class, 'index'])->name('mitra.index');
		Route::get('mitra/create', [MitraController::class, 'create'])->name('mitra.create')->middleware('role:ketua_tim,umum');
		Route::post('mitra', [MitraController::class, 'store'])->name('mitra.store')->middleware('role:ketua_tim,umum');
		Route::get('mitra/{mitra}/edit', [MitraController::class, 'edit'])->name('mitra.edit')->middleware('role:ketua_tim,umum');
		Route::put('mitra/{mitra}', [MitraController::class, 'update'])->name('mitra.update')->middleware('role:ketua_tim,umum');
		Route::delete('mitra/{mitra}', [MitraController::class, 'destroy'])->name('mitra.destroy')->middleware('role:ketua_tim,umum');

		Route::resource('user', UserController::class)->except('show')->middleware('role:ketua_tim,umum,admin');

		Route::get('profil', [ProfilController::class, 'index'])->name('profil.index');
		Route::patch('profil-info', [ProfilController::class, 'info'])->name('profil.info');
		Route::patch('profil-pwd', [ProfilController::class, 'password'])->name('profil.pwd');

		Route::resource('tambahan', SettingsController::class)->except(['create', 'store']);
	});

	Route::get('pengunjung-web', function () {
		$title = 'Pengunjung Web';
		$visitor = Visit::with('users')->paginate(10);

		return view('visit', compact('visitor', 'title'));
	})->name('visit')->middleware('role:admin');
});
