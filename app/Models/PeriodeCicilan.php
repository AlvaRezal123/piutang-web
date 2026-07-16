<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeCicilan extends Model
{
    protected $table = 'periode_cicilan';

    protected $fillable = [
        'nama_periode',
        'jumlah_bulan',
        'status'
    ];
}