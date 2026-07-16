<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agen', function (Blueprint $table) {

            $table->boolean('akses_cicilan')
                  ->default(false)
                  ->after('status');

            $table->enum('status_permohonan_cicilan', [
                'belum',
                'pending',
                'disetujui',
                'ditolak'
            ])->default('belum')
              ->after('akses_cicilan');

        });
    }

    public function down(): void
    {
        Schema::table('agen', function (Blueprint $table) {

            $table->dropColumn([
                'akses_cicilan',
                'status_permohonan_cicilan'
            ]);

        });
    }
};
