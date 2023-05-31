<?php

namespace App\Http\Controllers;

use App\Helper\AntrianHelper;
use App\Models\Bendahara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BendaharaController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function antrianBendahara($status, Request $request)
  {
    $tanggal_pendaftaran =  AntrianHelper::getTanggalPendaftaran($request);

    $antrian = DB::table('bendaharas')
      ->where('tanggal_pendaftaran', $tanggal_pendaftaran)
      ->where('terpanggil', $status)
      ->select('*')->get();

    for ($i = 0; $i < count($antrian); $i++) {
      $antrian[$i]->nomor_antrian = AntrianHelper::getKodeAntrianBendahara($antrian[$i]->nomor_antrian);
    }

    return view('bendahara.index', [
      'semua_antrian' => $antrian,
      'tanggal_pendaftaran' => $tanggal_pendaftaran,
      'status' => $status
    ]);
  }

  public function panggilNomorAntrian(Bendahara $bendahara)
  {
    $bendahara->nomor_antrian = AntrianHelper::getKodeAntrianBendahara($bendahara->nomor_antrian);

    return view('bendahara.panggil', [
      'bendahara' => $bendahara
    ]);
  }

  public function nomorAntrianTerpanggil(Request $request)
  {
    $isAntrianUpdated = Bendahara::where('id', $request['bendahara_id'])->update([
      'terpanggil' => 'sudah'
    ]);

    if (!$isAntrianUpdated) return redirect('/bendahara/antrian')->with('update-error', 'Gagal melakukan yg tadi');
    return redirect('/bendahara/antrian')->with('update-success', 'Berhasil melakukan yg tadi');
  }
  public function lanjutAntrian(Request $request)
  {
    $antrianSaatIni = DB::table('bendaharas')
      ->where('id', $request['bendahara_id'])
      ->select('nomor_antrian', 'tanggal_pendaftaran')
      ->first();

    $antrianSelanjutnya = DB::table('bendaharas')
      ->where('tanggal_pendaftaran', $antrianSaatIni->tanggal_pendaftaran)
      ->where('nomor_antrian', $antrianSaatIni->nomor_antrian + 1)
      ->select('*')->first();

    if (is_null($antrianSelanjutnya)) return back()->with('antrian-mentok', 'Antrian sudah mentok');

    return redirect('/bendahara/antrian/panggil/' . $antrianSelanjutnya->id);
  }
  public function lewatiAntrian(Request $request)
  {
    $isAntrianUpdated = Bendahara::where('id', $request['bendahara_id'])->update([
      'terpanggil' => 'lewati'
    ]);

    if (!$isAntrianUpdated) return back()->with('update-error', 'Gagal melewati antrian');

    return $this->lanjutAntrian($request);
  }
}
