<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Helper\AntrianHelper;
use Exception;

class TextToSpeechHelper
{
  private static function generateFFMPEGInputCommand(array $paths)
  {
    $concat_n = count($paths);
    $input_command = "";

    foreach ($paths as $path) {
      $input_command .= "-i $path ";
    }

    return [
      'input_command' => $input_command,
      'concat_n' => $concat_n
    ];
  }


  private static function generateAudioFile(string $kode_antrian, string $nomor_antrian, string $loket)
  {
    $intro = public_path('storage/audio/template/greetings/intro.mp3');
    $antrian_nomor_n = public_path('storage/audio/template/greetings/antrian_nomor_n.mp3');

    $kode_antrian_audio = public_path("storage/audio/template/kode/$kode_antrian.mp3");

    $nomor_antrian_array = collect(str_split($nomor_antrian))->map(function ($nomor) {
      return public_path("storage/audio/template/number/$nomor.mp3");
    })->toArray();

    $loket_audio = public_path("storage/audio/template/loket/$loket.mp3");

    $paths = [$intro, $antrian_nomor_n, $kode_antrian_audio, ...$nomor_antrian_array, $loket_audio];

    $FFMPEGInputCommand = self::generateFFMPEGInputCommand($paths);

    $filename = Str::random() . ".mp3";
    $output_path = public_path("storage/audio/antrian/" . $filename);

    $command = "ffmpeg " . $FFMPEGInputCommand['input_command'] . "-filter_complex \"[0:a][1:a]concat=n=" . $FFMPEGInputCommand['concat_n'] . ":v=0:a=1[out]\" -map \"[out]\" $output_path";

    try {
      exec($command);

      return '/storage/audio/antrian/' . $filename;
    } catch (Exception $e) {
      dd($e->getMessage());
    }
  }

  public static function getAudioPath(int $nomor_antrian, string|null $jenjang)
  {
    $kode_antrian = AntrianHelper::getKodeAntrian($jenjang);

    $antrian = DB::table('antrians')
      ->where('nomor_antrian', $nomor_antrian)
      ->where('kode_antrian', $kode_antrian)
      ->whereNotNull('audio_path')
      ->orderBy('tanggal_pendaftaran', 'asc')
      ->first('audio_path');

    $loket = is_null($jenjang) ? 'Bendahara' : strtoupper($jenjang);
    $loket = $jenjang === 'seragam' ? 'Seragam' : $loket;

    $nomor_antrian = $nomor_antrian < 100 ? ($nomor_antrian < 10 ? '00' . $nomor_antrian : '0' . $nomor_antrian) : $nomor_antrian;

    $audio_path = $antrian->audio_path ?? self::generateAudioFile(strtoupper($kode_antrian), $nomor_antrian, strtolower($loket));

    return $audio_path;
  }
}
