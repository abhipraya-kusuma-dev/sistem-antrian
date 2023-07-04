<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helper\TextToSpeechHelper;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Antrian>
 */
class AntrianFactory extends Factory
{
  private static $nomor_antrian = 1;
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'nomor_antrian'  => self::$nomor_antrian++,
      'tanggal_pendaftaran' => now('Asia/Jakarta')->format('Y-m-d'),
      'kode_antrian' => 'M',
      'audio_path' => TextToSpeechHelper::getAudioPath(self::$nomor_antrian, 'seragam')
    ];
  }
}
