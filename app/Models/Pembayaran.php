<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cicilan;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [

        'id_hutang',
        'id_cicilan',

        'tanggal_pembayaran',
        'jumlah_bayar',

        'bukti_pembayaran',
        'status',
        'alasan_penolakan',

        'nama_pengirim',
        'bank_pengirim',
    ];

    public function hutang()
    {
        return $this->belongsTo(
            Hutang::class,
            'id_hutang'
        );
    }

    public function cicilan()
    {
        return $this->belongsTo(
            Cicilan::class,
            'id_cicilan'
        );
    }
}