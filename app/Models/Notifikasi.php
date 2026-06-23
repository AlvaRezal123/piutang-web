<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'id_agen',
        'id_admin',
        'judul',
        'pesan',
        'tipe',
        'media',
        'tanggal',
        'status_baca'
    ];
}