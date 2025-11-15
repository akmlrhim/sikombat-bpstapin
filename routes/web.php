<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KontrakController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubAkunController;
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

		// akun route 
		Route::prefix('akun')->name('akun.')->controller(AkunController::class)->group(function () {
			Route::get('/', 'index')->name('index');
			Route::get('create', 'create')->name('create');
			Route::post('', 'store')->name('store');
			Route::get('{akun}/edit', 'edit')->name('edit');
			Route::put('{akun}', 'update')->name('update');
			Route::delete('{akun}', 'destroy')->name('destroy');
			Route::get('{akun}/sub-akun', 'subAkun')->name('sub-akun');
		});

		// sub akun route 
		Route::prefix('akun/{akun:uuid}/sub-akun')->name('akun.sub-akun.')->controller(SubAkunController::class)->group(function () {
			Route::get('create', 'create')->name('create');
			Route::post('', 'store')->name('store');
			Route::get('{sub_akun}/edit', 'edit')->name('edit');
			Route::get('{sub_akun}/detail', 'show')->name('show');
			Route::put('{sub_akun}', 'update')->name('update');
			Route::delete('{sub_akun}', 'destroy')->name('destroy');
		});

		// mitra route 
		Route::prefix('mitra')->name('mitra.')->controller(MitraController::class)->group(function () {
			Route::get('/', 'index')->name('index');

			Route::middleware('role:ketua_tim,umum')->group(function () {
				Route::get('create', 'create')->name('create');
				Route::post('', 'store')->name('store');
				Route::get('{mitra}/edit', 'edit')->name('edit');
				Route::put('{mitra}', 'update')->name('update');
				Route::delete('{mitra}', 'destroy')->name('destroy');
			});
		});

		// kontrak route 
		Route::prefix('kontrak')->name('kontrak.')->controller(KontrakController::class)->group(function () {
			Route::get('/', 'index')->name('index');
			Route::get('create', 'create')->name('create');
			Route::post('', 'store')->name('store');
			Route::get('{kontrak}/detail', 'show')->name('show');
			Route::get('{kontrak}/edit', 'edit')->name('edit');
			Route::put('{kontrak}', 'update')->name('update');
			Route::delete('{kontrak}', 'destroy')->name('destroy');
		});

		// user manage route 
		Route::resource('user', UserController::class)->except('show')->middleware('role:ketua_tim,umum,admin');

		// profil route 
		Route::get('profil', [ProfilController::class, 'index'])->name('profil.index');
		Route::patch('profil-info', [ProfilController::class, 'info'])->name('profil.info');
		Route::patch('profil-pwd', [ProfilController::class, 'password'])->name('profil.pwd');

		// tambahan route 
		Route::resource('tambahan', SettingsController::class)->except(['create', 'store']);

		// stats pengunjung web route 
		Route::get('pengunjung-web', function () {
			$title = 'Pengunjung Web';
			$visitor = Visit::with('users')->paginate(10);

			return view('visit', compact('visitor', 'title'));
		})->name('visit')->middleware('role:admin');
	});
});
