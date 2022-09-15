<?php namespace App\Listeners;

use App\Entity\PaymentFsetHistory;
use App\Payment\Events\PaymentsEvent;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

class PaymentsSubscribe implements ShouldQueue
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * PaymentsSubscribe constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(PaymentsEvent::class, static::class.'@onPaymentEvent');
    }

    public function onPaymentEvent(PaymentsEvent $event){
        $currentFset = $event->getFset();
        $fsetTitle = $currentFset->getName();
        $fsetId = $currentFset->getId();

        $paymentFsetLog = new PaymentFsetHistory($event->getAccount()->getAccountId(), $fsetId, $fsetTitle, Carbon::now());

        $this->em->persist($paymentFsetLog);
        $this->em->flush($paymentFsetLog);

    }

}
