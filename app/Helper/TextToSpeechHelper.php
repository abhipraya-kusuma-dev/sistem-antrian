<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Helper\AntrianHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

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
      'voice' => 'id-ID-Standard-A',
      'globalSpeed' => '70%'
    ]);

    $data = $response->json();

    return $data['transcriptionId'];
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

    if (!empty($data['audioUrl'])) {
      Cookie::forget('transcriptionId');
      return $data['audioUrl'];
    }

    Cookie::make('transcriptionId', $transcriptionId, 2, httpOnly: true, secure: true);
    return '';
  }

  private static function transformTextToSpeech(string $text, Request $request)
  {
    $transcriptionId = $request->cookie('transcriptionId') ?? self::getTranscriptionID($text);
    $audioUrl = self::getDownloadUrl($transcriptionId);

    if(empty($audioUrl)) return NULL;

    $fileName = Str::random() . '.mp3';
    $pathToFile = storage_path('app/public/audio/' . $fileName);

    $audio = file_get_contents($audioUrl);
    file_put_contents($pathToFile, $audio);

    return 'storage/audio/' . $fileName;
  }

  public static function getAudioPath(int $nomor_antrian, string|null $jenjang, Request $request)
  {
    $kode_antrian = AntrianHelper::getKodeAntrian($jenjang);
    $kode_nomor_antrian = AntrianHelper::generateNomorAntrian($kode_antrian, $nomor_antrian);

    $antrian = DB::table('antrians')
      ->where('nomor_antrian', $nomor_antrian)
      ->where('kode_antrian', $kode_antrian)
      ->orderBy('tanggal_pendaftaran', 'asc')
      ->first('audio_path');

    $loket = is_null($jenjang) ? 'Bendahara' : strtoupper($jenjang);
    $audio_path = $antrian->audio_path ?? self::transformTextToSpeech('Antrian nomor ' . $kode_nomor_antrian . ' menuju loket ' . $loket, $request);

    return $audio_path;
  }
}
