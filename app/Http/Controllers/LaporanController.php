<?php

namespace App\Http\Controllers;

use App\Helper\AntrianHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  private function getMappedLaporanData(Collection $laporan)
  {
    $jenjang = ['sd', 'smp', 'sma', 'smk'];
    $pointer = 0;

    $tmp = [
      'sd' => 0,
      'smp' => 0,
      'sma' => 0,
      'smk' => 0
    ];

    while ($pointer < count($jenjang)) {
      foreach ($laporan as $data) {
        if ($jenjang[$pointer] === $data->jenjang) {
          $tmp[$data->jenjang] = $data->nomor_antrian;
        }
      }

      $pointer += 1;
    }

    return $tmp;
  }

  public function laporan(Request $request)
  {
    $tanggal_pendaftaran = AntrianHelper::getTanggalPendaftaran($request);
    $laporanAntrian = DB::table('antrians')
      ->where('tanggal_pendaftaran', $tanggal_pendaftaran)
      ->select('*')->get();

    $mappedLaporan = $this->getMappedLaporanData($laporanAntrian);

    $laporanBendahara = DB::table('bendaharas')
      ->where('tanggal_pendaftaran', $tanggal_pendaftaran)
      ->orderBy('nomor_antrian', 'desc')->first('nomor_antrian');

    $mappedLaporan['bendahara'] = $laporanBendahara->nomor_antrian ?? 0;

    return view('laporan.index', [
      'data' => $mappedLaporan,
      'tanggal_pendaftaran' => $tanggal_pendaftaran
    ]);
  }
}
