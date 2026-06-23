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
    Schema::create('pembayaran', function (Blueprint $table) {
        $table->id();

        // relasi ke hutang
     $table->foreignId('id_hutang')->constrained('hutang')->onDelete('cascade');

        $table->date('tanggal_pembayaran');

        // jumlah uang → decimal (bukan 255)
        $table->decimal('jumlah_bayar', 15, 2);

        $table->string('bukti_pembayaran', 255);

        $table->enum('status', [
            'pending',
            'disetujui',
            'ditolak'
        ])->default('pending');

        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
