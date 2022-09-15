<?php namespace App\PubSub\Subscribers;

use Doctrine\ORM\EntityManager;
use Google\Cloud\PubSub\PubSubClient;

class ApplicationsSubscriber
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var PubSubClient
     */
    protected $pubsub;

    /**
     * ApplicationsSubscriber constructor.
     * @param EntityManager $em
     * @param PubSubClient $pubsub
     */
    public function __construct(EntityManager $em, PubSubClient $pubsub)
    {
        $this->em = $em;
        $this->pubsub = $pubsub;
    }

}
