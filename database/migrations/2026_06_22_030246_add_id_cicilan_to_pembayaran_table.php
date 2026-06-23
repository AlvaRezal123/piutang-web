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
        Schema::table('pembayaran', function (Blueprint $table) {

            $table->unsignedBigInteger('id_cicilan')
                ->nullable()
                ->after('id_hutang');

            $table->foreign('id_cicilan')
                ->references('id')
                ->on('cicilan')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {

            $table->dropForeign([
                'id_cicilan'
            ]);

            $table->dropColumn(
                'id_cicilan'
            );

        });
    }
};