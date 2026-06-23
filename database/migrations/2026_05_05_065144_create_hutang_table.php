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
    Schema::create('hutang', function (Blueprint $table) {
       $table->id();

        // Foreign key ke agen
       $table->foreignId('id_agen')->constrained('agen')->onDelete('cascade');

        // jumlah uang → pakai decimal (bukan 255)
        $table->decimal('jumlah_hutang', 15, 2);

        $table->enum('metode', ['cash', 'cicil']);

        // enum tempo (opsi 2 atau 3 bulan)
        $table->enum('lama_tempo', ['2 bulan', '3 bulan'])->nullable();

        $table->date('tanggal_pengajuan');
        $table->date('tanggal_jatuh_tempo');

        $table->decimal('sisa_hutang', 15, 2);

        $table->enum('status', [
            'pending',
            'disetujui',
            'ditolak',
            'berjalan',
            'lunas',
            'terlambat'
        ])->default('pending');

        $table->timestamps();
    });
}
       public function down(): void
       
    {
        Schema::dropIfExists('hutang');
    }
};
