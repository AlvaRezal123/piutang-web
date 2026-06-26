<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agen', function (Blueprint $table) {

            $table->integer(
                'riwayat_tepat_waktu'
            )->default(0)
             ->after('status_kredit');

        });
    }

    public function down(): void
    {
        Schema::table('agen', function (Blueprint $table) {

            $table->dropColumn(
                'riwayat_tepat_waktu'
            );

        });
    }
};