<?php

namespace App\Http\Controllers;

use App\Helper\AntrianHelper;
use App\Helper\TextToSpeechHelper;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperatorController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function antrian()
  {
    $jenjang = ['sd', 'smp', 'sma', 'smk'];

    return view('operator.antrian', [
      'jenjang' => $jenjang
    ]);
  }

  public function antrianPerJenjang($jenjang, $status, Request $request)
  {
    if (auth()->user()->role === 'admin') return redirect('/laporan');
    if (is_null($jenjang)) return redirect('/bendahara/antrian');

    $middleware = AntrianHelper::checkRoleMiddleware($jenjang);

    if ($middleware->get('isNotRightOP')) {
      return redirect('/operator/antrian/jenjang/' . $middleware->get('OPJenjangRole') . '/belum');
    }

    $tanggal_pendaftaran =  AntrianHelper::getTanggalPendaftaran($request);

    $antrian = DB::table('antrians')
      ->where('jenjang', $jenjang)
      ->where('tanggal_pendaftaran', $tanggal_pendaftaran)
      ->where('terpanggil', $status)
      ->orderBy('nomor_antrian', 'asc')
      ->select('*')->paginate(12);

    for ($i = 0; $i < count($antrian->items()); $i++) {
      $antrian[$i]->nomor_antrian = AntrianHelper::generateNomorAntrian($antrian[$i]->kode_antrian, $antrian[$i]->nomor_antrian);
    }

    return view('operator.jenjang', [
      'semua_antrian' => $antrian,
      'tanggal_pendaftaran' => $tanggal_pendaftaran,
      'jenjang' => $jenjang,
      'status' => $status
    ]);
  }

  public function panggilNomorAntrian(Antrian $antrian)
  {
    $middleware = AntrianHelper::checkRoleMiddleware($antrian->jenjang ?? 'seragam');

    if ($middleware->get('isNotRightOP')) {
      return redirect('/operator/antrian/jenjang/' . $middleware->get('OPJenjangRole') . '/belum');
    }

    if ($antrian->kode_antrian === 'B') return redirect('/bendahara/antrian/panggil/' . $antrian->id);

    $antrian->nomor_antrian = AntrianHelper::generateNomorAntrian($antrian->kode_antrian, $antrian->nomor_antrian);
    $antrian->audio_path = asset($antrian->audio_path);

    return view('operator.panggil', [
      'antrian' => $antrian
    ]);
  }
  public function lanjutAntrian(Request $request)
  {
    $antrianSaatIni = DB::table('antrians')
      ->where('id', $request['antrian_id'])
      ->select('nomor_antrian', 'tanggal_pendaftaran', 'kode_antrian')
      ->first();

    $antrianSelanjutnya = DB::table('antrians')
      ->where('tanggal_pendaftaran', $antrianSaatIni->tanggal_pendaftaran)
      ->where('nomor_antrian', $antrianSaatIni->nomor_antrian + 1)
      ->where('kode_antrian', $antrianSaatIni->kode_antrian)
      ->select('*')
      ->first();

    if (is_null($antrianSelanjutnya)) return back()->with('antrian-mentok', 'Antrian sudah mentok');

    return redirect('/operator/antrian/panggil/' . $antrianSelanjutnya->id);
  }

  public function lewatiAntrian(Request $request)
  {
    $isAntrianUpdated = Antrian::where('id', $request['antrian_id'])->update([
      'terpanggil' => 'lewati'
    ]);

    if (!$isAntrianUpdated) return back()->with('update-error', 'Gagal melewati antrian');

    return $this->lanjutAntrian($request);
  }

  public function nomorAntrianTerpanggil(Request $request)
  {
    $isAntrianUpdated = Antrian::where('id', $request['antrian_id'])->update([
      'terpanggil' => 'sudah',
      'dipanggil_saat' => now()
    ]);

    if (!$isAntrianUpdated) return redirect('/operator/antrian/jenjang/' . $request['antrian_jenjang'] . '/belum')->with('update-error', 'Gagal melakukan yg tadi');

    return $this->lanjutAntrian($request);
  }

  public function lanjutKeSeragam(Request $request)
  {
    $data = $request->only('antrian_id', 'nomor_antrian', 'antrian_jenjang');

    $antrianSaatIni = DB::table('antrians')
      ->where('tanggal_pendaftaran', now('Asia/Jakarta')->format('Y-m-d'))
      ->where('kode_antrian', 'M')
      ->latest()->first('nomor_antrian');

    $nomorAntrianSaatIni = $antrianSaatIni->nomor_antrian ?? 0;

    $isAntrianCreated = Antrian::create([
      'nomor_antrian' => $nomorAntrianSaatIni + 1,
      'kode_antrian' => 'M',
      'antrian_jenjang' => $data['nomor_antrian'],
      'audio_path' => TextToSpeechHelper::getAudioPath($nomorAntrianSaatIni + 1, 'seragam'),
      'tanggal_pendaftaran' => now('Asia/Jakarta')->format('Y-m-d')
    ]);

    if (!$isAntrianCreated) return redirect('/operator/antrian/jenjang/' . $data['antrian_jenjang'] . '/belum')->with('create-error', 'Gagal melakukan pemindahan antrian ke seragam');

    $updateAntrianSaatIni = DB::table('antrians')->where('id', $request['antrian_id'])->update([
      'terpanggil' => 'sudah'
    ]);

    if (!$updateAntrianSaatIni) return redirect('/operator/antrian/jenjang/' . $data['antrian_jenjang'] . '/belum')->with('create-error', 'Gagal melakukan pemindahan antrian ke seragam');

    return redirect('/operator/antrian/lanjut/berhasil/' . $isAntrianCreated->id);
  }

  public function lanjutKeSeragamBerhasil(Antrian $antrian)
  {
    $antrian->nomor_antrian = AntrianHelper::generateNomorAntrian($antrian->kode_antrian, $antrian->nomor_antrian);
    $jenjang = DB::table('antrians')
      ->where('kode_antrian', strtoupper($antrian->antrian_jenjang)[0])
      ->first('jenjang');

    return view('bendahara.berhasil', [
      'antrian' => $antrian,
      'jenjang' => $jenjang->jenjang
    ]);
  }
}
