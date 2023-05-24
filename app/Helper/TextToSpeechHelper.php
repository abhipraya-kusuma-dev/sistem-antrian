<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TextToSpeechHelper
{
  private static function getTranscriptionID(string $text)
  {
    $response = Http::withHeaders([
      'AUTHORIZATION' => env('AUTHORIZATION'),
      'X-USER-ID' => env('USER_ID'),
      'Accept' => 'application/json',
      'Content-Type' => 'application/json',
    ])->post('https://play.ht/api/v1/convert', [
      'content' => [$text],
      'voice' => 'id-ID-Standard-A'
    ]);

    $data = $response->json();

    return $data['transcriptionId'];
  }

  public static function generateNomorAntrian(string $jenjang, int $nomor_antrian)
  {
    $endCharacterJenjang = strtoupper(substr($jenjang, -1));
    $zeroPrefixAntrian = str_pad($nomor_antrian, 3, '0', STR_PAD_LEFT);

    return $endCharacterJenjang . $zeroPrefixAntrian;
  }

  private static function getDownloadUrl(string $transcriptionId)
  {
    $response = Http::withHeaders([
      'AUTHORIZATION' => env('AUTHORIZATION'),
      'X-USER-ID' => env('USER_ID'),
      'Accept' => 'application/json',
      'Content-Type' => 'application/json',
    ])->get('https://play.ht/api/v1/articleStatus?transcriptionId=' . $transcriptionId);

    $data = $response->json();

    return $data['audioUrl'];
  }

  private static function transformTextToSpeech(string $text)
  {
    $transcriptionId = self::getTranscriptionID($text);
    $audioUrl = self::getDownloadUrl($transcriptionId);

    $fileName = Str::random() . '.mp3';
    $pathToFile = storage_path('app/public/audio/' . $fileName);

    $audio = file_get_contents($audioUrl);
    file_put_contents($pathToFile, $audio);

    return 'storage/audio/' . $fileName;
  }

  public static function getAudioPath(int $nomor_antrian, string $jenjang)
  {
    $antrian = DB::table('antrians')
      ->where('nomor_antrian', $nomor_antrian)
      ->where('jenjang', $jenjang)
      ->orderBy('tanggal_pendaftaran', 'asc')
      ->first('audio_path');

    $nomor_antrian = self::generateNomorAntrian($jenjang, $nomor_antrian);

    $audio_path = $antrian->audio_path ?? self::transformTextToSpeech('Antrian nomor ' . $nomor_antrian . ' menuju loket ' . $jenjang);

    return $audio_path;
  }

  public static function getKodeAntrianBendahara(int $nomor_antrian)
  {
    return 'B' . str_pad($nomor_antrian, 3, '0', STR_PAD_LEFT);
  }

  public static function getAudioPathBendahara(int $nomor_antrian)
  {
    $antrian = DB::table('bendaharas')
      ->where('nomor_antrian', $nomor_antrian)
      ->orderBy('tanggal_pendaftaran', 'asc')
      ->first('audio_path');

    $kode_antrian = self::getKodeAntrianBendahara($nomor_antrian);
    $audio_path = $antrian->audio_path ?? self::transformTextToSpeech('Antrian nomor ' . $kode_antrian . ' menuju loket bendahara');

    return $audio_path;
  }
}
