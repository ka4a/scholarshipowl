<?php
namespace Test\Services\PubSub;

use App\Services\PubSub\AbstractPubSubService;
use App\Services\PubSub\AccountService;
use App\Testing\TestCase;
use Google\Cloud\PubSub\Connection\ConnectionInterface;
use Google\Cloud\PubSub\PubSubClient;

abstract class PubSubServiceAbstractTest extends TestCase
{
    /**
     * @var AccountService
     */
    protected $service;

    /**
     * @var PubSubClient
     */
    protected $pubSubClient;


    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->mockPubSubClient($this->getService());
    }

    public abstract function getService();

    public function mockPubSubClient(AbstractPubSubService $service){
        $this->service = $service;

        $mockPubSubConnection = true; //set FALSE to push messages to real testing pubsub topic
        if ($mockPubSubConnection) {
            $pubSubClient = $this->service->getPubSubClient();
            $reflection = new \ReflectionClass($pubSubClient);
            $property = $reflection->getProperty('connection');
            $property->setAccessible(true);
            $connection = $this->prophesize(ConnectionInterface::class);
            $property->setValue($pubSubClient, $connection->reveal());
        }
    }
}
