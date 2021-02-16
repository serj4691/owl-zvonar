<?php

namespace App\Console\Commands;

use App\Operator;
use Illuminate\Console\Command;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Voximplant\VoximplantApi;
use Voximplant\Resources\Params\StartScenariosParams;
use Illuminate\Support\Facades\Log;

class ResetStates extends Command
{
	protected $signature = 'reset_states';

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

        $operatorsAwaked = Operator
            ::where('state', OPERATOR_STATES_AWAKED)
            ->where('updated_at', '<', date('Y-m-d H:i:s', strtotime("-1 minutes")))
            ->get()
        ;

        $operatorHasLead = Operator
            ::where('state', OPERATOR_STATES_HAS_LEAD)
            ->where('updated_at', '<', date('Y-m-d H:i:s', strtotime("-1 minutes")))
            ->get()
        ;

        // $operatorAnswering = Operator
        //     ::where('state', OPERATOR_STATES_LEAD_CALLED)
        //     ->where('updated_at', '<', date('Y-m-d H:i:s', strtotime("-15 minutes")))
        //     ->get()
        // ;


        foreach ($operatorsAwaked as $operator) {
            Log::debug("Awaker operator drop");
            Log::debug($operator);
            $operator->resetOperatorState(OPERATOR_STATES_SLEEP);
        }

        foreach ($operatorHasLead as $operator) {
            Log::debug("Has lead operator drop");
            Log::debug($operator);
            $operator->resetOperatorState(OPERATOR_STATES_SLEEP);
        }

        foreach ($operatorAnswering as $operator) {
            Log::debug("Answering operator drop");
            Log::debug($operator);

            $operator->resetOperatorState(OPERATOR_STATES_SLEEP);
        }
    }
}