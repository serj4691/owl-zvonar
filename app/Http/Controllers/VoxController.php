<?php

namespace App\Http\Controllers;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\ReplyKeyboardMarkup as Keyboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\TelegramEvent;
use App\Call;
use App\VoxApi;

use App\Bot;

class VoxController extends Controller
{
	public function vox(Request $request)
	{
		Log::debug("Vox event call");
		Log::debug($request);

		$call_id = $request->input('call_id');
		$status = $request->input('status');

		if ($status == 'record_2_started') {
			$call = new Call;
			$parent_call = Call::find($call_id);

			$call->parent_id = $call_id;
			$call->record_url = $request->input('record_url');
			$call->last_status = $status;
			$call->lead_id = $parent_call->lead_id;
			$call->operator_id = $parent_call->operator_id;

			$call->save();

			Log::debug($call);

			return response()->json(['status' => 'ok!']);
		}

		$call = Call::find($call_id);
		$call->last_status = $status;

		if ($request->input('record_url')) {
			$call->record_url = $request->input('record_url');
		}

		Log::debug($call->record_url);

		$call->save();

		if (in_array($status, [
			"second_call_disconected_success",
			"second_call_failed",
			"first_call_disconected",
			"first_call_failed"
		])) {
			app('App\Http\Controllers\TelegramController')->dropCall($call);
		}
	}


	public function createCall($operator_id)
	{
		Log::debug("Vox create call " . $operator_id);
		
		$operator = \App\Operator::find($operator_id);
		$call = new Call;
        $call->operator_id = $operator->id;
        $call->lead_id = $operator->lead()->id;
        $call->save();

        $operator->lead()->process(CALL_CREATED, null, [
			'operator_id' => $operator->id,
			'call_id' => $call->id
		]);

        

        $vox = new VoxApi;
        $vox->callback($call);
	}


	public function createCall2($lead)
	{
		Log::debug("Vox create call to callcenter");
		$callcenter = $lead->callcenter();

		Log::debug($lead);
		Log::debug($callcenter);

		$call = new Call;
        $call->lead_id = $lead->id;
        $call->save();
        

        $vox = new VoxApi;
        $vox->callback($call);
	}
}