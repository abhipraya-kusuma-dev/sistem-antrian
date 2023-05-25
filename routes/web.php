<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OperatorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
  return view('welcome');
});

Route::controller(AntrianController::class)->group(function () {
  // Display
  Route::get('/antrian', 'index');

  // Daftar Antrian
  Route::get('/antrian/daftar', 'antrianBaru');
  Route::get('/antrian/daftar/konfirmasi/{jenjang}', 'konfirmasiAntrianBaru');

  Route::post('/antrian/daftar/proses', 'buatAntrianBaru');
});

Route::controller(OperatorController::class)->group(function () {
  // Monitoring Antrian (admin)
  Route::get('/operator/antrian', 'antrian');
  Route::get('/operator/antrian/jenjang/{jenjang}', 'antrianPerJenjang');
  Route::get('/operator/antrian/panggil/{antrian:id}', 'panggilNomorAntrian');

  Route::post('/operator/antrian/lanjut/', 'lanjutAntrian');
  Route::post('/operator/antrian/lanjut/bendahara', 'lanjutKeBendahara');

  Route::put('/operator/antrian/terpanggil', 'nomorAntrianTerpanggil');
});

Route::controller(BendaharaController::class)->group(function () {
  Route::get('/bendahara/antrian', 'antrianBendahara');
  Route::get('/bendahara/antrian/panggil/{bendahara:id}', 'panggilNomorAntrian');
  Route::put('/bendahara/antrian/terpanggil', 'nomorAntrianTerpanggil');

  Route::post('/bendahara/antrian/lanjut/', 'lanjutAntrian');
});

Route::controller(LaporanController::class)->group(function () {
  Route::get('/laporan', 'laporan');
});

Route::controller(AuthController::class)->group(function () {
  Route::get('/login', 'login')->name('login');
  Route::post('/login', 'authenticate');
  Route::post('/logout', 'logout');
});
