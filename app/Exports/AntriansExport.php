<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use App\Helper\AntrianHelper;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AntriansExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
  private string $tanggal_pendaftaran;

  /**
   * @return \Illuminate\Support\Collection
   */
  public function __construct(string $tanggal_pendaftaran)
  {
    $this->tanggal_pendaftaran = $tanggal_pendaftaran;
  }

  public function columnWidths(): array
  {
    return [
      'A' => 8,
      'B' => 8,
      'C' => 8,
      'D' => 8,
      'E' => 18,
      'F' => 18,
    ];
  }
  public function styles(Worksheet $sheet)
  {
    $styleArray = [
      'borders' => [
        'outline' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
          'color' => ['argb' => 'FFFF0000'],
        ],
      ],
    ];

    $sheet->getStyle('A')->applyFromArray($styleArray);

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
      'SD',
      'SMP',
      'SMA',
      'SMK',
      'BENDAHARA',
      'SERAGAM'
    ];
  }

  public function array(): array
  {
    $laporanAntrian = DB::table('antrians')
      ->where('tanggal_pendaftaran', $this->tanggal_pendaftaran)
      ->select('*')->get();

    $mappedLaporan = AntrianHelper::groupBasedOnJenjang($laporanAntrian);

    $arr = [
      'data' => []
    ];

    foreach ($mappedLaporan as $laporan) {
      if (!count($laporan)) {
        $arr['data'][] = 'Kosong';
        continue;
      }

      $arr['data'][] = count($laporan);
    }

    return $arr;
  }
}
