<?php

namespace App\Http\Controllers;

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

  public function antrianPerJenjang($jenjang)
  {
    $antrianTerpanggil = DB::table('antrians')
      ->where('jenjang', $jenjang)
      ->where('terpanggil', 'sudah')
      ->select('*')->get();

    $antrianBelumTerpanggil = DB::table('antrians')
      ->where('jenjang', $jenjang)
      ->where('terpanggil', 'belum')
      ->select('*')->get();

    return view('admin.jenjang', [
      'antrianPerJenjang' => [
        'terpanggil' => $antrianTerpanggil,
        'belumTerpanggil' => $antrianBelumTerpanggil,
      ]
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

    if (!$isAntrianUpdated) return redirect('/admin/antrian')->with('update-error', 'Gagal melakukan yg tadi');

    return redirect('/admin/antrian')->with('update-error', "Berhasil melakukan yg tadi");
  }
}
