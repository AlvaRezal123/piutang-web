<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cicilan extends Model
{
    use HasFactory;

    protected $table = 'cicilan';

    protected $fillable = [

        'id_hutang',

        'cicilan_ke',

        'jumlah_cicilan',

        'tanggal_jatuh_tempo',

        'status',

        'tanggal_lunas'

    ];

    // Relasi ke Hutang

    public function hutang()
    {
        return $this->belongsTo(
            Hutang::class,
            'id_hutang'
        );
    }
}