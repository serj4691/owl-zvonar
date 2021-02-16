<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Voximplant\VoximplantApi;
use Voximplant\Resources\Params\StartScenariosParams;


class VoxTest extends Command
{
	protected $signature = 'vox:test';

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

    	$caller_id = "+74999387975";
    	$voxApi = new VoximplantApi(env('VOXIMPLANT_KEY'));

    	var_dump(env('VOXIMPLANT_KEY'));

    	$params = new StartScenariosParams();

		$params->rule_id = 3026732;
		$params->script_custom_data = '+79671487799:+79152641893';

		$result = $voxApi->Scenarios->StartScenarios($params);

		// Show result
		var_dump($result);
    }
}