<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agen extends Model
{
    protected $table = 'agen';

    protected $fillable = [

        'user_id',

        'id_agen_pp',

        'username',

        'password',

        'no_hp',

        'alamat',

        'nik',

        'foto_ktp',

        'foto_selfie_ktp',

        'foto_toko_fisik',

        'status',

        'limit_pinjaman',

        'nama_usaha',

        'approved_at',

        'riwayat_tepat_waktu',  

        'status_kredit',

        'password_plain',

        'akses_cicilan',

        'status_permohonan_cicilan'
    ];

    // relasi ke users
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi ke hutang
    public function hutang()
    {
        return $this->hasMany(
            Hutang::class,
            'id_agen'
        );
    }

    // relasi ke notifikasi
   
}