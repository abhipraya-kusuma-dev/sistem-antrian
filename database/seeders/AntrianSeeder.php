<?php

namespace Database\Seeders;

use App\Models\Antrian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AntrianSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Antrian::factory(150)->create();
  }
}
