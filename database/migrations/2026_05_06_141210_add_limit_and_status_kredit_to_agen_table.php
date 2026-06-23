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

            // limit maksimal pinjaman
            $table->decimal('limit_pinjaman', 15, 2)
                  ->default(150000);

            // status kredit agen
            $table->enum('status_kredit', [
                'baru',
                'terpercaya',
                'bermasalah'
            ])->default('baru');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agen', function (Blueprint $table) {

            $table->dropColumn('limit_pinjaman');
            $table->dropColumn('status_kredit');

        });
    }
};