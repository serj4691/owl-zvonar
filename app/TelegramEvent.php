<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class TelegramEvent extends Model 
{
    public static function checkUnique($request)
    {

        if (isset($request['edited_message'])) return false;

    	$event_id = $request['message']['message_id'] . "_" . $request['message']["from"]["id"];

    	$event = self::where('event_id', $event_id)->first();

    	if (!!$event) return false;
    	else {

    		$event = new self;
    		$event->event_id = $event_id;
    		$event->save();
    		
    		return true;
    	}
    }
}