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
    Schema::table('cicilan', function (Blueprint $table) {
        $table->integer('hari_keterlambatan')
              ->default(0)
              ->after('tanggal_lunas');
    });
}

public function down()
{
    Schema::table('cicilan', function (Blueprint $table) {
        $table->dropColumn('hari_keterlambatan');
    });
}
};
