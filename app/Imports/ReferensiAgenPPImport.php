<?php

namespace App\Imports;

use App\Models\ReferensiAgenPP;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ReferensiAgenPPImport implements ToModel, WithHeadingRow
{
    protected $metodeImport;

    public function __construct($metodeImport)
    {
        $this->metodeImport = $metodeImport;
    }

    public function model(array $row)
    {
        return ReferensiAgenPP::updateOrCreate(
            ['id_agen_pp' => $row['id_agen_pp']],
            [
                'username' => $row['username'],
                'alamat'   => $row['alamat'],
                'no_hp'    => $row['no_hp'],
            ]
        );
    }
}