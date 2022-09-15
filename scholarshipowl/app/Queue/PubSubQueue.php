<?php

namespace App\Queue;

use App\Queue\Jobs\PubSubJob;
use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Queue\Queue;
use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Support\Arr;

class PubSubQueue extends Queue implements QueueContract{

    /**
     * The Google PubSub Instance
     *
     * @var \Google\Cloud\PubSub\PubSubClient;
     */
    protected $pubSub;
    /**
     * @var string;
     */
    protected $defaultTopic;
    /**
     * @var string
     */
    protected $defaultSubscription;
    /**
     * @var string
     */
    protected $defaultTTL = 100;

    /**
     * PubSubQueue constructor.
     *
     * @param PubSubClient $pubSub
     * @param              $config
     */
    public function __construct(PubSubClient $pubSub, $config)
    {
        $this->pubSub = $pubSub;
        $this->defaultTopic = $config['default_topic'];
        $this->defaultSubscription = $config['default_subscription'];
        $this->defaultTTL = $config['default_ttl'];
    }

    /**
     * Get the size of the topic.
     *
     * @param  string  $topic
     * @return int
     */
    public function size($topic = null)
    {
        return count($this->getSubscription($topic)->pull());
    }

    /**
     * Return current PubSub subscription object
     * @param null $topic PubSub topic name
     *
     * @return \Google\Cloud\PubSub\Subscription
     */
    protected function getSubscription($topic = null)
    {
        return $this->pubSub->subscription($this->defaultSubscription, $topic ?: $this->defaultTopic);
    }

    /**
     * Push a new job onto the queue.
     * @param string $job
     * @param string $data
     * @param null   $topic
     *
     * @return mixed
     */
    public function push($job, $data = '', $topic = null)
    {
        return $this->pushRaw($this->createPayload($job, $data), $topic);
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param  string  $payload
     * @param  string  $topic
     * @param  array   $options
     * @return mixed
     */
    public function pushRaw($payload, $topic = null, array $options = [])
    {
        $topic = $this->pubSub->topic($topic ?: $this->defaultTopic);
        $response = $topic->publish([
            'data' => base64_encode($payload),
            'attributes' => null
        ]);

        return Arr::get($response, 'messageIds.0');
    }
    /**
     * Create a payload string from the given job and data.
     *
     * @param  string $job
     * @param  mixed $data
     * @param  string $topic
     * @return string
     */
    protected function createPayload($job, $data = '', $topic = null)
    {
        // add property on the fly
        $job->tries = 1;

        return parent::createPayload($job, $data);
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  \DateTime|int  $delay
     * @param  string  $job
     * @param  mixed   $data
     * @param  string  $topic
     * @return mixed
     */
    public function later($delay, $job, $data = '', $topic = null)
    {
        $topicDelay = $this->getTime() + $this->getSeconds($delay);
        $payload = $this->createPayload($job, $data);
        $topic = $this->pubSub->topic($topic ?: $this->defaultTopic);

        $response = $topic->publish(
            [
                'data'       => base64_encode($payload),
                'attributes' => null
            ],
            [
                'publishTime' => $topicDelay
            ]
        );

        return Arr::get($response, 'messageIds.0');
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param  string  $topic
     * @return PubSubJob|null
     */
    public function pop($topic = null)
    {
        $subscription = $this->getSubscription($topic);
        $pullOptions = [
            'returnImmediately' => true,
            'maxMessages' => 1
        ];
        $messages = $subscription->pull($pullOptions);
        if (count($messages) > 0) {
                //set subscription in "read" status
                $subscription->modifyAckDeadline(
                    $messages[0], $this->defaultTTL
                );

                return new PubSubJob(
                    $this->container,
                    $this->pubSub,
                     $topic ?: $this->defaultTopic,
                    $this->defaultSubscription,
                    $messages[0]
                );
            }
    }

}