<?php namespace Test\Payment;

use App\Entity\Queue\PaymentMessage;
use ScholarshipOwl\Domain\Payment\Event\PaymentRemoteEvent;
use ScholarshipOwl\Domain\Payment\QueuePaymentMessage;
use ScholarshipOwl\Domain\Payment\IMessage;

use App\Testing\TestCase;
use Illuminate\Queue\Jobs\Job;

use \Mockery as m;

class QueuePaymentMessageTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        static::$truncate[] = 'queue_payment_message';
    }

    public function seeMessageInDatabase($id, $status, $listener)
    {
        $this->assertDatabaseHas('queue_payment_message', [
            'queue_payment_message_id' => $id,
            'listener' => $listener,
            'status' => $status,
        ]);
    }

    public function testPushAndRetryPaymentMessage()
    {
        \Queue::shouldReceive('push')
            ->twice()
            ->with(QueuePaymentMessage::class, m::on(function($value) {
                $this->assertArrayHasKey('queue_payment_message_id', $value);
                $this->seeMessageInDatabase($value['queue_payment_message_id'], PaymentMessage::STATUS_PENDING, 'TestListener');

                return true;
            }), QueuePaymentMessage::QUEUE_NAME);

        $message = $this->getMockBuilder(IMessage::class)->getMock();

        $queuePaymentMessage = new QueuePaymentMessage();
        $id = $queuePaymentMessage->push('TestListener', $message);
        $queuePaymentMessage->retry($id);
    }

    public function testFirePaymentMessageSuccess()
    {
        \Queue::shouldReceive('push')->once();

        $jobMock = m::mock(Job::class)
            ->shouldReceive('delete')->once()
            ->shouldReceive('isDeletedOrReleased')->once()->andReturn(false)
            ->getMock();

        $message = $this->getMockBuilder(IMessage::class)->getMock();
        $listener = m::mock(PaymentRemoteEvent::class);

        $queuePaymentMessage = m::mock(QueuePaymentMessage::class)->makePartial();
        $queuePaymentMessage->shouldAllowMockingProtectedMethods()
            ->shouldReceive('buildListener')->once()
            ->with(get_class($listener))
            ->andReturn($listener);

        /** @var QueuePaymentMessage $queuePaymentMessage */
        $paymentMessageId = $queuePaymentMessage->push(get_class($listener), $message);

        $listener->shouldReceive('fireEventFromMessage')->once()
            ->with(m::on(function($Message) use ($message, $paymentMessageId, $listener) {
                $this->assertInstanceOf(get_class($Message), $message);
                $this->seeMessageInDatabase(
                    $paymentMessageId,
                    PaymentMessage::STATUS_RUNNING,
                    get_class($listener)
                );

                return true;
            }), $jobMock);

        $queuePaymentMessage->fire($jobMock, ['queue_payment_message_id' => $paymentMessageId]);

        $this->seeMessageInDatabase(
            $paymentMessageId,
            PaymentMessage::STATUS_SUCCESS,
            get_class($listener)
        );
    }


    public function testFireAndFail()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Test exception');
        \Queue::shouldReceive('push')->once();

        $jobMock = m::mock(Job::class)
            ->shouldReceive('delete')->once()
            ->shouldReceive('isDeletedOrReleased')->once()->andReturn(false)
            ->getMock();

        $message = $this->getMockBuilder(IMessage::class)->getMock();
        $listener = m::mock(PaymentRemoteEvent::class);

        /** @var m\MockInterface|QueuePaymentMessage $queuePaymentMessage */
        $queuePaymentMessage = m::mock(QueuePaymentMessage::class)->makePartial();
        $queuePaymentMessage->shouldAllowMockingProtectedMethods()
            ->shouldReceive('buildListener')->once()
            ->with(get_class($listener))
            ->andReturn($listener);

        $paymentMessageId = $queuePaymentMessage->push(get_class($listener), $message);

        $listener->shouldReceive('fireEventFromMessage')->once()
            ->with(m::on(function($Message) use ($message, $paymentMessageId, $listener) {
                $this->assertInstanceOf(get_class($Message), $message);
                $this->seeMessageInDatabase(
                    $paymentMessageId,
                    PaymentMessage::STATUS_RUNNING,
                    get_class($listener)
                );

                return true;
            }), $jobMock)
            ->andThrow(new \Exception('Test exception'));

        $queuePaymentMessage->fire($jobMock, ['queue_payment_message_id' => $paymentMessageId]);

        $this->assertEmpty($paymentMessageId);
        $this->seeMessageInDatabase(
            $paymentMessageId->getPaymentMessageId(),
            PaymentMessage::STATUS_FAILED,
            get_class($listener)
        );
    }

}
