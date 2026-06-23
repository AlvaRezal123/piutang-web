<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    DB::statement("
        ALTER TABLE notifikasi
        MODIFY tipe ENUM(
            'pengajuan',
            'pembayaran',
            'persetujuan',
            'pencairan',
            'keterlambatan'
        )
    ");
}
  public function down(): void
{
    DB::statement("
        ALTER TABLE notifikasi
        MODIFY tipe ENUM(
            'pengajuan',
            'pembayaran',
            'keterlambatan'
        )
    ");
}
};
