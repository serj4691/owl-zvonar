<?php

namespace App\Console\Commands;

use App\Operator;
use App\LeadUpload;
use App\Lead;
use Illuminate\Console\Command;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Voximplant\VoximplantApi;
use Voximplant\Resources\Params\StartScenariosParams;
use Illuminate\Support\Facades\Log;

class UploadLeads extends Command
{
	protected $signature = 'upload_leads';

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
        $files = LeadUpload::where('uploaded', 0)->get();

        foreach ($files as $file) {
            $content = file_get_contents('https://zvonar.take-make.ru/' . $file->file);
            $content = str_replace("\r", "", $content);
            $rows = explode("\n", $content);

            foreach ($rows as $row) {
                $data = explode(";", $row);

                if (count($data) < 2) $data = explode(",", $row);

                if (!$data[0]) continue;

                $lead = new Lead;

                $lead->callcenter_id = $file->callcenter_id;
                $lead->phone = $data[0];
                $lead->name = isset($data[1]) ? $data[1] : "";
                $lead->description = '';
                $lead->base_id = $file->id;
                $lead->save();
                $file->uploaded++;
                
                var_dump($lead);


            } 
            $file->save();
        }

    }
}