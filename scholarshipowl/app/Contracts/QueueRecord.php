<?php namespace App\Contracts;

/**
 * Interface QueueRecord
 *
 * Used for saving queue results in DB
 */
interface QueueRecord
{
    const STATUS_PENDING = 'Pending';

    const STATUS_RUNNING = 'Running';

    const STATUS_SUCCESS = 'Success';

    const STATUS_FAILED = 'Failed';

    /**
     * Get id of queue record
     *
     * @return mixed
     */
    public function getId();

    /**
     * Set queue status
     *
     * @param string $status
     *
     * @return string
     */
    public function setStatus($status);

    /**
     * Get queue status
     *
     * @return string
     */
    public function getStatus();

    /**
     * Create new queue record
     *
     * @param string $queue
     * @param array  $data
     *
     * @return static
     */
    static public function create($queue, array $data);

    /**
     * Mark record as started
     *
     * @return $this
     */
    public function start();

    /**
     * Mark record as success
     *
     * @return $this
     */
    public function success();

    /**
     * Mark record as failed
     *
     * @return $this
     */
    public function failed();
}
