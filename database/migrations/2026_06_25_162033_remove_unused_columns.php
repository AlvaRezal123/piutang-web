<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hutang', function (Blueprint $table) {

            $table->dropColumn([
                'catatan_pengajuan',
                'keterangan_pencairan'
            ]);

        });

        Schema::table('pembayaran', function (Blueprint $table) {

            $table->dropColumn([
                'keterangan_pembayaran'
            ]);

        });
    }

    public function down(): void
    {
        Schema::table('hutang', function (Blueprint $table) {

            $table->text('catatan_pengajuan')->nullable();

            $table->text('keterangan_pencairan')->nullable();

        });

        Schema::table('pembayaran', function (Blueprint $table) {

            $table->text('keterangan_pembayaran')->nullable();

        });
    }
};