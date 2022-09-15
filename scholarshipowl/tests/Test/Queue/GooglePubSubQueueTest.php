<?php

namespace Test\Queue;

use App\Jobs\MandrillTemplateJob;
use App\Providers\GooglePubSubServiceProvider;
use App\Queue\Connectors\PubSubConnector;
use App\Queue\PubSubQueue;
use App\Testing\TestCase;
use Google\Cloud\PubSub\Connection\ConnectionInterface;
use Google\Cloud\PubSub\Message;
use Google\Cloud\PubSub\PubSubClient;
use Google\Cloud\PubSub\Subscription;
use Google\Cloud\PubSub\Topic;
use Prophecy\Argument;
use ScholarshipOwl\Util\Mailer;

class GooglePubSubQueueTest extends TestCase
{
    protected $connection;

    protected $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->connection = $this->prophesize(ConnectionInterface::class);

        $this->client = new PubSubClientTest([
            'projectId' => 'project',
            'transport' => 'rest'
        ]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_pubsub_handle_job()
    {
        $this->mockingPubSubClient();

        $pubsub = app('queue')->connection('pubsub');
        $this->app['config']['queue.default'] = 'pubsub';
        Mailer::sendMandrillTemplate(
            Mailer::MANDRILL_SUBSCRIPTION_CREDIT_INCREASES, 1
        );
        $pop = $pubsub->pop();
        $commandName = $pop->payload()['data']['commandName'];
        $this->assertEquals($commandName, MandrillTemplateJob::class);
        $pop->fire();
    }

    public function test_pubsub_connector_available_after_service_boot()
    {
        $applicationMock = \Mockery::mock(\Application::class);
        $serviceProvider = new GooglePubSubServiceProvider($applicationMock);
        $serviceProvider->boot();

        $pubsub = app('queue')->connection('pubsub');
        $this->assertContainsOnlyInstancesOf(
            PubSubQueue::class,
            [$pubsub]
        );
    }

    public function test_create_topic()
    {
        $topicName = 'test-topic';
        $this->connection->createTopic(Argument::withEntry('foo', 'bar'))
            ->willReturn([
                'name' => 'projects/project/topics/'. $topicName
            ]);
        // Set this to zero to make sure we're getting the cached result
        $this->connection->getTopic(Argument::any())->shouldNotBeCalled();
        $this->client->setConnection($this->connection->reveal());
        $topic = $this->client->createTopic($topicName, [
            'foo' => 'bar'
        ]);
        $this->assertInstanceOf(Topic::class, $topic);
        $info = $topic->info();
        $this->assertEquals($info['name'], 'projects/project/topics/'. $topicName);
    }

    public function test_topic()
    {
        $topicName = 'test-topic';
        $this->connection->getTopic(Argument::any())
            ->willReturn([
                'name' => 'projects/project/topics/'. $topicName
            ])->shouldBeCalledTimes(1);

        $this->client->setConnection($this->connection->reveal());
        $topic = $this->client->topic($topicName);
        $this->assertInstanceOf(Topic::class, $topic);
        $info = $topic->info();
        $this->assertEquals($info['name'], 'projects/project/topics/'. $topicName);
    }

    public function test_subscription()
    {
        $this->connection->getSubscription(Argument::any())->shouldBeCalledTimes(1)->willReturn(['foo' => 'bar']);
        $this->client->setConnection($this->connection->reveal());
        $subscription = $this->client->subscription('subscription-name', 'topic-name');
        $info = $subscription->info();
        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals('bar', $info['foo']);
    }

    protected function mockingPubSubClient()
    {
        $fakeTopic = $this->createMock(Topic::class);
        $fakeTopic->expects($this->any())->method('publish')->will(
            $this->returnValue(
                [
                    'messageIds' =>
                        [
                            0 => '4205658617483',
                        ],
                ]
            )
        );

        $fakeSubscription = $this->createMock(Subscription::class);
        $fakeSubscription->expects($this->any())->method('pull')->will(
            $this->returnValue(
                [
                    new Message(
                        [
                            'data'        => 'eyJqb2IiOiJJbGx1bWluYXRlXFxRdWV1ZVxcQ2FsbFF1ZXVlZEhhbmRsZXJAY2FsbCIsImRhdGEiOnsiY29tbWFuZE5hbWUiOiJBcHBcXEpvYnNcXE1hbmRyaWxsVGVtcGxhdGVKb2IiLCJjb21tYW5kIjoiTzoyODpcIkFwcFxcSm9ic1xcTWFuZHJpbGxUZW1wbGF0ZUpvYlwiOjEwOntzOjEyOlwiXHUwMDAwKlx1MDAwMGFjY291bnRJZFwiO2k6MTtzOjExOlwiXHUwMDAwKlx1MDAwMHRlbXBsYXRlXCI7czoyOTpcInN1YnNjcmlwdGlvbi1jcmVkaXQtaW5jcmVhc2VzXCI7czo3OlwiXHUwMDAwKlx1MDAwMGRhdGFcIjthOjA6e31zOjU6XCJcdTAwMDAqXHUwMDAwdG9cIjtOO3M6MTA6XCJcdTAwMDAqXHUwMDAwc3ViamVjdFwiO047czo3OlwiXHUwMDAwKlx1MDAwMGZyb21cIjtOO3M6NjpcIlx1MDAwMCpcdTAwMDBqb2JcIjtOO3M6MTA6XCJjb25uZWN0aW9uXCI7TjtzOjU6XCJxdWV1ZVwiO047czo1OlwiZGVsYXlcIjtOO30ifSwiYXR0ZW1wdHMiOjF9',
                            'messageId'   => '4205658617483',
                            'publishTime' => '2017-12-14T20:27:31.008Z',
                            'attributes'  => [],
                        ], [
                            'ackId'        => 'RUFeQBJMNgJESVMrQwsqWBFOBCEhPjA-RVNEUAYWLF1GSFE3GQhoUQ5PXiM_NSAoRRIECBQFfH1xU1B1XVkaB1ENGXJ8Z3VvXBsJAUxTflVaEQ16bVxXOlUPGXd7ZH1sWxQFCkZ74fer669tZho9XxJLLD5-PTc',
                            'subscription' => null]
                    ),
                ]
            )
        );

        $pubSubClient = $this->createMock(PubSubClient::class);
        $pubSubClient->expects($this->any())->method('topic')->will(
            $this->returnValue($fakeTopic)
        );
        $pubSubClient->expects($this->any())->method('subscription')->will(
            $this->returnValue($fakeSubscription)
        );

        $pubSubQueue = new PubSubQueue(
            $pubSubClient, app('config')['queue.connections.pubsub']
        );

        $mockConnector = $this->createMock(PubSubConnector::class);
        $mockConnector->expects($this->any())->method('connect')->will(
            $this->returnValue($pubSubQueue)
        );

        app('queue')->addConnector(
            'pubsub', function () use ($mockConnector) {
                return $mockConnector;
            }
        );
    }
}


class PubSubClientTest extends PubSubClient
{
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
