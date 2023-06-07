<?php

namespace App\Http\Controllers;

use App\Helper\TextToSpeechHelper;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helper\AntrianHelper;

class SeragamController extends Controller
{
  public function konfirmasiPendaftaran()
  {
    $antrian = DB::table('antrians')
      ->where('kode_antrian', 'M')
      ->where('tanggal_pendaftaran', now('Asia/Jakarta')->format('Y-m-d'))
      ->latest()->first('nomor_antrian');

    $nomorAntrianSebelumnya = $antrian->nomor_antrian ?? 0;

    return view('seragam.konfirmasi', [
      'nomorAntrianSaatIni' => $nomorAntrianSebelumnya + 1
    ]);
  }

  public function buatAntrianBaru(Request $request)
  {
    $request['nomor_antrian'] = (int) $request['nomor_antrian'];

    $data = $request->only('nomor_antrian');
    $data['audio_path'] = TextToSpeechHelper::getAudioPath($data['nomor_antrian'], 'seragam', $request);

    if (is_null($data['audio_path'])) return redirect('/seragam/konfirmasi')->with('create-error', 'Gagal membuat antrian baru');

    $isAntrianCreated = Antrian::create([
      'nomor_antrian' => $data['nomor_antrian'],
      'jenjang' => NULL,
      'kode_antrian' => AntrianHelper::getKodeAntrian('seragam'),
      'audio_path' => $data['audio_path'],
      'tanggal_pendaftaran' => now('Asia/Jakarta')->format('Y-m-d')
    ]);

    if (!$isAntrianCreated) return redirect('/seragam/konfirmasi')->with('create-error', 'Gagal membuat antrian baru');

    return redirect('/seragam/daftar/berhasil/' . $isAntrianCreated->id);
  }

  public function daftarAntrianBerhasil(Antrian $antrian)
  {
    $antrian->nomor_antrian = AntrianHelper::generateNomorAntrian($antrian->kode_antrian, $antrian->nomor_antrian);
    $antrian->jenjang = 'Seragam';

    return view('seragam.berhasil', [
      'antrian' => $antrian
    ]);
  }
}
