<?php

use App\Http\Controllers\AntrianController;
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

Route::controller(AntrianController::class)->group(function() {
  Route::get('/antrian', 'index');
  Route::get('/antrian/daftar', 'antrianBaru');
  Route::get('/antrian/daftar/konfirmasi/{jenjang}', 'antrianBaruKonfirmasi');
  Route::get('/antrian/jenjang/{jenjang}', 'antrianPerJenjang');

  Route::post('/antrian/daftar/proses', 'buatAntrianBaru');
});
