<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model 
{

	public function task()
	{
		return $this->belongsTo('\App\LeadTask', 'lead_task_id')->first();
	}

	public function lead()
	{
		if (!$this->task()) return null;
		return $this->task()->lead();
	}

	public function resetOperatorState($state)
	{
		$this->state = $state['STATUS'];
		$this->lead_task_id = null;
		$this->question_id = null;
		$this->save();
	}

	public function setQuestion($question_id)
	{
		$this->question_id = $question_id;
		$this->save();
	}

	public function callcenters()
    {
        return $this->belongsToMany('App\Callcenter', 'operator_callcenters')->withPivot('operator_id');
    }

    public function setCallcentersAttribute($callcenters)
	{
		$this->callcenters()->detach();
		if ( ! $callcenters) return;

		if ( ! $this->exists) $this->save();

		$this->callcenters()->attach($callcenters);
	}
    
}