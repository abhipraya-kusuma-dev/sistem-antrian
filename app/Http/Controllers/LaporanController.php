<?php

namespace App\Http\Controllers;

use App\Exports\AntriansExport;
use App\Helper\AntrianHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function laporan(Request $request)
  {
    $tanggal_pendaftaran = AntrianHelper::getTanggalPendaftaran($request);
    $laporanAntrian = DB::table('antrians')
      ->where('tanggal_pendaftaran', $tanggal_pendaftaran)
      ->select('*')->get();

    $mappedLaporan = AntrianHelper::groupBasedOnJenjang($laporanAntrian);

    foreach ($mappedLaporan as $key => $value) {
      $mappedLaporan[$key] = count($value);
    }

    return view('laporan.index', [
      'data' => $mappedLaporan,
      'tanggal_pendaftaran' => $tanggal_pendaftaran
    ]);
  }

  public function saveToExcel(Request $request)
  {
    $tanggal_pendaftaran = AntrianHelper::getTanggalPendaftaran($request);
    $export = new AntriansExport($tanggal_pendaftaran);

    return Excel::download($export, 'test.xlsx');
  }
}
