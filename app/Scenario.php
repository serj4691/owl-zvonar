<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Scenario extends Model 
{
	public function firstQuestion($column)
	{
		if (!in_array($column, ['success', 'not_interest'])) throw new Exception("Are u crazy?", 1);
		return $this->belongsTo('\App\Question', $column . "_first_id")->first();
	}

	public function strategy()
	{
		return $this->belongsTo('\App\NotRespondingStrategy', 'strategy_id')->first();
	}
}