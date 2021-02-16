<?php

namespace App\Scenarios;

class CPASwitchScenario extends Scenario 
{
	public function successProcessing($lead)
	{
		if (!$lead->callcenter()->use_redirect)	app('App\Http\Controllers\VoxController')->createCall2($lead);

		
		return false;
	}

}