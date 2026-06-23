<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY status ENUM(
                'aktif',
                'pending',
                'ditolak',
                'diblokir'
            ) NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY status ENUM(
                'aktif',
                'pending',
                'ditolak'
            ) NOT NULL DEFAULT 'pending'
        ");
    }
};