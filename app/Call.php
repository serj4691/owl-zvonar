<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Call extends Model 
{
    public function lead()
	{
		return $this->belongsTo('\App\Lead', 'lead_id')->first();
	}


	public function operator()
	{
		return $this->belongsTo('\App\Operator', 'operator_id')->first();
	}
}