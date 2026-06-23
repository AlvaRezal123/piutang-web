<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agen', function (Blueprint $table) {
            $table->string('id_agen_pp')->change();
        });
    }

    public function down(): void
    {
        Schema::table('agen', function (Blueprint $table) {
            $table->bigInteger('id_agen_pp')->change();
        });
    }
};