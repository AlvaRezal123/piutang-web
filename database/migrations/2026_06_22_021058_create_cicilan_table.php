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
    Schema::create('cicilan', function (Blueprint $table) {

        $table->id();

        $table->foreignId('id_hutang')
            ->constrained('hutang')
            ->onDelete('cascade');

        $table->integer('cicilan_ke');

        $table->decimal(
            'jumlah_cicilan',
            15,
            2
        );

        $table->date('tanggal_jatuh_tempo');

        $table->enum(
            'status',
            [
                'belum',
                'lunas'
            ]
        )->default('belum');

        $table->date('tanggal_lunas')
            ->nullable();

        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('cicilan');
    }
};
