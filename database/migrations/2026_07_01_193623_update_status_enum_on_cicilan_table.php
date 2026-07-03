<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE cicilan
            MODIFY status ENUM(
                'belum',
                'terlambat',
                'lunas'
            ) NOT NULL DEFAULT 'belum'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE cicilan
            MODIFY status ENUM(
                'belum',
                'lunas'
            ) NOT NULL DEFAULT 'belum'
        ");
    }
};