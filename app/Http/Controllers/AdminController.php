<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
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

    return view('admin.antrian', [
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

  public function laporan()
  {
    return view('laporan.index', [
      'laporan' => ''
    ]);
  }
}
