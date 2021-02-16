<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model 
{
	protected $table = 'scenario_questions';

	public function scenario()
	{
		return $this->belongsTo('\App\Scenario', 'scenario_id');
	}

	public function answers()
	{
		return $this->hasMany('\App\Answer', 'question_id')->get();
	}
}