<?php namespace App\Entity\Traits;

use \App\Contracts\QueueRecord as QueueRecordContract;

trait QueueRecord
{
    /**
     * Create new queue record
     *
     * @param string $queue
     * @param array  $data
     *
     * @return static
     */
    static public function create($queue, array $data)
    {
        $record = new static($queue, $data);

        \EntityManager::persist($record);
        \EntityManager::flush($record);

        return $record;
    }

    /**
     * Mark record as started
     *
     * @return $this
     */
    public function start()
    {
        $this->setStatus(QueueRecordContract::STATUS_RUNNING);

        \EntityManager::persist($this);
        \EntityManager::flush($this);

        return $this;
    }

    /**
     * Mark record as success
     *
     * @return $this
     */
    public function success()
    {
        $this->setStatus(QueueRecordContract::STATUS_SUCCESS);

        \EntityManager::persist($this);
        \EntityManager::flush($this);

        return $this;
    }

    /**
     * Mark record as failed
     *
     * @return $this
     */
    public function failed()
    {
//        $this->setStatus(QueueRecordContract::STATUS_FAILED);

//        \EntityManager::persist($this);
//        \EntityManager::flush($this);

        return $this;
    }
}
