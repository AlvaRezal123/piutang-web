<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferensiAgenPP extends Model
{
    protected $table = 'referensi_agen_pp';

    protected $primaryKey = 'id_agen_pp';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_agen_pp',
        'username',
        'alamat',
        'no_hp',
    ];
}