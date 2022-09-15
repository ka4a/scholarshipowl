<?php namespace App\Listeners;

use App\Entity\FeatureSet;
use App\Entity\Package;
use App\Entity\SubscriptionAcquiredType;
use App\Events\Account\CreateAccountEvent;
use App\Events\Account\MissionCompletedEvent;
use App\Jobs\HasOffersPostbackJob;
use App\Payment\Events\SubscriptionAddEvent;
use App\Payment\Events\SubscriptionEvent;
use App\Payment\Events\SubscriptionPaymentEvent;
use App\Services\PaymentManager;
use Doctrine\ORM\EntityManager;
use Illuminate\Events\Dispatcher;
use ScholarshipOwl\Data\Service\Marketing\AccountHasoffersFlagService;
use ScholarshipOwl\Data\Service\Marketing\MarketingSystemService;

class FreemiumSubscriber
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var PaymentManager
     */
    protected $pm;

    /**
     * FreemiumSubscriber constructor.
     * @param EntityManager $em
     * @param PaymentManager $pm
     */
    public function __construct(EntityManager $em, PaymentManager $pm)
    {
        $this->em = $em;
        $this->pm = $pm;
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(CreateAccountEvent::class,static::class.'@accountCreate');
    }

    /**
     * @param CreateAccountEvent $event
     */
    public function accountCreate(CreateAccountEvent $event)
    {
        $account = $event->getAccount();

        if (!$account->isMember()) {
            $fset = \App\Entity\FeatureSet::config();

            if ($fset->getName()  == FeatureSet::FREEMIUM_MVP_NAME && !$account->isFreemium()) {
                $freemiumPackage = $this->em->getRepository(Package::class)->findOneBy(['alias' => Package::FREEMIUM_MVP_ALIAS]);
                if ($freemiumPackage->isFreemium()) {
                    try {
                        $this->pm->applyPackageOnAccount($account, $freemiumPackage, \App\Entity\SubscriptionAcquiredType::FREEBIE);
                    } catch (\Exception $e) {
                        handle_exception($e);
                    }
                }
            }
        }
    }
}
