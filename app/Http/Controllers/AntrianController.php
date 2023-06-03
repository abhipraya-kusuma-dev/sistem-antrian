<?php

namespace App\Http\Controllers;

use App\Enum\JenjangEnum;
use App\Helper\AntrianHelper;
use App\Helper\TextToSpeechHelper;
use App\Models\Antrian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;

class AntrianController extends Controller
{
  public function index()
  {
    $warna = ['#ff6384', '#36a2eb', '#FFCD56', '#c8a2eb', '#d27b41'];

    $antrian = DB::table('antrians')
      ->where('tanggal_pendaftaran', now('Asia/Jakarta')->format('Y-m-d'))
      ->orderBy('created_at', 'asc')
      ->select('*')
      ->get();

    $antrian = AntrianHelper::groupBasedOnJenjang($antrian);

    return view('antrian.index', [
      'warna' => $warna,
      'tanggal' => Carbon::now('Asia/Jakarta')->format('D, d M Y'),
      'antrian' => $antrian
    ]);
  }

  public function antrianBaru()
  {
    $jenjang = ['sd', 'smp', 'sma', 'smk'];
    $warna = ['#ff6384', '#36a2eb', '#FFCD56', '#c8a2eb', '#d27b41'];
    return view('antrian.daftar', [
      'jenjang' => $jenjang,
      'warna' => $warna
    ]);
  }

  public function konfirmasiAntrianBaru($jenjang)
  {
    if($jenjang === 'bendahara') return redirect('/bendahara/konfirmasi');

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
      'jenjang' => ['nullable', new Enum(JenjangEnum::class)]
    ]);

    $data['audio_path'] = TextToSpeechHelper::getAudioPath($data['nomor_antrian'], $data['jenjang'], $request);

    if(is_null($data['audio_path'])) return redirect('/antrian/daftar')->with('create-error', 'Gagal membuat antrian baru');

    $isAntrianCreated = Antrian::create([
      'nomor_antrian' => $data['nomor_antrian'],
      'jenjang' => $data['jenjang'],
      'kode_antrian' => AntrianHelper::getKodeAntrian($data['jenjang']),
      'audio_path' => $data['audio_path'],
      'tanggal_pendaftaran' => now('Asia/Jakarta')->format('Y-m-d')
    ]);

    if (!$isAntrianCreated) return redirect('/antrian/daftar')->with('create-error', 'Gagal membuat antrian baru');

    return redirect('/antrian/daftar')->with('create-success', 'Berhasil membuat antrian baru');
  }
}
