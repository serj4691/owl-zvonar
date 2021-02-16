<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\CustomerPhoneImport;
use Maatwebsite\Excel\Facades\Excel;
use \App\CustomerPhone;


class CustomerPhoneController extends Controller
{
    public function import(Request $request)
    {
        //dd('HHH');
        if ($request->file('imported_file')) {
        //     //dd($request->file('imported_file'));
        //     Excel::import(new CustomerPhoneImport, 'table.xlsx');//request()->file('imported_file'));
        //     dd(CustomerPhone);
        //     return redirect('/admin/customer_phones')->with('success', 'All good!');
        // }
    // public function import() 
    // {
            $array = Excel::toArray(new CustomerPhoneImport, request()->file('imported_file'));
            //dd($array[0]);
            foreach ($array[0] as $key => $value) {
                $customerPhones = new CustomerPhone();
                $customerPhones->phoneNumber = $value[0];
                $customerPhones->nameSource = $value[1];
                $customerPhones->nameBase = $value[2];
                $customerPhones->UTM_campaign = $value[3];
                $customerPhones->UTM_Source = $value[4];
                $customerPhones->nameResidentialComplexDonor = $value[5];
                $customerPhones->save();
            }
            
            return redirect('/')->with('success', 'All good!');
            //return back();
            }
    }

}
