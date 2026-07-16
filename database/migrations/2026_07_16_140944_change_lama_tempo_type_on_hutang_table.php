<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE hutang
            MODIFY lama_tempo TINYINT UNSIGNED NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE hutang
            MODIFY lama_tempo ENUM('2 bulan','3 bulan') NULL
        ");
    }
};