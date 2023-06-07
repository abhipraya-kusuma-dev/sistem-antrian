<?php

namespace App\Helper;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AntrianHelper
{
  public static function getKodeAntrian(string|null $jenjang): string
  {
    if (is_null($jenjang)) return 'B';
    if($jenjang === 'seragam') return 'M';

    return strtoupper(substr($jenjang, -1));
  }

  public static function generateNomorAntrian(string $kode_antrian, int $nomor_antrian)
  {
    $zeroPrefixAntrian = str_pad($nomor_antrian, 3, '0', STR_PAD_LEFT);

    return $kode_antrian . $zeroPrefixAntrian;
  }

  public static function getTanggalPendaftaran(Request $request)
  {
    if ($request['tanggal_pendaftaran']) return Carbon::parse($request['tanggal_pendaftaran'], 'Asia/Jakarta')->format('Y-m-d');
    return now('Asia/Jakarta')->format('Y-m-d');
  }

  public static function groupBasedOnJenjang(Collection $antrian)
  {
    $arr = [
      'sd' => [],
      'smp' => [],
      'sma' => [],
      'smk' => [],
      'bendahara' => [],
    ];

    for ($i = 0; $i < $antrian->count(); $i++) {
      if (is_null($antrian[$i]->jenjang) && $antrian[$i]->kode_antrian === 'B') {
        $arr['bendahara'][] = $antrian[$i];
        continue;
      }

      $arr[$antrian[$i]->jenjang][] = $antrian[$i];
    }

    return $arr;
  }
}
