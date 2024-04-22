<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use App\Helper\AntrianHelper;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;

class TextToSpeechHelper
{
  private static function generateAudioFile(string $kode_antrian, string $nomor_antrian, string $loket)
  {
    $intro = 'audio/template/greetings/intro.mp3';
    // $outro = 'public/audio/template/greetings/outro.mp3';

    $antrian_nomor_n = 'audio/template/greetings/antrian_nomor_n.mp3';
    $kode_antrian_audio = "audio/template/kode/$kode_antrian.mp3";

    // $slowed_antrian_nomor_n = FFMpeg::fromDisk('local')
    //   ->open($antrian_nomor_n)
    //   ->addFilter(['atempo', 0.5]);

    $nomor_antrian_array = str_split($nomor_antrian);
    $audio_storage_paths = [];

    foreach ($nomor_antrian_array as $nomor) {
      $audio_storage_paths[] = "audio/template/number/$nomor.mp3";
    }

    $loket_audio = "audio/template/loket/$loket.mp3";

    $filename = "$kode_antrian$nomor_antrian-" . now()->format('Y-m-d') . ".mp3";

    $output_path = "audio/antrian/" . $filename;

    try {
      FFMpeg::fromDisk('public')
        ->open([$intro, $antrian_nomor_n, $kode_antrian_audio, ...$audio_storage_paths, $loket_audio])
        ->export()
        ->toDisk('public')
        ->inFormat(new Mp3)
        ->concatWithoutTranscoding()
        ->save($output_path);

      return 'storage/audio/antrian/' . $filename;
    } catch (EncodingException $e) {
      dd([
        'command' => $e->getCommand(),
        'error' => $e->getErrorOutput(),
      ]);
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
