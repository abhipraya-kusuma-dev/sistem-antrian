<?php

namespace App\Http\Controllers;

use App\Exports\AntriansExport;
use App\Helper\AntrianHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function getTanggalPendaftaranPerMinggu(Request $request)
  {
    if ($request['tanggal_pendaftaran_start'] && $request['tanggal_pendaftaran_end']) {
      $start = Carbon::parse($request['tanggal_pendaftaran_start'])->format('Y-m-d');
      $end = Carbon::parse($request['tanggal_pendaftaran_end'])->format('Y-m-d');

      return [$start, $end];
    }

    $start = Carbon::now('Asia/Jakarta')->subWeek()->format('Y-m-d');
    $end = Carbon::now('Asia/Jakarta')->format('Y-m-d');

    return [$start, $end];
  }

  public function laporan(Request $request)
  {
    $tanggal_pendaftaran = $this->getTanggalPendaftaranPerMinggu($request);

    $laporanAntrian = DB::table('antrians')
      ->whereBetween('tanggal_pendaftaran', [$tanggal_pendaftaran[0], $tanggal_pendaftaran[1]])
      ->select('*')->get();

    $mappedLaporan = AntrianHelper::groupBasedOnJenjang($laporanAntrian);

    foreach ($mappedLaporan as $key => $value) {
      $mappedLaporan[$key] = count($value);
    }

    return view('laporan.index', [
      'data' => $mappedLaporan,
      'tanggal_pendaftaran_start' => $tanggal_pendaftaran[0],
      'tanggal_pendaftaran_end' => $tanggal_pendaftaran[1],
    ]);
  }

  public function saveToExcel(Request $request)
  {
    $tanggal_pendaftaran = $this->getTanggalPendaftaranPerMinggu($request);
    $export = new AntriansExport($tanggal_pendaftaran);

    return Excel::download($export, 'test.xlsx');
  }
}
