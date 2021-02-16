<?php

namespace App\Imports;

use App\BasePhone;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToArray;

class BasePhonesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new BasePhone([
            'name'     => $row[0],
             'phone'    => $row[1],
             'complexes' => $row[2],
             'sources' => $row[3],
             'channels' => $row[4],
             'first_contact_date' => $this->transformDate($row[5]),
             'comments' => $row[7],
             'success_calls' => $row[8],
             'got_through_calls' => $row[9],
             'failed_calls' => $row[10],
//            'name'     => 'qwe',
//            'phone'    => '1234567890',
//            'complexes' => 'complex',
//            'sources' => 'sources',
//            'channels' => 'channels',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function array(array $array)
    {
        // TODO: Implement array() method.
    }


}
