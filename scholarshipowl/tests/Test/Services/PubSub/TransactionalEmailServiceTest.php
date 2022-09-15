<?php
namespace Test\Services\PubSub;

use App\Services\PubSub\AccountService;
use App\Services\PubSub\TransactionalEmailService;;
include_once 'PubSubServiceAbstractTest.php';

class TransactionalEmailServiceTest extends PubSubServiceAbstractTest
{

    /**
     * @var TransactionalEmailService
     */
    protected $service;
    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    public function getService(){
        return app(TransactionalEmailService::class);
    }

    /**
     * @throws \Exception
     */
    public function testSendCommonEmail()
    {
        $account = $this->generateAccount();
        $this->service->sendCommonEmail($account, TransactionalEmailService::CHANGE_PASSWORD,[
            "password" => 'test',
        ]);
    }

    /**
     * @throws \Exception
     */
    public function testSendBulkCommonEmail()
    {
        $account = $this->generateAccount();
        $account2 = $this->generateAccount('test2@example.com');
        $this->service->sendBulkCommonEmail([$account, $account2], TransactionalEmailService::SUBSCRIPTION_CREDIT_INCREASES);
    }

    public function testDefaultTopic()
    {
        $topic = self::getMethod('getTopic');
        $res = $topic->invoke($this->service);
        $this->assertEquals($res->name(), 'projects/sowl-tech/topics/sowl.transEmail');
    }
    /**
     * @throws \Exception
     */
    public function testSetCustomTopic()
    {
        $service = $this->service->setTopic('test');
        $topic = self::getMethod('getTopic');
        $res = $topic->invoke($service);
        $this->assertEquals($res->name(), 'projects/sowl-tech/topics/test');
    }

    protected static function getMethod($name) {
        $class = new \ReflectionClass(TransactionalEmailService::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

}
