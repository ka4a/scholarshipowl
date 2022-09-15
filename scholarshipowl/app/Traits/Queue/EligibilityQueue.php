<?php namespace App\Traits\Queue;

use Illuminate\Queue\QueueManager;

trait EligibilityQueue
{
    /**
     * @param QueueManager $queue
     * @param string       $method
     * @param array        $arguments
     *
     * @return mixed
     */
    public function queue(QueueManager $queue, $method, $arguments)
    {
        return $queue->connection()->pushOn('eligibility', $method, $arguments);
    }
}
