<?php

namespace App\Http\Controllers;

use App\Helper\AntrianHelper;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helper\TextToSpeechHelper;

class BendaharaController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function antrianBendahara($status, Request $request)
  {
    $tanggal_pendaftaran =  AntrianHelper::getTanggalPendaftaran($request);

    $antrian = DB::table('antrians')
      ->where('tanggal_pendaftaran', $tanggal_pendaftaran)
      ->where('kode_antrian', 'B')
      ->where('terpanggil', $status)
      ->orderBy('nomor_antrian', 'asc')
      ->select('*')->get();

    for ($i = 0; $i < count($antrian); $i++) {
      $antrian[$i]->nomor_antrian = AntrianHelper::generateNomorAntrian($antrian[$i]->kode_antrian, $antrian[$i]->nomor_antrian);
    }

    return view('bendahara.index', [
      'semua_antrian' => $antrian,
      'tanggal_pendaftaran' => $tanggal_pendaftaran,
      'status' => $status
    ]);
  }

  public function konfirmasiAntrianBaru()
  {
    $antrianPerJenjangTerbaru = DB::table('antrians')
      ->where('kode_antrian', 'B')
      ->where('tanggal_pendaftaran', now('Asia/Jakarta')->format('Y-m-d'))
      ->orderBy('nomor_antrian', 'desc')
      ->first('nomor_antrian');

    $nomorAntrianSebelumnya = $antrianPerJenjangTerbaru->nomor_antrian ?? 0;

    return view('bendahara.konfirmasi', [
      'nomorAntrianSaatIni' => $nomorAntrianSebelumnya + 1,
    ]);
  }

  public function buatAntrianBaru(Request $request)
  {
    $request['nomor_antrian'] = (int) $request['nomor_antrian'];

    $data = $request->validate([
      'nomor_antrian' => ['required'],
    ]);

    $data['jenjang'] = NULL;
    $data['audio_path'] = TextToSpeechHelper::getAudioPath($data['nomor_antrian'], $data['jenjang'], $request);

    if(is_null($data['audio_path'])) return redirect('/antrian/daftar')->with('create-error', 'Gagal membuat antrian baru');

    $isAntrianCreated = Antrian::create([
      'nomor_antrian' => $data['nomor_antrian'],
      'kode_antrian' => AntrianHelper::getKodeAntrian($data['jenjang']),
      'audio_path' => $data['audio_path'],
      'tanggal_pendaftaran' => now('Asia/Jakarta')->format('Y-m-d')
    ]);

    if (!$isAntrianCreated) return redirect('/antrian/daftar')->with('create-error', 'Gagal membuat antrian baru');

    return redirect('/antrian/daftar')->with('create-success', 'Berhasil membuat antrian baru');
  }

  public function panggilNomorAntrian(Antrian $antrian)
  {
    $antrian->nomor_antrian = AntrianHelper::generateNomorAntrian($antrian->kode_antrian, $antrian->nomor_antrian);
    $antrian->audio_path = asset($antrian->audio_path);

    return view('bendahara.panggil', [
      'antrian' => $antrian
    ]);
  }

  public function nomorAntrianTerpanggil(Request $request)
  {
    $isAntrianUpdated = Antrian::where('id', $request['antrian_id'])->update([
      'terpanggil' => 'sudah'
    ]);

    if (!$isAntrianUpdated) return redirect('/bendahara/antrian/belum')->with('update-error', 'Gagal melakukan yg tadi');
    return redirect('/bendahara/antrian/belum')->with('update-success', 'Berhasil melakukan yg tadi');
  }
  public function lanjutAntrian(Request $request)
  {
    $antrianSaatIni = DB::table('antrians')
      ->where('id', $request['antrian_id'])
      ->where('kode_antrian', 'B')
      ->select('nomor_antrian', 'tanggal_pendaftaran')
      ->first();

    $antrianSelanjutnya = DB::table('antrians')
      ->where('kode_antrian', 'B')
      ->where('tanggal_pendaftaran', $antrianSaatIni->tanggal_pendaftaran)
      ->where('nomor_antrian', $antrianSaatIni->nomor_antrian + 1)
      ->select('*')->first();

    if (is_null($antrianSelanjutnya)) return back()->with('antrian-mentok', 'Antrian sudah mentok');
    return redirect('/bendahara/antrian/panggil/' . $antrianSelanjutnya->id);
  }
  public function lewatiAntrian(Request $request)
  {
    $isAntrianUpdated = Antrian::where('id', $request['antrian_id'])->update([
      'terpanggil' => 'lewati'
    ]);

    if (!$isAntrianUpdated) return back()->with('update-error', 'Gagal melewati antrian');
    return $this->lanjutAntrian($request);
  }
}
