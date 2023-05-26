<?php

namespace App\Http\Controllers;

use App\Helper\TextToSpeechHelper;
use App\Models\Antrian;
use App\Models\Bendahara;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperatorController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  private function getTanggalPendaftaran(Request $request)
  {
    if ($request['tanggal_pendaftaran']) return Carbon::parse($request['tanggal_pendaftaran'], 'Asia/Jakarta')->format('Y-m-d');
    return now('Asia/Jakarta')->format('Y-m-d');
  }

  public function antrian()
  {
    $jenjang = ['sd', 'smp', 'sma', 'smk'];

    return view('operator.antrian', [
      'jenjang' => $jenjang
    ]);
  }

  public function antrianPerJenjang($jenjang, Request $request)
  {
    $tanggal_pendaftaran =  $this->getTanggalPendaftaran($request);

    $antrianTerpanggil = DB::table('antrians')
      ->where('jenjang', $jenjang)
      ->where('tanggal_pendaftaran', $tanggal_pendaftaran)
      ->where('terpanggil', 'sudah')
      ->select('*')->get();

    for ($i = 0; $i < count($antrianTerpanggil); $i++) {
      $antrianTerpanggil[$i]->nomor_antrian = TextToSpeechHelper::generateNomorAntrian($antrianTerpanggil[$i]->jenjang, $antrianTerpanggil[$i]->nomor_antrian);
    }

    $antrianBelumTerpanggil = DB::table('antrians')
      ->where('jenjang', $jenjang)
      ->where('tanggal_pendaftaran', $tanggal_pendaftaran)
      ->where('terpanggil', 'belum')
      ->select('*')->get();

    for ($i = 0; $i < count($antrianBelumTerpanggil); $i++) {
      $antrianBelumTerpanggil[$i]->nomor_antrian = TextToSpeechHelper::generateNomorAntrian($antrianBelumTerpanggil[$i]->jenjang, $antrianBelumTerpanggil[$i]->nomor_antrian);
    }

    $antrianTerlewati = DB::table('antrians')
      ->where('jenjang', $jenjang)
      ->where('tanggal_pendaftaran', $tanggal_pendaftaran)
      ->where('terpanggil', 'lewati')
      ->select('*')->get();

    for ($i = 0; $i < count($antrianTerlewati); $i++) {
      $antrianTerlewati[$i]->nomor_antrian = TextToSpeechHelper::generateNomorAntrian($antrianTerlewati[$i]->jenjang, $antrianTerlewati[$i]->nomor_antrian);
    }

    return view('operator.jenjang', [
      'antrianPerJenjang' => [
        'terpanggil' => $antrianTerpanggil,
        'belumTerpanggil' => $antrianBelumTerpanggil,
        'terlewati' => $antrianTerlewati,
      ],
      'tanggal_pendaftaran' => $tanggal_pendaftaran
    ]);
  }

  public function panggilNomorAntrian(Antrian $antrian)
  {
    $antrian->nomor_antrian = TextToSpeechHelper::generateNomorAntrian($antrian->jenjang, $antrian->nomor_antrian);

    return view('operator.panggil', [
      'antrian' => $antrian
    ]);
  }
  public function lanjutAntrian(Request $request)
  {
    $antrianSaatIni = DB::table('antrians')->where('id', $request['antrian_id'])->select('nomor_antrian', 'tanggal_pendaftaran', 'jenjang')->first();

    $antrianSelanjutnya = DB::table('antrians')
      ->where('tanggal_pendaftaran', $antrianSaatIni->tanggal_pendaftaran)
      ->where('nomor_antrian', $antrianSaatIni->nomor_antrian + 1)
      ->where('jenjang', $antrianSaatIni->jenjang)
      ->select('*')->first();

    if (is_null($antrianSelanjutnya)) return back()->with('antrian-mentok', 'Antrian sudah mentok');

    return redirect('/operator/antrian/panggil/' . $antrianSelanjutnya->id);
  }

  public function nomorAntrianTerpanggil(Request $request)
  {
    $isAntrianUpdated = Antrian::where('id', $request['antrian_id'])->update([
      'terpanggil' => 'sudah'
    ]);

    if (!$isAntrianUpdated) return redirect('/operator/antrian/jenjang/' . $request['antrian_jenjang'])->with('update-error', 'Gagal melakukan yg tadi');

    return redirect('/operator/antrian/jenjang/' . $request['antrian_jenjang'])->with('update-error', "Berhasil melakukan yg tadi");
  }

  public function lanjutKeBendahara(Request $request)
  {
    $isAntrianUpdated = Antrian::where('id', $request['antrian_id'])->update([
      'terpanggil' => 'sudah'
    ]);

    if (!$isAntrianUpdated) return redirect('/operator/antrian/jenjang/' . $request['antrian_jenjang'])->with('update-error', 'Gagal melakukan pemindahan antrian ke bendahara');

    $antrianSaatIni = DB::table('bendaharas')
      ->orderBy('created_at', 'desc')->first('nomor_antrian');

    $nomorAntrianSaatIni = $antrianSaatIni->nomor_antrian ?? 0;

    $isCreated = Bendahara::create([
      'nomor_antrian' => $nomorAntrianSaatIni + 1,
      'tanggal_pendaftaran' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
      'audio_path' => TextToSpeechHelper::getAudioPathBendahara($nomorAntrianSaatIni + 1)
    ]);

    if (!$isCreated) return redirect('/operator/antrian')->with('create-error', 'Gagal melakukan pemindahan antrian ke bendahara');

    return redirect('/operator/antrian')->with('create-success', 'Antrian dilanjut ke bendahara dengan nomor ' . $isCreated->nomor_antrian);
  }
}
