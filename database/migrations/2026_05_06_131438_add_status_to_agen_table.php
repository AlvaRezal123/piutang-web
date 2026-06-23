<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agen', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'aktif',
                'ditolak',
                'diblokir'
            ])->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('agen', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};