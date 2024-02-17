<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Helper\AntrianHelper;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Audio\Mp3;

class TextToSpeechHelper
{
  private static function generateAudioFile(string $kode_antrian, string $nomor_antrian, string $loket)
  {
    $intro = 'public/audio/template/greetings/intro.mp3';
    $outro = 'public/audio/template/greetings/outro.mp3';

    $antrian_nomor_n = 'public/audio/template/greetings/antrian_nomor_n.mp3';
    $kode_antrian_audio = "public/audio/template/kode/$kode_antrian.mp3";

    // $slowed_antrian_nomor_n = FFMpeg::fromDisk('local')
    //   ->open($antrian_nomor_n)
    //   ->addFilter(['atempo', 0.5]);

    $nomor_antrian_array = str_split($nomor_antrian);
    $audio_storage_paths = [];

    foreach ($nomor_antrian_array as $nomor) {
      $audio_storage_paths[] = "public/audio/template/number/$nomor.mp3";
    }

    $loket_audio = "public/audio/template/loket/$loket.mp3";

    $filename = Str::random() . ".mp3";

    $output_path = "public/audio/antrian/" . $filename;

    FFMpeg::fromDisk('local')
      ->open([$intro, $antrian_nomor_n, $kode_antrian_audio, ...$audio_storage_paths, $loket_audio, $outro])
      ->export()
      ->inFormat(new Mp3)
      ->concatWithTranscoding(false, true)
      ->save($output_path);

    return 'storage/audio/antrian/' . $filename;
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
