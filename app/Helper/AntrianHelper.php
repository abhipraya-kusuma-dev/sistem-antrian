<?php

namespace App\Helper;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpParser\Node\Stmt\Foreach_;

class AntrianHelper
{
  public static function generateNomorAntrian(string $jenjang, int $nomor_antrian)
  {
    $endCharacterJenjang = strtoupper(substr($jenjang, -1));
    $zeroPrefixAntrian = str_pad($nomor_antrian, 3, '0', STR_PAD_LEFT);

    return $endCharacterJenjang . $zeroPrefixAntrian;
  }

  public static function getKodeAntrianBendahara(int $nomor_antrian)
  {
    return 'B' . str_pad($nomor_antrian, 3, '0', STR_PAD_LEFT);
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
    ];

    foreach ($arr as $key => $value) {

      for ($i = 0; $i < $antrian->count(); $i++) {
        if ($antrian[$i]->jenjang === $key) {
          $arr[$key][] = $antrian[$i];
        }
      }

    }

    return $arr;
  }
}
