<?php
namespace App\Services\Marketing;


use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;

class PushnamiService
{

//curl -X PUT https://api.pushnami.com/api/push/v2/subscriber/variables  -H 'authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJwYWlkIjoiNWM0YTAxY2NlMWUzY2MwMDEwN2RhNTEwIiwiaWF0IjoxNTU1NDA1NjgzLCJleHAiOjE1ODY5NjMyODMsImlzcyI6Imh0dHBzOi8vYXBpLnB1c2huYW1pLmNvbSJ9.Do5fWximy_dnYKd8Asaobu7KKfzGIGQAUydJMrpaLDU' -F "psid=5cb339f82a0fd20a51c5cba3" -F "state=Automotive"   -F "l=78704"
    /**
     * @var Client
     */
    protected $client;

    protected $pushnamiEndpoint = 'https://api.pushnami.com/api/push/v2/subscriber/variables';

    protected $pushnamiSubscriptionIdField = 'psid';

    public function __construct() {
        $this->client = new Client();
    }

    public function updateSubscription() {

//
        $url = "https://api.pushnami.com/api/push/v2/subscriber/variables";

        $postdata = http_build_query(
            array(
                'psid' => '5cb848ce02aa9856643f6bf4',
                'state' => '123'
            )
        );

        $context_options = array (
            'http' => array (
                'method' => 'PUT',
                'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: " . strlen($postdata) . "\r\n"
                    . 'authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJwYWlkIjoiNWM0YTAxY2NlMWUzY2MwMDEwN2RhNTEwIiwiaWF0IjoxNTU1NDA1NjgzLCJleHAiOjE1ODY5NjMyODMsImlzcyI6Imh0dHBzOi8vYXBpLnB1c2huYW1pLmNvbSJ9.Do5fWximy_dnYKd8Asaobu7KKfzGIGQAUydJMrpaLDU',
                'content' => $postdata
            )
        );

        $context = stream_context_create($context_options);
        $response = file_get_contents($url, true, $context);


        dd('test', $response);

    }
}