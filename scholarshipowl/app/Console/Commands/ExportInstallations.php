<?php

namespace App\Console\Commands;

use App\Entity\Installations;
use Illuminate\Console\Command;

class ExportInstallations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:export_installations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exports all the installations to one signal service';

    /**
     * Create a new command instance.
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
        $installations = \EntityManager::getRepository(Installations::class)->findAll();
        $exportData = [];

        /** @var Installations $installation */
        foreach ($installations as $installation) {
            $exportData[] = [
                'app_id' => "a370da04-e7ec-4ba5-bca2-4346dd4a8905",
                'identifier' => $installation->getDeviceToken(),
                'language' => "en",
                'timezone' => "-28800",
                'device_os' => "10.0",
                'device_type' => "0",
                "tags" => [
                    $installation->getAccount()->getProfile()->getFirstName() => $installation->getAccount()
                        ->getProfile()->getLastName()
                ]
            ];
        }
        
        $ch = curl_init();
        foreach ($exportData as $item) {
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/players");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($item));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            print("\nresponse:");
            print($response);
        }

        curl_close($ch);

        $this->info('Installations was successfully exported');
    }
}
