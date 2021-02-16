<?php

namespace App\Imports;

use App\BasePhone;
use Maatwebsite\Excel\Concerns\ToModel;

class BasePhoneImport implements ToModel
{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {dd($row);
        return new BasePhone([

            'name'     => $row[0],
            'phone'    => $row[1],
            'complexes' => $row[2],
            'sources' => $row[3],
            'channels' => $row[4],
            'first_contact_date' => $row[5],
            'comments' => $row[6],
            'success_calls' => $row[7],
            'got_through_calls' => $row[8],
            'failed_calls' => $row[9],
        ]);
    }

}
