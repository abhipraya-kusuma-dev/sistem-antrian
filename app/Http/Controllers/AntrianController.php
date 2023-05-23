<?php

namespace App\Http\Controllers;

use App\Enum\JenjangEnum;
use App\Helper\TextToSpeechHelper;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;

class AntrianController extends Controller
{
  public function antrianBaru()
  {
    $jenjang = ['sd', 'smp', 'sma', 'smk'];
    return view('antrian.daftar', [
      'jenjang' => $jenjang
    ]);
  }

  public function konfirmasiAntrianBaru($jenjang)
  {
    $antrianPerJenjangTerbaru = DB::table('antrians')
      ->where('jenjang', $jenjang)
      ->where('tanggal_pendaftaran', now('Asia/Jakarta')->format('Y-m-d'))
      ->orderBy('nomor_antrian', 'desc')
      ->first('nomor_antrian');

    $nomorAntrianSebelumnya = $antrianPerJenjangTerbaru->nomor_antrian ?? 0;

    return view('antrian.konfirmasi', [
      'nomorAntrianSaatIni' => $nomorAntrianSebelumnya + 1,
      'jenjang' => $jenjang
    ]);
  }

  public function buatAntrianBaru(Request $request)
  {
    $request['nomor_antrian'] = (int) $request['nomor_antrian'];

    $data = $request->validate([
      'nomor_antrian' => ['required'],
      'jenjang' => ['required', new Enum(JenjangEnum::class)]
    ]);

    $data['audio_path'] = TextToSpeechHelper::getAudioPath($data['nomor_antrian'], $data['jenjang']);

    $isAntrianCreated = Antrian::create([
      'nomor_antrian' => $data['nomor_antrian'],
      'jenjang' => $data['jenjang'],
      'audio_path' => $data['audio_path'],
      'tanggal_pendaftaran' => now('Asia/Jakarta')->format('Y-m-d')
    ]);

    if(!$isAntrianCreated) return redirect('/antrian/daftar')->with('create-error', 'Gagal membuat antrian baru');

    return redirect('/antrian/daftar')->with('create-success', 'Berhasil membuat antrian baru');
  }
}
