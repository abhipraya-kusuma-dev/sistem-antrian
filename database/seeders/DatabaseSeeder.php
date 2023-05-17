<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Antrian;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    User::create([
      'name' => 'Admin',
      'username' => 'admin',
      'password' => bcrypt('hehe1234##')
    ]);

    // Antrian::create([
    //   'nomor_antrian' => 1,
    //   'jenjang' => 'smk',
    //   'terpanggil' => 'belum'
    // ]);
    //
    // Antrian::create([
    //   'nomor_antrian' => 2,
    //   'jenjang' => 'smk',
    //   'terpanggil' => 'belum'
    // ]);
    //
    // Antrian::create([
    //   'nomor_antrian' => 3,
    //   'jenjang' => 'smk',
    //   'terpanggil' => 'belum'
    // ]);
    //
    // Antrian::create([
    //   'nomor_antrian' => 1,
    //   'jenjang' => 'sma',
    //   'terpanggil' => 'belum'
    // ]);
    //
    // Antrian::create([
    //   'nomor_antrian' => 2,
    //   'jenjang' => 'sma',
    //   'terpanggil' => 'belum'
    // ]);
  }
}
