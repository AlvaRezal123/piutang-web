<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayaran', function (Blueprint $table) {

            $table->string('nama_pengirim')
                ->nullable()
                ->after('jumlah_bayar');

            $table->string('bank_pengirim')
                ->nullable()
                ->after('nama_pengirim');

            $table->text('keterangan_pembayaran')
                ->nullable()
                ->after('bank_pengirim');

        });
    }

    public function down()
    {
        Schema::table('pembayaran', function (Blueprint $table) {

            $table->dropColumn([
                'nama_pengirim',
                'bank_pengirim',
                'keterangan_pembayaran'
            ]);

        });
    }
};