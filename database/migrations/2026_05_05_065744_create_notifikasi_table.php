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
    Schema::create('notifikasi', function (Blueprint $table) {
        $table->id();

        // relasi (nullable karena bisa ke agen atau admin)
       $table->foreignId('id_agen')->nullable()->constrained('agen');
$table->foreignId('id_admin')->nullable()->constrained('admin');

        $table->string('judul', 255);
        $table->text('pesan');

        $table->enum('tipe', [
            'pengajuan',
            'pembayaran',
            'keterlambatan',
            'konfirmasi_saldo'
        ]);

        $table->enum('media', ['web', 'whatsapp']);

        $table->dateTime('tanggal');

        $table->enum('status_baca', ['dibaca', 'belum'])->default('belum');

        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
