<?php

namespace ScholarshipOwl\Domain\Payment;

use Illuminate\Queue\Jobs\Job;
use ScholarshipOwl\Data\DateHelper;
use ScholarshipOwl\Data\Service\IDDL;
use ScholarshipOwl\Domain\Payment\Event\PaymentRemoteEvent;

class QueuePaymentMessage
{

    const QUEUE_NAME = 'payment_message';

    const STATUS_PENDING = 'Pending';

    const STATUS_RUNNING = 'Running';

    const STATUS_SUCCESS = 'Success';

    const STATUS_FAILED = 'Failed';

    /**
     * @param string   $listenerClass
     * @param IMessage $message
     *
     * @return bool|string
     */
    public function push($listenerClass, IMessage $message)
    {
        $paymentMessageId = false;
        $serialized = serialize($message);

        $query = \DB::table(IDDL::TABLE_QUEUE_PAYMENT_MESSAGE)
            ->insert(array(
                'status' => static::STATUS_PENDING,
                'message' => $serialized,
                'listener' => $listenerClass,
                'created_at' => date(DateHelper::DEFAULT_FORMAT),
            ));

        if ($query) {
            $this->queue($paymentMessageId = \DB::getPdo()->lastInsertId());
        }

        return $paymentMessageId;
    }

    /**
     * @param int $id
     */
    public function retry($id)
    {
        if ($queuePaymentMessage = $this->findById($id)) {
            $this->queue($queuePaymentMessage['queue_payment_message_id']);
        }
    }

    /**
     * @param Job $job
     * @param array $data
     * @throws \Exception
     */
    public function fire(Job $job, array $data)
    {
        try {
            if (isset($data['queue_payment_message_id'])) {

                /**
                 * Added because seeing on production duplicate of transactions.
                 * And look like reason is because gate2shop sending DMN message in same moment
                 * with website redirect to success page
                 */
                if (\App::environment() !== 'testing') {
                    sleep(2);
                }

                $queuePaymentMessageId = $data['queue_payment_message_id'];
                if ($queuePaymentMessage = $this->findById($queuePaymentMessageId)) {

                    \DB::table(IDDL::TABLE_QUEUE_PAYMENT_MESSAGE)
                        ->where('queue_payment_message_id', '=', $queuePaymentMessageId)
                        ->update(array(
                            'lastrun_at' => date(DateHelper::DEFAULT_FORMAT),
                            'status' => static::STATUS_RUNNING,
                        ));

                    try {

                        $this->fireFromMessage($queuePaymentMessage, $job);

                        \DB::table(IDDL::TABLE_QUEUE_PAYMENT_MESSAGE)
                            ->where('queue_payment_message_id', '=', $queuePaymentMessageId)
                            ->update(array(
                                'status' => static::STATUS_SUCCESS,
                                'updated_at' => date(DateHelper::DEFAULT_FORMAT),
                            ));

                    } catch (\Exception $e) {
                        \DB::table(IDDL::TABLE_QUEUE_PAYMENT_MESSAGE)
                            ->where('queue_payment_message_id', '=', $queuePaymentMessageId)
                            ->update(array(
                                'status' => static::STATUS_FAILED,
                                'status_message' => $e->getMessage(),
                                'updated_at' => date(DateHelper::DEFAULT_FORMAT),
                            ));

                        throw $e;
                    }

                }

            } else {
                throw new \Exception("Running without payment message id.");
            }

            $this->deleteJob($job);
        } catch (\Exception $e) {
            $this->deleteJob($job);
            throw $e;
        }
    }

    /**
     * @param Job $job
     *
     * @return Job
     */
    protected function deleteJob(Job $job)
    {
        if (!$job->isDeletedOrReleased()) {
            $job->delete();
        }

        return $job;
    }

    /**
     * @param int    $id
     */
    protected function queue($id)
    {
        \Queue::push(get_called_class(), ['queue_payment_message_id' => $id], static::QUEUE_NAME);
    }

    /**
     * @param array $data
     * @param Job   $job
     */
    protected function fireFromMessage(array $data, Job $job)
    {
        if ($listenerClass = (isset($data['listener']) ? $data['listener'] : false)) {
            if (($message = @unserialize($data['message'])) && ($message instanceof IMessage)) {

                try {
                    $this->buildListener($listenerClass)
                        ->fireEventFromMessage($message, $job);
                } catch (FailedPaymentException $e) {}

            }
        }
    }

    /**
     * @param int $id
     * @return array
     * @throws \Exception
     */
    protected function findById(int $id)
    {
        $queuePaymentMessage = \DB::table(IDDL::TABLE_QUEUE_PAYMENT_MESSAGE)
            ->where('queue_payment_message_id', '=', $id)
            ->first();

        if (!$queuePaymentMessage) {
            throw new \Exception(sprintf("Queue payment message ID (%s) not found!", $id));
        }

        return (array) $queuePaymentMessage;
    }

    /**
     * @param $listenerClass
     *
     * @return PaymentRemoteEvent
     */
    protected function buildListener($listenerClass)
    {
        return new $listenerClass();
    }
}
