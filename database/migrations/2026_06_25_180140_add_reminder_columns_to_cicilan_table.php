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
        Schema::table('cicilan', function (Blueprint $table) {

            $table->boolean('reminder_h1')
                ->default(false)
                ->after('status');

            $table->boolean('reminder_telat')
                ->default(false)
                ->after('reminder_h1');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cicilan', function (Blueprint $table) {

            $table->dropColumn([
                'reminder_h1',
                'reminder_telat'
            ]);

        });
    }
};