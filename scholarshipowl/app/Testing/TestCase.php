<?php namespace App\Testing;

use App\Entity\Account;
use App\Entity\FeaturePaymentSet;
use App\Entity\FeatureSet;
use App\Services\Account\AccountLoginTokenService;
use App\Services\EligibilityCacheService;
use App\Services\Mailbox\MailboxService;
use App\Services\ScholarshipService;
use App\Testing\Traits\EntityGenerator;
use App\Testing\Traits\JsonResponseAsserts;
use App\Listeners\AccountPubSubPublisher;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Google\Cloud\PubSub\PubSubClient;
use Google\Cloud\PubSub\Topic;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Mockery as m;

class TestCase extends BaseTestCase
{
    use EntityGenerator;
    use JsonResponseAsserts;

    /**
     * List of tables that should be truncated on tear down
     * @var array
     */
    public static $truncate = [
        'eligibility_cache',
        'account',
        'profile'
    ];


    /**
     * @var
     */
    protected $storageMock;

    /**
     * @var Filesystem|m\Mock
     */
    protected $cloudMock;

    /**
     * @var string
     */
    protected $baseUrl = 'http://scholarship.test';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }

    public function setUp(): void
    {
        ini_set('memory_limit', '1500M');

        parent::setUp();

        $this->em = $this->app->make(EntityManager::class);

        $this->withoutMiddleware(ThrottleRequests::class);

        $this->cloudMock = m::mock(Filesystem::class)
            ->shouldIgnoreMissing()
            ->shouldReceive('get')->zeroOrMoreTimes()
            ->andReturnUsing(function($path) {
                $path = storage_path(ltrim($path, '/'));

                if (file_exists($path)) {
                    return file_get_contents($path);
                }

                return 'testing';
            })
            ->shouldReceive('put')->zeroOrMoreTimes()
            ->andReturnUsing(function($path, $content, $visibility) {
                $path = storage_path(ltrim($path, '/'));

                if (!is_dir(dirname($path))) {
                    mkdir(dirname($path), 0770, true);
                }

                return file_put_contents($path, $content);
            })
            ->shouldReceive('getDriver')->andReturnUsing(function() {
                return m::mock(Filesystem::class)->shouldIgnoreMissing()
                    ->shouldReceive('put')->zeroOrMoreTimes()
                    ->andReturnUsing(function () {
                        return true;
                    })->getMock();
            })
            ->getMock();

        $this->storageMock = m::mock(FilesystemManager::class)
            ->shouldReceive('disk')->with('gcs')->andReturnUsing(function() {
                return $this->cloudMock;
            })
            ->getMock();

        \Storage::swap($this->storageMock);


        $this->app->singleton(\App\Services\PubSub\AccountService::class, function(Container $app) {
            $pubSub = m::mock(PubSubClient::class)->shouldReceive('topic')->zeroOrMoreTimes()
                ->andReturnUsing(function () {
                    $topic = m::mock(Topic::class)->shouldReceive('publish')->zeroOrMoreTimes()
                        ->andReturnUsing(function () {
                            return 'publish';
                        })->getMock();

                    return $topic;
                })->getMock();

            return new \App\Services\PubSub\AccountService(
                $pubSub,
                $app->make(ManagerRegistry::class),
                $app->make(ScholarshipService::class),
                $app->make(AccountLoginTokenService::class),
                $app->make(EligibilityCacheService::class),
                $app->make(MailboxService::class)
            );
        });

        $this->setMockPubSubAccountPublisher();
    }

    public function tearDown(): void
    {
        $this->truncateTables();

        $this->beforeApplicationDestroyed(function () {
            foreach ($this->app->make('db')->getConnections() as $connection) {
                $connection->disconnect();
            }

            /** @var ManagerRegistry $registry */
            $registry = $this->app->make(ManagerRegistry::class);
            /** @var EntityManager $entityManager */
            foreach ($registry->getManagers() as $entityManager) {
                $entityManager->getConnection()->close();
                $entityManager->clear();
                $entityManager->close();
            }
        });

        parent::tearDown();
    }

    /**
     * Mock the event dispatcher so all events are silenced and collected.
     *
     * @return $this
     */
    protected function withoutEvents()
    {
        $mock = \Mockery::mock('Illuminate\Contracts\Events\Dispatcher');

        $mock->shouldReceive('dispatch')->andReturnUsing(function ($called) {
            $this->firedEvents[] = $called;
        });

        \Event::swap($mock);

        return $this;
    }


    public static function tearDownAfterClass(): void
    {
        static::truncateTables();

        parent::tearDownAfterClass();
    }

    public static function truncateTables()
    {
        if (!empty(static::$truncate)) {
            \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
            $tables = array_unique(static::$truncate);
            foreach ($tables as $table) {
                \DB::table($table)->truncate();
            }
            \DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
            static::$truncate = [];
            FeatureSet::set(null);
        }
    }

    public function actingAsAccount(Account $account = null)
    {
        $this->actingAs($account = $account ?: $this->generateAccount());

        return $account;
    }

    public function actingAsAdmin()
    {
        return $this->actingAs($this->generateAdminAccount());
    }

    /**
     * Creates JWT token and performs a signed request
     *
     * @param Account $account
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return $this
     */
    public function callWithJwt(Account $account, string $method, string $uri, array $data = [], array $headers = [])
    {
        $token = \JWTAuth::fromUser($account);

        $headers = array_merge([
            'Authorization' => 'Bearer '.$token,
        ], $headers);

        return $this->json($method, $uri, $data, $headers);
    }

    /**
     * @param object $owner Class to set a mocked client to
     * @param Response[] ...$responses Responses which will be put to mock que
     * an used one by one sequentially as requests are sent and returned as a result
     */
    public function setMockHttpClient(object $owner, Response ...$responses)
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);

        $httpClient = new Client([
            'handler' => $handler,
            'exceptions' => false
        ]);

        $owner->setHttpClient($httpClient);
    }

    /**
     * @param string $json e.g {"1": 10, "2": 10}'
     *
     * Then it can be used as following
     *   $this->assertDatabaseHas('eligibility_cache', [
     *      'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
     *      'account_id' => $account->getAccountId(),
     *   ]);
     *
     * @return \Illuminate\Database\Query\Expression
     * @throws \Exception
     */
    protected function castToJson(string $json)
    {
        // Convert from array to json and add slashes, if necessary.
        if (is_array($json)) {
            $json = addslashes(json_encode($json));
        }
        // Or check if the value is malformed.
        elseif (is_null($json) || is_null(json_decode($json))) {
            throw new \Exception('A valid JSON string was not provided.');
        }

        return \DB::raw("CAST('{$json}' AS JSON)");
    }

    /**
     * Use this method to speed up tests which do not test PubSub account publisher
     */
    protected function setMockPubSubAccountPublisher()
    {
        $listener = \Mockery::mock(AccountPubSubPublisher::class)
            ->shouldReceive('subscribe')
            ->shouldReceive('onCreateAccount')
            ->shouldReceive('onUpdateAccount')
            ->shouldReceive('onDeleteAccount')
            ->shouldReceive('onDeleteTestAccount')
            ->shouldReceive('onChangeEmail')
            ->shouldReceive('onSubscriptionAddEvent')
            ->shouldReceive('onSubscriptionExpire')
            ->shouldReceive('onSubscriptionCancelled')
            ->shouldReceive('onCancelledActiveUntilExhausted')
            ->shouldReceive('onMailboxRead')
            ->shouldReceive('onNewEmail')
            ->zeroOrMoreTimes()
            ->getMock();

        $this->app->instance(AccountPubSubPublisher::class, $listener);
    }
}
