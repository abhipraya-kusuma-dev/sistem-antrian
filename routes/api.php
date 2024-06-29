<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helper\AntrianHelper;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/antrian', function(Request $request) {
    $tanggal_pendaftaran =  AntrianHelper::getTanggalPendaftaran($request);

    $antrian = DB::table('antrians')
      ->where('jenjang', $jenjang)
      ->where('tanggal_pendaftaran', $tanggal_pendaftaran)
      ->where('terpanggil', $status)
      ->orderBy('nomor_antrian', 'asc')
      ->select('*')->paginate(12);

    for ($i = 0; $i < count($antrian->items()); $i++) {
      $antrian[$i]->nomor_antrian = AntrianHelper::generateNomorAntrian($antrian[$i]->kode_antrian, $antrian[$i]->nomor_antrian);
    }

    return response()->json([
        'semua_antrian' => $antrian
    ]);
});