<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hutang', function (Blueprint $table) {
            $table->date('tanggal_jatuh_tempo')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('hutang', function (Blueprint $table) {
            $table->date('tanggal_jatuh_tempo')
                ->nullable(false)
                ->change();
        });
    }
};