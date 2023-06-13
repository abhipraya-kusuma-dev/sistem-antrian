<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use App\Helper\AntrianHelper;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AntriansExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
  private string $tanggal_pendaftaran_start;
  private string $tanggal_pendaftaran_end;

  /**
   * @return \Illuminate\Support\Collection
   */
  public function __construct(array $tanggal_pendaftaran)
  {
    $this->tanggal_pendaftaran_start = $tanggal_pendaftaran[0];
    $this->tanggal_pendaftaran_end = $tanggal_pendaftaran[1];
  }

  public function columnWidths(): array
  {
    return [
      'A' => 28,
      'B' => 8,
      'C' => 8,
      'D' => 8,
      'E' => 8,
      'F' => 18,
      'G' => 18,
    ];
  }
  public function styles(Worksheet $sheet)
  {
    return [
      1 => [
        'font' => [
          'bold' => true
        ],
        'fill' => [
          'fillType' => Fill::FILL_SOLID,
          'startColor' => [
            'argb' => 'FFFF00',
          ],
        ]
      ],
    ];
  }

  public function headings(): array
  {
    return [
      'TANGGAL_PENDAFTARAN',
      'SD',
      'SMP',
      'SMA',
      'SMK',
      'BENDAHARA',
      'SERAGAM'
    ];
  }

  public function collection()
  {
    $laporanAntrian = DB::table('antrians')
      ->whereBetween('tanggal_pendaftaran', [$this->tanggal_pendaftaran_start, $this->tanggal_pendaftaran_end])
      ->select('*')->get();

    $mappedLaporan = AntrianHelper::groupBasedOnTanggalPendaftaran($laporanAntrian);

    // $arr = [
    //   '2023-06-13' => [
    //     '2023-06-13',
    //     1, 1, 1, 1, 3, 1
    //   ],
    //   '2023-06-10' => [
    //     '2023-06-10',
    //     1, 2, 1, 1, 3, 1
    //   ],
    // ];

    $arr = [];

    foreach ($mappedLaporan as $tanggal_pendaftaran => $value) {
      $arr[$tanggal_pendaftaran][] = $tanggal_pendaftaran;

      foreach ($value as $laporan) {
        if (!count($laporan)) {
          $arr[$tanggal_pendaftaran][] = 'Kosong';
          continue;
        }

        $arr[$tanggal_pendaftaran][] = count($laporan);
      }
    }

    return collect($arr);
  }
}
