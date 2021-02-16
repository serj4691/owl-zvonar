<?php

namespace App\Console\Commands;

use App\Operator;
use App\LeadUpload;
use App\Lead;
use App\BlacklistUpload;
use App\BlacklistPhone;
use Illuminate\Console\Command;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Voximplant\VoximplantApi;
use Voximplant\Resources\Params\StartScenariosParams;
use Illuminate\Support\Facades\Log;

class UploadBlacklist extends Command
{
	protected $signature = 'upload_blacklist';

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
        $files = BlacklistUpload::where('uploaded', 0)->get();

        foreach ($files as $file) {
            $content = file_get_contents('https://zvonar.take-make.ru/' . $file->file);
            $content = str_replace("\r", "", $content);
            $rows = explode("\n", $content);

            foreach ($rows as $row) {
                $data = explode(";", $row);

                if (count($data) < 2) $data = explode(",", $row);

                $lead = new BlacklistPhone;
                $lead->phone = $data[0];
                $lead->save();

                $file->uploaded++;
                
                var_dump($lead);
            } 
            $file->save();
        }

    }
}