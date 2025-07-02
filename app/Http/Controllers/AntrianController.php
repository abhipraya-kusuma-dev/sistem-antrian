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
use Carbon\CarbonInterval;

class AntrianController extends Controller
{
  public function index()
  {
    Carbon::setLocale('id');

    $warna = ['#ff6384', '#36a2eb', '#FFCD56', '#c8a2eb', '#d27b41', '#d27b41'];
    $antrian = collect(json_decode($this->getNewestAntrianData()->content()))->all();


    $antrianDipanggil = Antrian::whereNotNull('dipanggil_saat')
      ->whereDate('tanggal_pendaftaran', Carbon::today('Asia/Jakarta'))
      ->where('terpanggil', 'sudah')
      ->orderBy('dipanggil_saat')
      ->get()
      ->groupBy(function ($item) {
        return $item->jenjang ?? 'seragam';
      });

    $estimasi = [];

    foreach ($antrianDipanggil as $jenjang => $group) {
      $timestamps = [];

      foreach ($group as $item) {
        if (!empty($item->dipanggil_saat)) {
          $parsed = Carbon::parse($item->dipanggil_saat);
          $timestamps[] = $parsed;
        }
      }

      $timestamps = collect($timestamps)->sortBy(fn($ts) => $ts->timestamp)->values()->all();

      logger("Final timestamps for $jenjang:");
      foreach ($timestamps as $t) {
        logger(" - " . $t->toDateTimeString());
      }

      $totalGapInSeconds = 0;
      $gapCount = 0;

      for ($i = 1; $i < count($timestamps); $i++) {
        $diff = $timestamps[$i]->diffInSeconds($timestamps[$i - 1]);
        logger("Diff between {$timestamps[$i - 1]->toDateTimeString()} and {$timestamps[$i]->toDateTimeString()} = $diff seconds");

        $totalGapInSeconds += $diff;
        $gapCount++;
      }

      logger("GAP COUNT for $jenjang: $gapCount");
      logger("TOTAL GAP SECONDS for $jenjang: $totalGapInSeconds");

      $averageGap = $gapCount > 0 ? $totalGapInSeconds / $gapCount : 0;
      $formattedAverage = $gapCount > 0
        ? CarbonInterval::seconds((int) $averageGap)->cascade()->forHumans()
        : '5 Menit';

      $estimasi[$jenjang] = [
        'average_in_second' => (int) $averageGap,
        'formatted' => $formattedAverage
      ];
    }

    return view('antrian.index', [
      'warna' => $warna,
      'tanggal' => Carbon::now('Asia/Jakarta')->format('D, d M Y'),
      'antrian' => $antrian,
      'estimasi' => $estimasi
    ]);
  }
  public function getNewestAntrianData()
  {
    Carbon::setLocale('id');
    $antrian = DB::table('antrians')
      ->where('tanggal_pendaftaran', now('Asia/Jakarta')->format('Y-m-d'))
      ->where('terpanggil', 'belum')
      ->orderBy('created_at', 'asc')
      ->select('*')
      ->get();

    $antrian = AntrianHelper::groupBasedOnJenjang($antrian);

    foreach ($antrian as $key => $value) {
      foreach ($value as $antrianData) {
        $antrianData->nomor_antrian = AntrianHelper::generateNomorAntrian($antrianData->kode_antrian, $antrianData->nomor_antrian);
      }
    }
    $antrianDipanggil = Antrian::whereNotNull('dipanggil_saat')
      ->whereDate('tanggal_pendaftaran', Carbon::today('Asia/Jakarta'))
      ->where('terpanggil', 'sudah')
      ->orderBy('dipanggil_saat')
      ->get()
      ->groupBy(function ($item) {
        return $item->jenjang ?? 'seragam';
      });
    $estimasi = [];

    foreach ($antrianDipanggil as $jenjang => $group) {
      $timestamps = [];

      foreach ($group as $item) {
        if (!empty($item->dipanggil_saat)) {
          $parsed = Carbon::parse($item->dipanggil_saat);
          $timestamps[] = $parsed;
        }
      }

      $timestamps = collect($timestamps)->sortBy(fn($ts) => $ts->timestamp)->values()->all();

      logger("Final timestamps for $jenjang:");
      foreach ($timestamps as $t) {
        logger(" - " . $t->toDateTimeString());
      }

      $totalGapInSeconds = 0;
      $gapCount = 0;

      for ($i = 1; $i < count($timestamps); $i++) {
        $diff = $timestamps[$i]->diffInSeconds($timestamps[$i - 1]);
        logger("Diff between {$timestamps[$i - 1]->toDateTimeString()} and {$timestamps[$i]->toDateTimeString()} = $diff seconds");

        $totalGapInSeconds += $diff;
        $gapCount++;
      }

      logger("GAP COUNT for $jenjang: $gapCount");
      logger("TOTAL GAP SECONDS for $jenjang: $totalGapInSeconds");

      $averageGap = $gapCount > 0 ? $totalGapInSeconds / $gapCount : 0;
      $formattedAverage = $gapCount > 0
        ? CarbonInterval::seconds((int) $averageGap)->cascade()->forHumans()
        : '5 Menit';

      $estimasi[$jenjang] = [
        'average_in_second' => (int) $averageGap,
        'formatted' => $formattedAverage
      ];
    }
    $antrian['estimasi'] = $estimasi;
    return response()->json($antrian);
  }

  public function antrianBaru()
  {
    $jenjang = ['sd', 'smp', 'sma', 'smk', 'seragam'];
    $warna = ['#ff6384', '#36a2eb', '#FFCD56', '#c8a2eb', '#d27b41'];

    $antrian = DB::table('antrians')
      ->where('tanggal_pendaftaran', now('Asia/Jakarta')->format('Y-m-d'))
      ->where('terpanggil', 'belum')
      ->select('*')
      ->get();

    $antrian = AntrianHelper::groupBasedOnJenjang($antrian);

    return view('antrian.daftar', [
      'jenjang' => $jenjang,
      'warna' => $warna,
      'antrian' => $antrian
    ]);
  }

  public function konfirmasiAntrianBaru($jenjang)
  {
    if ($jenjang === 'bendahara') return redirect('/bendahara/konfirmasi');
    if ($jenjang === 'seragam') return redirect('/seragam/konfirmasi');

    $antrianPerJenjangTerbaru = DB::table('antrians')
      ->where('jenjang', $jenjang)
      ->where('tanggal_pendaftaran', now('Asia/Jakarta')->format('Y-m-d'))
      ->orderBy('nomor_antrian', 'desc')
      ->first('nomor_antrian');

    $nomorAntrianSebelumnya = $antrianPerJenjangTerbaru->nomor_antrian ?? 0;

    return view('antrian.konfirmasi', [
      'nomorAntrianSaatIni' => $nomorAntrianSebelumnya + 1,
      'jenjang' => $jenjang,
      'tanggal' => Carbon::now('Asia/Jakarta')->format('D, d M Y'),
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

    if (is_null($data['audio_path'])) return redirect('/antrian/daftar')->with('create-error', 'Gagal membuat antrian baru');

    $isAntrianCreated = Antrian::create([
      'nomor_antrian' => $data['nomor_antrian'],
      'jenjang' => $data['jenjang'],
      'kode_antrian' => AntrianHelper::getKodeAntrian($data['jenjang']),
      'audio_path' => $data['audio_path'],
      'tanggal_pendaftaran' => now('Asia/Jakarta')->format('Y-m-d')
    ]);

    if (!$isAntrianCreated) return redirect('/antrian/daftar')->with('create-error', 'Gagal membuat antrian baru');

    return redirect("/antrian/daftar/berhasil/$isAntrianCreated->id");
  }

  public function daftarAntrianBerhasil(Antrian $antrian)
  {
    $antrian->nomor_antrian = AntrianHelper::generateNomorAntrian($antrian->kode_antrian, $antrian->nomor_antrian);
    $antrian->jenjang = $antrian->jenjang ?? 'Bendahara';

    return view('antrian.berhasil', [
      'antrian' => $antrian
    ]);
  }
}
