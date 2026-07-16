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
    Schema::table('agen', function (Blueprint $table) {

        $table->text('alasan_penolakan_cicilan')
              ->nullable()
              ->after('status_permohonan_cicilan');

    });
}

public function down(): void
{
    Schema::table('agen', function (Blueprint $table) {

        $table->dropColumn('alasan_penolakan_cicilan');

    });
}
};
