<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cicilan;

class Hutang extends Model
{
    protected $table = 'hutang';

    protected $fillable = [
        'id_agen',
        'jumlah_hutang',
        'metode',
        'lama_tempo',
        'tanggal_pengajuan',
        'tanggal_jatuh_tempo',
        'sisa_hutang',
        'status',
        'alasan_penolakan',
        'bukti_pencairan',
        'keterangan_pencairan',
        'tanggal_pencairan'
    ];

    public function agen()
    {
        return $this->belongsTo(
            Agen::class,
            'id_agen'
        );
    }

    public function pembayaran()
    {
        return $this->hasMany(
            Pembayaran::class,
            'id_hutang'
        );
    }

    public function cicilan()
    {
        return $this->hasMany(
            Cicilan::class,
            'id_hutang'
        );
    }
}