<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Callcenter extends Model 
{
	protected $fillable = [
		
		'operators'
	];

	public function scenario()
	{
		return $this->belongsTo('\App\Scenario', 'scenario_id')->first();
	}

	public function operators()
    {
        return $this->belongsToMany('App\Operator', 'operator_callcenters')->withPivot('callcenter_id');
    }

    public function setOperatorsAttribute($operators)
	{
		$this->operators()->detach();
		if ( ! $operators) return;

		if ( ! $this->exists) $this->save();

		$this->operators()->attach($operators);
	}
}