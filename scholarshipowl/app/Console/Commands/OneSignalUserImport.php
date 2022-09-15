<?php

namespace App\Console\Commands;

use App\Entity\Account;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use App\Entity\OnesignalAccount;
use App\Entity\Installations;

class OneSignalUserImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onesignal:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import user from OneSignal to database';


    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @var int
     */
    protected $startFrom = 0;

    /**
     * @var int
     */
    protected $amount = 0;

    /**
     * @var int
     */
    protected $limit = 300;

    /**
     * MailboxListen constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = new GuzzleClient(['headers' => [
            'Content-Type'          => 'application/json',
            'Authorization: Basic ' => config('onesignal.mobile.api_key'),
            'timeout'               => 1.0,
        ]]);
    }

    /**
     * @param int $offset
     * @return mixed
     */
    protected function makeRequest(int $offset = 0)
    {
        $this->info("Making request to generate users ");
        $response = $this->client->get('https://onesignal.com/api/v1/players?app_id=' .
            config('onesignal.mobile.app_id') .
            '&limit='  . $this->limit .
            '&offset=' . $offset);

        if ($response->getStatusCode() == 200) {
            $this->info("Success!");
            return json_decode($response->getBody());
        } else {
            $this->info("Bad response.");
            return false;
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = $this->makeRequest();
        if ($data) {
            $this->amount = $data->total_count;
            $offset = $this->limit;
            $i = 0;
            while ($this->amount > 0) {
                if ($data) {
                    foreach ($data->players as $player) {
                        /** @var Installations $user */
                        $user = \EntityManager::getRepository(Installations::class)->findOneBy(['deviceToken' => $player->identifier]);
                        if ($user) {
                            ++$i;
                            /** @var Account $account */
                            $account = $user->getAccount();
                            $mobile = new OnesignalAccount($account, $player->id, "mobile");
                            \EntityManager::persist($mobile);
                        } else {
                            \Log::error('User with such token not found. ' . $player->identifier);
                        }
                    }
                    \EntityManager::flush();
                    $this->info('Saved portion of ' . $this->limit . ' users');

                    $this->amount -= $this->limit;
                    $offset += $this->limit;

                    if ($this->amount > 0) {
                        $data = $this->makeRequest($offset);
                    }
                }
            }

            $this->info("Total saved users: " . $i);
            if ($this->amount > $i) {
                $this->info("Users not exist: " . $this->amount - $i);
            }
        }
    }
}
