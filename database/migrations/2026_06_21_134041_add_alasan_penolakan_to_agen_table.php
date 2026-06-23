<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('agen', function ($table) {

        $table->text('alasan_penolakan')
              ->nullable();

    });
}
    public function down(): void
    {
        Schema::table('agen', function (Blueprint $table) {
            //
        });
    }
};
