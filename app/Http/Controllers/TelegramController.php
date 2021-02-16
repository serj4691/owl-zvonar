<?php

namespace App\Http\Controllers;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\ReplyKeyboardMarkup as Keyboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\TelegramEvent;

use App\Bot;

class TelegramController extends Controller
{

	public function bot(Request $request)
	{
		
		Log::debug("Telegram message " . json_encode($request->all()));

		$request = $request->all();

		if (!TelegramEvent::checkUnique($request)) {
			
			return response()->json(["status" => "done"]);
		} else {
			$bot = new Bot([
				'chat_id' => $request['message']['chat']['id'],
				'username' => $request['message']['chat']['username'],
				'message' => $request['message']['text']
			], new BotApi(env('TELEGRAM_TOKEN')));
			$bot->process();
		}
	}

	public function dropCall($call) {

		Log::debug("Telegram command dropcall " . $call->id);

		$operator = $call->operator();

		if ($operator) {
			$bot = new Bot([
				'chat_id' => $operator->chat_id,
				'username' => $operator->username,
				'message' => "Звонок завершен"
			], new BotApi(env('TELEGRAM_TOKEN')));

			$bot->process();
		}
	}

	public function test(Request $request)
    {
        $request = $request->all();

        var_dump($request);
        Log::debug($request);
    }
}