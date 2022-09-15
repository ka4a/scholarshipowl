<?php
namespace App\Queue\Jobs;

use Illuminate\Queue\Jobs\Job;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;
use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Support\Arr;

class PubSubJob extends Job implements JobContract
{
    /**
     * @var PubSubClient
     */
    protected $pubSub;
    /**
     * @var
     */
    protected $job;
    /**
     * @var \Google\Cloud\PubSub\Subscription
     */
    protected $subscription;

    /**
     * PubSubJob constructor.
     *
     * @param Container    $container
     * @param PubSubClient $pubSub
     * @param              $topic
     * @param              $subscription
     * @param              $job
     */
    public function __construct(Container $container, PubSubClient $pubSub, $topic, $subscription, $job)
    {
        $this->pubSub = $pubSub;
        $this->job = $job;
        $this->queue = $topic;
        $this->container = $container;
        $this->subscription = $this->pubSub->subscription($subscription, $this->queue);
    }

    /**
     * @inheritdoc
     */
    public function getJobId()
    {
        return md5(serialize($this->job));
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        return Arr::get($this->payload(), 'attempts');
    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        return base64_decode($this->job->data());
    }

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete()
    {
        parent::delete();
        $this->subscription->acknowledge($this->job);
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
        $this->subscription->modifyAckDeadline($this->job, $delay);
    }
}