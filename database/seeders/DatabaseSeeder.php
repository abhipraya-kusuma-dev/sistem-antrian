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
      'password' => bcrypt('hehe1234##')
    ]);

    User::create([
      'name' => 'Operator1',
      'username' => 'op1',
      'role' => 'operator',
      'password' => bcrypt('hehe1234##')
    ]);

    User::create([
      'name' => 'Operator2',
      'username' => 'op2',
      'role' => 'operator',
      'password' => bcrypt('hehe1234##')
    ]);
  }
}
