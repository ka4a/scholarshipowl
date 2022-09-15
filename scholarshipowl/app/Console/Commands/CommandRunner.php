<?php

namespace App\Console\Commands;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Console\Command;


class CommandRunner extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'command:run
        {--onBehalfOfAccount=0 : Account to prefix output cache with},
        {--cmd="php artisan" : Command to run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs a command as detached process, buffers output into cache.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $cmd = $this->option('cmd');
        $accountId = $this->option('onBehalfOfAccount');
        $cacheCommandKey = "command-runner-command-{$accountId}".md5($cmd);;
        $cacheDataKey = "command-runner-result-{$accountId}-".md5($cmd);
        $cacheChunkKey = "command-runner-chunk-{$accountId}-".md5($cmd);
        $cacheFinishKey = "command-runner-finish-{$accountId}-".md5($cmd);

        \Log::info("Running command [ {$cmd} ] on behalf of account [ {$accountId} ]");

        $pullDataArray = function () use ($cacheDataKey) {
            $dataString = \Cache::get($cacheDataKey);

            if ($dataString) {
                $dataArray = json_decode($dataString);
            } else {
                $dataArray = [];
            }

            return $dataArray;
        };

        $putDataArray = function (array $data) use ($cacheDataKey) {
            $dataString = json_encode($data);
            \Cache::put($cacheDataKey, $dataString, 60);
        };

        $isCommandRunning = (bool)\Cache::get($cacheCommandKey);

        if ($isCommandRunning) {
            return;
        } else {
            \Cache::put($cacheCommandKey, 1, 60 * 60);
            $handle = popen($cmd, 'r');

            if (is_resource($handle)) {
                while ($s = fgets($handle)) {
                    $dataArray = $pullDataArray();
                    $dataArray[] = $s;
                    $putDataArray($dataArray);
                }

                fclose($handle);
            }

            sleep(1);
            \Cache::put($cacheFinishKey, $cacheCommandKey, 60);
            \Cache::delete($cacheCommandKey);
        }
    }
}
