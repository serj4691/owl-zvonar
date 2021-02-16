<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use \App\BasePhone;
// use \App\BaseChannel;
use \App\BaseComplex;
// use \App\BaseSource;
use App\Imports\BasePhonesImport;
use Maatwebsite\Excel\Facades\Excel;

class BasePhoneController extends Controller
{
    public function import(Request $request)
    {
        if ($request->file('imported_file')) {
        	
            $complexesName = BaseComplex::all()->toArray();
            $existPhones = BasePhone::all()->toArray();
            //dump($existPhones);
            $array = Excel::toArray(new BasePhonesImport, request()->file('imported_file'));
            //dd($array[0]);
            
            foreach ($array[0] as $key => $value) {
                //dd(array_search($value[1], array_column($existPhones, 'phone')));
                $keyPhone = array_search($value[1], array_column($existPhones, 'phone'));
                //dd($existPhones[$keyPhone]['id']);
                if(!$keyPhone){
                    $phones = new BasePhone();
                    $phones->name = $value[0];
                    $phones->phone = $value[1];
                     
                    $key = array_search($value[2], array_column($complexesName, 'name'));
                     
                    if(!$key){
                        //dump("key=",$key,end($existPhones)['id']);
                        $complexes = new BaseComplex;
                        $complexes->name = $value[2];
                        $complexes->save();
                        $complexes->id= end($complexesName)['id'] + 1;
                    } else {
                        $complexes = new BaseComplex;
                        $complexes->id= end($complexesName)['id'];
                    }
                    $hasComplex = $phones->complexes()->where('id', $key)->exists();
                    $phones->id = end($existPhones)['id'] + 1;
                    $phonePivot = App\BasePhone::find($phones->id);
                    //dd($phones->id, $complexes->id);
                    // $phonePivot->complexes()->updateExistingPivot($phones->id, ['base_complex_id ' => $complexes->id]);
                    //$phonePivot->complexes()->save($phones->id, ['base_complex_id ' => $complexes->id]);
                    $phonePivot->complexes()->attach($complexes->id);

                     // $hasComplex = $phones->complexes()->where('id', $key)->exists();
                     // if($hasComplex){

                     // } else {
                     //    $phones->complexes()->updateExistingPivot($phones->id, ['id' => $complexes->id]);
                     // }

                     $phones->sources = $value[3];
                     $phones->channels = $value[4];
                     $phones->first_contact_date = $this->transformDate($value[5]);//$value[5];
                     $phones->comments = $value[7];
                     $phones->success_calls = $value[8];
                     $phones->got_through_calls = $value[9];
                     $phones->failed_calls = $value[10];
                     dd("New:",$phones);
                     $phones->save();
                } else {
                    $id = $existPhones[$keyPhone]['id'];
                    //$hasComplex = $phones->complexes()->where('base_phone_id', $id)->exists();
                    $hasComplex = App\BasePhone::has('complexes')->get();
                    dd("Est:",$existPhones[$keyPhone]['id'], $hasComplex);
                }
            //dd($phones);
             }
            
            return redirect()->route('admin.dashboard')->with('success', 'All good!');
            //return back();
        } else {
            return redirect()->route('admin.dashboard')->with('danger', 'Bad file name!');
        }
    }
    /**
     * Transform a date value into a Carbon object.
     *
     * @return \Carbon\Carbon|null
     */
    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
}
