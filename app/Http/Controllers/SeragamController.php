<?php

namespace App\Http\Controllers;

use App\Helper\TextToSpeechHelper;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helper\AntrianHelper;

class SeragamController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth', 'is_seragam'])->except('display');
  }

  public function display()
  {
    $seragam = DB::table('antrians')
      ->where('kode_antrian', 'M')
      ->where('tanggal_pendaftaran', now('Asia/Jakarta')->format('Y-m-d'))
      ->where('terpanggil', 'belum')
      ->orderBy('created_at', 'asc')
      ->select('*')->first();

    if (!is_null($seragam)) {
      $seragam->nomor_antrian = AntrianHelper::generateNomorAntrian($seragam->kode_antrian, $seragam->nomor_antrian);
    }

    return view('seragam.index', [
      'seragam' => $seragam
    ]);
  }

  public function antrianSeragam($status, Request $request)
  {
    $tanggal_pendaftaran =  AntrianHelper::getTanggalPendaftaran($request);

    $data = DB::table('antrians')
      ->where('tanggal_pendaftaran', $tanggal_pendaftaran)
      ->where('kode_antrian', 'M')
      ->where('terpanggil', $status)
      ->orderBy('nomor_antrian', 'asc')
      ->select('*')->get();

    foreach ($data as $antrian) {
      $antrian->nomor_antrian = AntrianHelper::generateNomorAntrian($antrian->kode_antrian, $antrian->nomor_antrian);
    }

    return view('seragam.list', [
      'data' => $data,
      'tanggal_pendaftaran' => $tanggal_pendaftaran,
      'status' => $status
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

    return redirect('/seragam/antrian/panggil/' . $antrianSelanjutnya->id);
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
      'terpanggil' => 'sudah'
    ]);

    if (!$isAntrianUpdated) return redirect('/seragam/antrian/belum')->with('update-error', 'Gagal melakukan yg tadi');

    return redirect('/seragam/antrian/belum')->with('update-error', "Berhasil melakukan yg tadi");
  }

  public function panggilNomorAntrian(Antrian $antrian)
  {
    $antrian->nomor_antrian = AntrianHelper::generateNomorAntrian($antrian->kode_antrian, $antrian->nomor_antrian);
    $antrian->audio_path = asset($antrian->audio_path);

    return view('seragam.panggil', [
      'antrian' => $antrian
    ]);
  }

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
