<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Mautic\Auth\OAuth;
use Mautic\Exception\AuthorizationRequiredException;

class MauticAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mautic:auth
        {--token= : "oauth_token" that you get on success auth response.}
        {--verifier= : "oauth_verifier" that you get on success auth response.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mautic helper for OAuth1a authorization.';

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
     * @return void
     * @throws \Mautic\Exception\IncorrectParametersReturnedException
     */
    public function handle()
    {
//        $baseUrl = config('services.mautic.base_url');
//        $timestamp = time();
//
//        $client = new Client();
//
//        $response = $client->post("$baseUrl/oauth1/v1/request_token", [
//            'headers' => [
//                'Authorization' => sprintf('OAuth
//                      oauth_consumer_key="%s",
//                      oauth_nonce="%s",
//                      oauth_signature="%s",
//                      oauth_signature_method="HMAC-SHA1",
//                      oauth_timestamp="%s",
//                      oauth_version="1.0"',
//                    config('services.mautic.public_key'),
//                    $this->generateNonce(),
//                    $signature,
//                    $timestamp
//                )
//            ]
//        ]);
//
//        dd($response->getBody());

        session_start([
            'save_path' => storage_path('framework/sessions'),
        ]);

        $auth = new OAuth();
        $auth->setup(
            config('services.mautic.base_url'),
            'OAuth1a',
            config('services.mautic.public_key'),
            config('services.mautic.secret_key')
        );

        if (config('app.debug')) {
            $auth->enableDebugMode();
        }

        if ($this->hasOption('token') && $this->hasOption('verifier')) {
            $_SESSION['oauth']['token'] = $this->option('token');
            $_GET['oauth_token'] = $this->option('token');
            $_GET['oauth_verifier'] = $this->option('verifier');
        }

        try {
            $auth->validateAccessToken(false);
        } catch (AuthorizationRequiredException $e) {
            $this->info('Please open next link in your browser:');
            $this->warn($e->getAuthUrl());
            $this->info('After allowing authorization you will get file with "oauth_token" and "oauth_verifier"'
                .' please enter them as options for the command.');
            dd($_SESSION);
        }
        session_abort();
    }

    /**
     * OAuth1.0 nonce generator
     *
     * @param int $bits
     *
     * @return string
     */
    private function generateNonce($bits = 64)
    {
        $result          = '';
        $accumulatedBits = 0;
        $random          = mt_getrandmax();

        for ($totalBits = 0; $random != 0; $random >>= 1) {
            ++$totalBits;
        }

        $usableBits = intval($totalBits / 8) * 8;

        while ($accumulatedBits < $bits) {
            $bitsToAdd = min($totalBits - $usableBits, $bits - $accumulatedBits);
            if ($bitsToAdd % 4 != 0) {
                // add bits in whole increments of 4
                $bitsToAdd += 4 - $bitsToAdd % 4;
            }

            // isolate leftmost $bits_to_add from mt_rand() result
            $moreBits = mt_rand() & ((1 << $bitsToAdd) - 1);

            // format as hex (this will be safe)
            $format_string = '%0'.($bitsToAdd / 4).'x';
            $result .= sprintf($format_string, $moreBits);
            $accumulatedBits += $bitsToAdd;
        }

        return $result;
    }
}
