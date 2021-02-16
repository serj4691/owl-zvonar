<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Lead;
use App\Callcenter;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
	public function createLead($id, Request $request)
	{
		Log::debug("API Creating Lead " . json_encode($request->all()));

		$lead = new Lead;

		$lead->callcenter_id = $id;
		$lead->phone = $request->input('phone');
		$lead->name = $request->input('name');
		$lead->description = $request->input('description');
		if ($request->input('redirect_number')) $lead->redirect_number = $request->input('redirect_number');

		if ($lead->checkPhone()) {
			$lead->save();
			return response()->json(['result'=>'ok', 'lead'=>$lead]);
		} else {
			return response()->json(['result'=>'not ok', 'message'=>'bad_phone or double']);
		}
	}


	public function userIdLead(Request $request)
	{
		$data = $request->json()->all();

		$phones = $data["phones"];
		$page = $data["visit"]["page"];

		$callcenters = Callcenter::whereNotNull('user_id_page')->get();

		foreach ($callcenters as $callcenter) {
			if (strpos($page, $callcenter->user_id_page) !== false) {
				foreach ($phones as $phone) {
					$lead = new Lead;

					$lead->callcenter_id = $callcenter->id;
					$lead->phone = $phone;
					$lead->name = '';
					$lead->description = '';

					if ($lead->checkPhone()) {
						$lead->save();
					}
				}
				
			}
		}

		return "ok!";
	}
}