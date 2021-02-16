<?php

namespace App\Console\Commands;

use App\Operator;
use App\LeadEvent;
use App\LeadTask;
use App\Lead;
use Illuminate\Console\Command;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Voximplant\VoximplantApi;
use Voximplant\Resources\Params\StartScenariosParams;
use Illuminate\Support\Facades\Log;

class ResetNotInteresedLeads extends Command
{
	protected $signature = 'reset_not_interesed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $events = LeadEvent::where('status', 'not_interested')
            ->where('created_at', '>', date("Y-m-d H:i:s", strtotime("-1 hours")))
            ->get()
        ;

        $phones = [];

        foreach ($events as $event) {
            $phones[] = Lead::find($event->lead_id)->phone;
        }

        foreach ($phones as $phone) {

            $leads = Lead::where('phone', $phone);

            foreach ($leads as $lead) if (!!LeadTask::where('lead_id', $lead->id)->first()) {
                $tasks = LeadTask::where('lead_id', $lead->id)->get();

                foreach ($tasks as $task) {
                    $task->processing_at = date("Y-m-d H:i:s", strtotime("7 days"));
                    $task->save();
                }
            }
            
        }
    }
}