<?php

namespace App;

use TelegramBot\Api\Types\ReplyKeyboardMarkup as Keyboard;
use Illuminate\Support\Facades\Log;


class VoxApi
{
	public function __construct()
	{
		$this->base_url = "https://api.voximplant.com/platform_api/";
		$this->api_key = env("VOX_API_KEY");
		$this->scenario_id = env("VOX_SCENARIO_RULE_ID");
		$this->account_id = env("VOX_ACCOUNT_ID");


		$url = "?account_id=&api_key=&rule_id=3026732&script_custom_data=+79671487799:+79152641893";

		
	}

	public function callback($call)
	{
		$data = [
			$call->operator() 
				? $call->operator()->phone 
				: ($call->lead()->redirect_number 
						? $call->lead()->redirect_number 
						: $call->lead()->callcenter()->phone),
			$call->lead()->phone,
			$call->id,
			1,
			$call->lead()->id,
			$call->lead()->callcenter()->phone
		];
		$lead = $call->lead();

		// if ($lead->callcenter()->use_redirect) $data[] = $call->lead()->callcenter()->phone;
		if ($lead->ids) {
			$ids = $lead->ids;
	        $ids = explode(",", $ids);

	        $phones = [];

	        foreach ($ids as $id) {
	            $phones[] = Complex::find($id)->phone;
	        }

	        $data[] = implode(",", $phones);
		}

		$scenario_id = $this->scenario_id;
		if ($lead->callcenter()->id == 297) $scenario_id = '3288357';
		$scenario_id = '3288357';

		return $this->query('StartScenarios', $data, ["rule_id" => $scenario_id]);
	}



	private function query($method, $data, $params = []) {
		$params["account_id"] = $this->account_id;
		$params["api_key"] = $this->api_key;
		$params["script_custom_data"] = implode(":", $data);

		$url = $this->base_url . "/$method/?" . http_build_query($params);

		Log::debug($url);
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
	}
}