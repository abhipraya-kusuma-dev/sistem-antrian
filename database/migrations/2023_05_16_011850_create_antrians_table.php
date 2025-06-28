<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('antrians', function (Blueprint $table) {
      $table->uuid('id');
      $table->integer('nomor_antrian');
      $table->string('antrian_jenjang')->nullable();
      $table->date('tanggal_pendaftaran')->default(now('Asia/Jakarta')->format('Y-m-d'));
      $table->enum('jenjang', ['sd', 'smp', 'sma', 'smk'])->nullable();
      $table->enum('kode_antrian', ['D', 'P', 'A', 'K', 'B', 'M']);
      $table->enum('terpanggil', ['belum', 'sudah', 'lewati'])->default('belum');
      $table->string('audio_path')->nullable();
      $table->timestamp('dipanggil_saat')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('antrians');
  }
};
