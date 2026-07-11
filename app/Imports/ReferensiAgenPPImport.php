<?php

namespace App\Imports;

use App\Models\ReferensiAgenPP;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ReferensiAgenPPImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
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