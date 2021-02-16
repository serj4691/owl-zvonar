<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Lead;
use App\Complex;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect('/admin');
        //return view('show');
    }


    public function leadIds($id)
    {
        $ids = Lead::find($id)->ids;
        $ids = explode(",", $ids);

        $phones = [];

        foreach ($ids as $id) {
            $phones[] = Complex::find($id)->phone;
        }

        return implode(";", $phones);
    }

    public function complexes()
    {
        $cs = Complex::get();

        foreach ($cs as $c) {
            $c->delete();
        }

        $json = file_get_contents("https://cpa.getflat.info/api/complexes");
        $json = json_decode($json);

        foreach ($json as $row) {
            $complex = new Complex;

            $complex->name = $row->name;

            $complex->budjet0 = $row->budjet0;
            $complex->budjet1 = $row->budjet1;
            $complex->budjet2 = $row->budjet2;
            $complex->budjet3 = $row->budjet3;
            $complex->budjet4 = $row->budjet4;
            $complex->budjet0_max = $row->budjet0_max;
            $complex->budjet1_max = $row->budjet1_max;
            $complex->budjet2_max = $row->budjet2_max;
            $complex->budjet3_max = $row->budjet3_max;
            $complex->budjet4_max = $row->budjet4_max;

            // https://metabase.getflat.info/question/123?rooms=2&budjet=20&budjet_max=21

            $complex->save();
        }
    }
}
