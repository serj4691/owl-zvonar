<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\LeadEvent;
use App\Call;

class LeadUpload extends Model 
{
	protected $table = 'lead_uploads';

	public function callcenter()
	{
		return $this->belongsTo('\App\Callcenter', 'callcenter_id');
	}
}