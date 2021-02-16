<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model 
{
	protected $table = 'scenario_answers';

	public function question()
	{
		return $this->belongsTo('\App\Question', 'question_id');
	}
}