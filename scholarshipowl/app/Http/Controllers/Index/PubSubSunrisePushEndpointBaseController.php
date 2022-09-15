<?php

namespace App\Http\Controllers\Index;

use Google\Cloud\PubSub\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PubSubSunrisePushEndpointBaseController extends Controller
{
    /**
     * @var Message
     */
    public $message;

    /**
     * @var array
     */
    public $rawMessage;


    /**
     * @var string
     */
    public $event;

    /**
     * Used to maintain message order. Return FALSE if message has time mark earlier then the previous one.
     * Such message must be discarded.
     *
     * @param string $cacheKey
     * @param int $value
     * @return bool
     */
    protected function maintainIdempotency(string $cacheKey, int $value): bool
    {
        $prevValue = \Cache::get($cacheKey);

        if (!$prevValue || $prevValue <  $value) {
            \Cache::put($cacheKey, $value, 24 * 60 * 60);

            return true;
        }

        return false;
    }

    /**
     * Validates PubSub message and creates a Message instance.
     * Response with 204 status code implies implicit message ack.
     *
     * @param Request $request
     */
    protected function handleRequest(Request $request): void
    {
        $secretKey = $request->route('secretKey');
        if (strcmp(config('pubsub.sunrise.push_endpoint_secret'), $secretKey) !== 0) {
            $msg = 'Invalid secret key';
            $this->logValidationError($msg);
            abort(403, $msg);
        }

        $rawMessage = $request->all();
        $this->rawMessage = $rawMessage;

        if (!isset($rawMessage['message'])) {
            $msg = 'massage may not be empty';
            $this->logValidationError($msg);
            abort(400, $msg);
        }

        if (!isset($rawMessage['message']['attributes']['timestamp'])) {
            $msg = 'massage.attributes must have a timestamp field';
            $this->logValidationError($msg);
            abort(400, $msg);
        }

        if (isset($rawMessage['message']['data'])) {
            $rawMessage['message']['data'] = base64_decode($rawMessage['message']['data']);
        }

        $this->message = new Message($rawMessage['message'], [
            'ackId' => isset($rawMessage['ackId']) ? $rawMessage['ackId'] : null,
            'subscription' => isset($rawMessage['subscription']) ? $rawMessage['subscription']: null
        ]);
    }

    /**
     * @param $message
     */
    protected function logValidationError($message)
    {
        \Log::warning(
            sprintf(
                'Sunrise PubSub message validation error: %s. PubSub message: %s ',
                $message,
                var_export($this->rawMessage, true)
            )
        );
    }
}

