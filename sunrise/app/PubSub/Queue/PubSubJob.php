<?php namespace App\PubSub\Queue;

use Illuminate\Container\Container;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Support\Arr;
use Google\Cloud\PubSub\Message;
use Google\Cloud\PubSub\Subscription;

/**
 * Class PubSubJob */
class PubSubJob extends Job implements JobContract
{
    /**
     * @var Message
     */
    protected $message;

    /**
     * @var Subscription
     */
    protected $subscription;

    /**
     * @var PubSubQueue
     */
    protected $pubsub;

    /**
     * PubSubJob constructor.
     *
     * @param Container $container
     * @param Subscription $subscription
     * @param PubSubQueue $pubsub
     * @param Message $message
     * @param string $queue
     * @param string $connectionName
     */
    public function __construct(
        Container $container,
        Subscription $subscription,
        PubSubQueue $pubsub,
        Message $message,
        $queue,
        $connectionName
    ) {
        $this->container = $container;
        $this->subscription = $subscription;
        $this->pubsub = $pubsub;
        $this->message = $message;
        $this->queue = $queue;
        $this->connectionName = $connectionName;
    }

    /**
     * Get the job identifier.
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->message->id();
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        return $this->payload()['attempts'] ?? null;
    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        return base64_decode($this->message->data());
    }

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete()
    {
        $this->subscription->acknowledge($this->message);
        parent::delete();
    }

    /**
     * Release the job back into the queue.
     *
     * @param  int   $delay
     * @return void
     */
    public function release($delay = 0)
    {
        parent::release($delay);
        $payload = $this->payload();
        $payload['attempts'] = isset($payload['attempts']) ? $payload['attempts'] + 1 : 1;
        $payload['runAt'] = time() + $delay;

        $this->pubsub->pushRaw(json_encode($payload), $this->queue);
        $this->subscription->acknowledge($this->message);
    }
}
