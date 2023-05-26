<?php

namespace App\Http\Controllers;

use App\Helper\AntrianHelper;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function antrian()
  {
    $jenjang = ['sd', 'smp', 'sma', 'smk'];

    return view('admin.antrian', [
      'jenjang' => $jenjang
    ]);
  }

  public function antrianPerJenjang($jenjang, Request $request)
  {
    $tanggal_pendaftaran =  AntrianHelper::getTanggalPendaftaran($request);

    $antrianTerpanggil = DB::table('antrians')
      ->where('jenjang', $jenjang)
      ->where('tanggal_pendaftaran', $tanggal_pendaftaran)
      ->where('terpanggil', 'sudah')
      ->select('*')->get();

    $antrianBelumTerpanggil = DB::table('antrians')
      ->where('jenjang', $jenjang)
      ->where('tanggal_pendaftaran', $tanggal_pendaftaran)
      ->where('terpanggil', 'belum')
      ->select('*')->get();

    return view('admin.jenjang', [
      'antrianPerJenjang' => [
        'terpanggil' => $antrianTerpanggil,
        'belumTerpanggil' => $antrianBelumTerpanggil,
      ],
      'tanggal_pendaftaran' => $tanggal_pendaftaran
    ]);
  }

  public function panggilNomorAntrian(Antrian $antrian)
  {
    return view('admin.panggil', [
      'antrian' => $antrian
    ]);
  }

  public function nomorAntrianTerpanggil(Request $request)
  {
    $isAntrianUpdated = Antrian::where('id', $request['antrian_id'])->update([
      'terpanggil' => 'sudah'
    ]);

    if (!$isAntrianUpdated) return redirect('/admin/antrian/jenjang/' . $request['antrian_jenjang'])->with('update-error', 'Gagal melakukan yg tadi');

    return redirect('/admin/antrian/jenjang/' . $request['antrian_jenjang'])->with('update-error', "Berhasil melakukan yg tadi");
  }
}
