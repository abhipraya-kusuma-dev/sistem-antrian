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
    if ($jenjang === 'seragam') return 'M';

    return strtoupper(substr($jenjang, -1));
  }

  public static function checkRoleMiddleware(string $jenjang)
  {
    $getJenjangFromRole = strtolower(substr(auth()->user()->role, 3));

    return collect([
      'isNotRightOP' => $getJenjangFromRole != $jenjang,
      'OPJenjangRole' => $getJenjangFromRole
    ]);
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
      // 'bendahara' => [],
      'seragam' => []
    ];

    for ($i = 0; $i < $antrian->count(); $i++) {
      /*
      if (is_null($antrian[$i]->jenjang) && $antrian[$i]->kode_antrian === 'B') {
        $arr['bendahara'][] = $antrian[$i];
        continue;
      }
       */

      if (is_null($antrian[$i]->jenjang) && $antrian[$i]->kode_antrian === 'M') {
        $arr['seragam'][] = $antrian[$i];
        continue;
      }

      $arr[$antrian[$i]->jenjang][] = $antrian[$i];
    }

    return $arr;
  }

  public static function groupBasedOnTanggalPendaftaran(Collection $antrian)
  {
    // Get tanggal_pendaftaran as a key of the complete array soon
    $tanggal_pendaftaran = [];

    for ($i = 0; $i < $antrian->count(); $i++) {
      $tanggal_pendaftaran[] = $antrian[$i]->tanggal_pendaftaran;
    }
    $tanggal_pendaftaran = [...array_unique($tanggal_pendaftaran)];

    // Push the antrian collection based on tanggal_pendaftaran keys
    $arr = [];
    $pointer = 0;

    while ($pointer < count($tanggal_pendaftaran)) {
      $key = $tanggal_pendaftaran[$pointer];

      for ($i = 0; $i < $antrian->count(); $i++) {
        if ($antrian[$i]->tanggal_pendaftaran === $tanggal_pendaftaran[$pointer]) {

          $arr[$key][] = $antrian[$i];
        }
      }

      // Grouping again the antrian collection based on jenjang
      $arr[$key] = self::groupBasedOnJenjang(collect($arr[$key]));

      $pointer += 1;
    }

    return $arr;
  }
}
