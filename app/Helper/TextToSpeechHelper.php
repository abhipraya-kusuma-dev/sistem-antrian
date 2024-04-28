<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Helper\AntrianHelper;
use Exception;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use Illuminate\Support\Facades\Artisan;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;

class TextToSpeechHelper
{
  private static function generateAudioFile(string $kode_antrian, string $nomor_antrian, string $loket)
  {
    $intro = public_path('storage/audio/template/greetings/intro.mp3');
    // $outro = 'public/audio/template/greetings/outro.mp3';

    $antrian_nomor_n = public_path('storage/audio/template/greetings/antrian_nomor_n.mp3');
    $kode_antrian_audio = public_path("storage/audio/template/kode/$kode_antrian.mp3");

    $concat_n = 3;

    $command = "ffmpeg -i $intro -i $antrian_nomor_n -i $kode_antrian_audio";

    // $slowed_antrian_nomor_n = FFMpeg::fromDisk('local')
    //   ->open($antrian_nomor_n)
    //   ->addFilter(['atempo', 0.5]);

    $nomor_antrian_array = str_split($nomor_antrian);
    // $audio_storage_paths = [];

    foreach ($nomor_antrian_array as $nomor) {
      $command .= ' -i ' . public_path("storage/audio/template/number/$nomor.mp3");
      $concat_n += 1;
    }

    $loket_audio = public_path("storage/audio/template/loket/$loket.mp3");

    $command .= " -i $loket_audio";
    $concat_n += 1;

    $filename = Str::random() . ".mp3";
    $output_path = public_path("storage/audio/antrian/" . $filename);

    $command .= " -filter_complex \"[0:a][1:a]concat=n=$concat_n:v=0:a=1[out]\" -map \"[out]\" $output_path";

    try {
      exec($command);

      return '/storage/audio/antrian/' . $filename;
    } catch (Exception $e) {
      dd($e->getMessage());
    }

    // try {
    //   FFMpeg::fromDisk('local')
    //     ->open([$intro, $antrian, $nomor_n, $kode_antrian_audio, ...$audio_storage_paths, $loket_audio])
    //     ->export()
    //     ->onProgress(function ($percentage) {
    //       echo "{$percentage}% transcoded";
    //     })
    //     ->inFormat(new Mp3())
    //     ->concatWithoutTranscoding()
    //     ->save($output_path);
    //
    //   return 'storage/audio/antrian/' . $filename;
    // } catch (EncodingException $e) {
    //   dd([
    //     'command' => $e->getCommand(),
    //     'error' => $e->getErrorOutput(),
    //   ]);
    // }
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
