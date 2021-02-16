<?php

namespace App\Imports;

use App\CustomerPhone;
use Maatwebsite\Excel\Concerns\ToModel;
//use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerPhoneImport implements ToModel//, WithUpserts
{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // if (!isset($row[3])) {
        //     $row[3] = '';
        // }
        // if (!isset($row[4])) {
        //     $row[4] = '';
        // }
        //dd($row);
        return new CustomerPhone([

            'phoneNumber'     => $row[0],
            'nameSource'    => $row[1],
            'nameBase' => $row[2],
            'UTM_campaign' => $row[3],
            'UTM_Source' => $row[4],
            'nameResidentialComplexDonor' => $row[5],
        ]);
    }

}
