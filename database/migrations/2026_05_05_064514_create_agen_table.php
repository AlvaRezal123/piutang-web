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
    Schema::create('agen', function (Blueprint $table) {
        $table->id(); // bigint primary key auto increment

        $table->bigInteger('id_agen_pp')->nullable();
        $table->string('username', 255);
        $table->string('password', 255);
        $table->string('no_hp', 20);
        $table->string('alamat', 255);

        $table->string('nik', 50); // ❗ diperbaiki (bukan int)
        
        $table->string('foto_ktp', 255);
        $table->string('foto_selfie_ktp', 255);
        $table->string('foto_toko_fisik', 255);

        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('agen');
    }
};
