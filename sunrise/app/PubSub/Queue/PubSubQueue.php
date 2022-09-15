<?php namespace App\PubSub\Queue;

use App\PubSub\Queue\PubSubJob;
use Google\Cloud\Core\Exception\NotFoundException;
use Google\Cloud\PubSub\Message;
use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Queue\InvalidPayloadException;
use Illuminate\Queue\Queue;
use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class PubSubQueue extends Queue implements QueueContract
{
    const DEFAULT_QUEUE = 'default';

    /**
     * The Google PubSub Instance
     *
     * @var \Google\Cloud\PubSub\PubSubClient;
     */
    protected $pubsub;

    /**
     * @var string;
     */
    protected $prefix = 'laravel';

    /**
     * @var string
     */
    protected $ttl = 100;

    /**
     * @var string
     */
    protected $connection = 'default';

    /**
     * @var string
     */
    protected $queue = 'default';

    /**
     * PubSubQueue constructor.
     *
     * @param PubSubClient $pubSub
     * @param              $config
     */
    public function __construct(PubSubClient $pubSub, $config)
    {
        $this->pubsub = $pubSub;

        if (isset($config['queue'])) {
            $this->queue = $config['queue'];
        }

        if (isset($config['connection'])) {
            $this->connection = $config['connection'];
        }

        if (isset($config['prefix'])) {
            $this->prefix = $config['prefix'];
        }

        if (isset($config['ttl'])) {
            $this->ttl = intval($config['ttl']);
        }
    }

    /**
     * Create topic and subscription if not exists on Google Cloud.
     */
    public function setup()
    {
        $topic = $this->getPubSubTopic();
        if (!$topic->exists()) {
            $topic->create();
        }

        $subscription = $this->getPubSubSubscription();
        if (!$subscription->exists()) {
            $subscription->create();
        }
    }

    /**
     * Get the size of the topic.
     *
     * @param  string  $queue
     * @return int
     */
    public function size($queue = null)
    {
        return count($this->getPubSubSubscription($queue)->pull());
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param  string  $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    public function pop($queue = null)
    {
        $subscription = $this->getPubSubSubscription($queue);
        $messages = $subscription->pull([
            'returnImmediately' => true,
            'maxMessages' => 1
        ]);

        if (count($messages) > 0) {

            $job = new PubSubJob(
                $this->container,
                $subscription,
                $this,
                $messages[0],
                $this->queue,
                $this->connection
            );

            if (!empty($job->payload()['runAt'])) {
                if ($job->payload()['runAt'] >= time()) {
                    $subscription->modifyAckDeadline($messages[0], $job->payload()['runAt'] - time());
                    return null;
                }
            }

            //set subscription in "read" status
            $subscription->modifyAckDeadline($messages[0], $this->ttl);

            return $job;
        }

        return null;
    }

    /**
     * @param object|string $job
     * @param string $data
     * @param null $queue
     * @return mixed
     */
    public function push($job, $data = '', $queue = null)
    {
        return $this->pushRaw($this->createPayload($job, $data), $queue);
    }

    /**
     * @param \DateInterval|\DateTimeInterface|int $delay
     * @param object|string $job
     * @param string $data
     * @param null $queue
     * @return mixed
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        $publishTime = ($delay instanceof \DateTime) ?
            $delay->getTimestamp() : time() + $delay;

        return $this->pushRaw($this->createPayload($job, $data, $publishTime), $queue);
    }

    /**
     * @param string $payload
     * @param string $queue
     * @param array  $options
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        $topic = $this->getPubSubTopic($queue);
        $message = [ 'data' => base64_encode($payload) ];
        return Arr::get($topic->publish($message, $options), 'messageIds.0');
    }

    /**
     * Create a payload string from the given job and data.
     *
     * @param  string  $job
     * @param  mixed   $data
     * @param  int     $runAt
     * @return string
     *
     * @throws \Illuminate\Queue\InvalidPayloadException
     */
    protected function createPayload($job, $data = '', $runAt = null)
    {
        $payload = $this->createPayloadArray($job, $data);
        $payload['attempts'] = 0;
        $payload['runAt'] = $runAt;

        $payload = json_encode($payload);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidPayloadException(
                'Unable to JSON encode payload. Error code: '.json_last_error()
            );
        }

        return $payload;
    }

    /**
     * @param null $queue
     * @return \Google\Cloud\PubSub\Subscription
     */
    private function getPubSubSubscription($queue = null)
    {
        return $this->pubsub->subscription($this->getSubscriptionName($queue), $this->getTopicName($queue));
    }

    /**
     * @param null $queue
     * @return \Google\Cloud\PubSub\Topic
     */
    private function getPubSubTopic($queue = null)
    {
        return $this->pubsub->topic($this->getTopicName($queue));
    }

    /**
     * Google Cloud Pub\Sub topic name.
     *
     * @param string|null $queue
     * @return string
     */
    public function getTopicName($queue = null)
    {
        return App::environment() . '.' . $this->prefix . ($queue ?: static::DEFAULT_QUEUE);
    }

    /**
     * Google Cloud Pub\Sub subscription name.
     *
     * @param string|null $queue
     * @return string
     */
    public function getSubscriptionName($queue = null)
    {
        return App::environment() . '.subscription.' . $this->prefix . ($queue ?: static::DEFAULT_QUEUE);
    }
}
