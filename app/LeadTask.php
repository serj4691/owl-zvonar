<?php

namespace App;
use \DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class LeadTask extends Model 
{
	public function lead() {
        return $this->belongsTo('App\Lead')->first();
    }

    public function operator() {
        return $this->hasOne('App\Operator', 'lead_task_id')->first();
    }

    public static function getPriorityTask($operator)
    {
        $task = LeadTask::select("lead_tasks.*")
            ->join("leads", "leads.id", "=", "lead_tasks.lead_id")
            ->leftJoin("operators", "operators.lead_task_id", "=", "lead_tasks.id")
            ->join("operator_callcenters", "operator_callcenters.callcenter_id", "=", "leads.callcenter_id")
            ->join("callcenters", "callcenters.id", "=", "leads.callcenter_id")
            ->whereNull('operators.id')
            ->where('processing_at', '<', date('Y-m-d H:i:s'))
            ->where('leads.phone', '<>', '')
            ->where('operator_callcenters.operator_id', $operator->id)
            ->orderBy(DB::raw('lead_tasks.priority - callcenters.priority'))
            ->orderBy('lead_tasks.processing_at')
            ->orderBy('lead_tasks.created_at')
            ->first();

        Log::debug($task);


        if (!$task) return null;
        
        Log::debug($task);

        return $task;
    }
}