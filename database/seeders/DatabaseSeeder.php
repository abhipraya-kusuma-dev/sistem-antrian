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
      'role' => 'admin',
      'password' => bcrypt('pwadmin')
    ]);

    User::create([
      'name' => 'Operator SD',
      'username' => 'opsd',
      'role' => 'op_sd',
      'password' => bcrypt('pwopsd')
    ]);

    User::create([
      'name' => 'Operator SMP',
      'username' => 'opsmp',
      'role' => 'op_smp',
      'password' => bcrypt('pwopsmp')
    ]);

    User::create([
      'name' => 'Operator SMA',
      'username' => 'opsma',
      'role' => 'op_sma',
      'password' => bcrypt('pwopsma')
    ]);

    User::create([
      'name' => 'Operator SMK',
      'username' => 'opsmk',
      'role' => 'op_smk',
      'password' => bcrypt('pwopsmk')
    ]);

    User::create([
      'name' => 'Operator Bendahara',
      'username' => 'opbendahara',
      'role' => 'op_bendahara',
      'password' => bcrypt('pwopbendahara')
    ]);

    User::create([
      'name' => 'Operator Seragam',
      'username' => 'opseragam',
      'role' => 'op_seragam',
      'password' => bcrypt('pwopseragam')
    ]);

    // Antrian::factory(10)->create();
  }
}
