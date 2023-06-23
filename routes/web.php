<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\SeragamController;
use App\Models\Antrian;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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
  Route::get('/antrian/terbaru', 'getNewestAntrianData')->name('new_antrian');

  Route::get('/antrian/daftar/berhasil/{antrian:id}', 'daftarAntrianBerhasil');
});

Route::controller(OperatorController::class)->group(function () {
  // Monitoring Antrian (admin)
  // Route::get('/operator/antrian', 'antrian');
  Route::get('/operator/antrian/jenjang/{jenjang}/{status}', 'antrianPerJenjang');
  Route::get('/operator/antrian/panggil/{antrian:id}', 'panggilNomorAntrian');
  Route::get('/operator/antrian/lanjut/berhasil/{antrian:id}', 'lanjutKeBendaharaBerhasil');

  Route::post('/operator/antrian/lanjut/', 'lanjutAntrian');
  Route::post('/operator/antrian/lewati/', 'lewatiAntrian');
  Route::post('/operator/antrian/lanjut/bendahara', 'lanjutKeBendahara');

  Route::put('/operator/antrian/terpanggil', 'nomorAntrianTerpanggil');
});

Route::controller(BendaharaController::class)->group(function () {
  Route::get('/bendahara/antrian/{status}', 'antrianBendahara');
  Route::get('/bendahara/antrian/panggil/{antrian:id}', 'panggilNomorAntrian');
  Route::get('/bendahara/konfirmasi', 'konfirmasiAntrianBaru');
  Route::get('/bendahara/antrian/lanjut/berhasil/{antrian:id}', 'lanjutKeSeragamBerhasil');

  Route::put('/bendahara/antrian/terpanggil', 'nomorAntrianTerpanggil');

  Route::post('/bendahara/antrian/lanjut/', 'lanjutAntrian');
  Route::post('/bendahara/antrian/lanjut/seragam', 'lanjutKeSeragam');
  Route::post('/bendahara/daftar/proses', 'buatAntrianBaru');
  Route::post('/bendahara/antrian/lewati/', 'lewatiAntrian');
});

Route::controller(SeragamController::class)->group(function () {
  Route::get('/seragam', 'display');
  Route::get('/seragam/antrian/{status}', 'antrianSeragam');
  Route::get('/seragam/antrian/panggil/{antrian:id}', 'panggilNomorAntrian');

  Route::get('/seragam/konfirmasi', 'konfirmasiPendaftaran');
  Route::get('/seragam/daftar/berhasil/{antrian:id}', 'daftarAntrianBerhasil');
  Route::get('/seragam/terbaru', 'getNewestAntrianData')->name('list_terpanggil');

  Route::post('/seragam/daftar/proses', 'buatAntrianBaru');
  Route::post('/seragam/antrian/lanjut/', 'lanjutAntrian');
  Route::post('/seragam/antrian/lewati/', 'lewatiAntrian');
  Route::put('/seragam/antrian/terpanggil', 'nomorAntrianTerpanggil');
});

Route::controller(LaporanController::class)->group(function () {
  Route::get('/laporan', 'laporan');
  Route::post('/laporan/excel', 'saveToExcel');
});

Route::controller(AuthController::class)->group(function () {
  Route::get('/login', 'login')->name('login');
  Route::post('/login', 'authenticate');
  Route::post('/logout', 'logout');
});

Route::get('/update', function () {
  $antrian = DB::table('antrians')
    // ->where('tanggal_pendaftaran', now('Asia/Jakarta')->format('Y-m-d'))
    ->whereNot('terpanggil', 'belum')
    ->update([
      'terpanggil' => 'belum'
    ]);

  return $antrian;
});

Route::get('/test-create', function () {
  $antrianLama = DB::table('antrians')->where('kode_antrian', 'K')->orderBy('created_at', 'asc')->first('audio_path');

  $isAntrianCreated = Antrian::create([
    'nomor_antrian' => 1,
    'jenjang' => 'smk',
    'kode_antrian' => 'K',
    'audio_path' => $antrianLama->audio_path,
    'tanggal_pendaftaran' => now('Asia/Jakarta')->subDays(3)->format('Y-m-d')
  ]);

  return $isAntrianCreated;
});

Route::get('/test-paginate', function () {

  $data = DB::table('antrians')
    ->where('tanggal_pendaftaran', now('Asia/Jakarta')->format('Y-m-d'))
    ->where('terpanggil', 'sudah')
    ->select()->paginate(10);

  return view('test.paginate', [
    'data' => $data
  ]);
});
