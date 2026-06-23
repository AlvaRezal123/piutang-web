<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hutang', function (Blueprint $table) {

            $table->string('bukti_pencairan')
                ->nullable()
                ->after('catatan_pengajuan');

            $table->text('keterangan_pencairan')
                ->nullable()
                ->after('bukti_pencairan');

            $table->date('tanggal_pencairan')
                ->nullable()
                ->after('keterangan_pencairan');

        });
    }

    public function down(): void
    {
        Schema::table('hutang', function (Blueprint $table) {

            $table->dropColumn([
                'bukti_pencairan',
                'keterangan_pencairan',
                'tanggal_pencairan'
            ]);

        });
    }
};