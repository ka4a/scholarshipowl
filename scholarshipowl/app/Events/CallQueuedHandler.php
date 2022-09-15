<?php namespace App\Events;

use App\Contracts\QueueRecord;
use Illuminate\Contracts\Queue\Job;
use \Illuminate\Events\CallQueuedHandler as BaseQeuedHanlder;

class CallQueuedHandler extends BaseQeuedHanlder
{
    /**
     * @param array $data
     *
     * @return QueueRecord
     */
    public function record(array $data)
    {
        return \EntityManager::findById($data['record'], $data['id']);
    }

    public function call(Job $job, array $data)
    {
        $record = $this->record($data)->start();

        parent::call($job, $data);

        $record->success();
    }

    public function failed(array $data)
    {
        $this->record($data)->failed();

        parent::failed($data);
    }
}
