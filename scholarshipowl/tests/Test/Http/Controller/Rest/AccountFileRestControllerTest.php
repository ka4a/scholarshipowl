<?php namespace Test\Http\Controller\Rest;

use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;

use Illuminate\Contracts\Filesystem\Filesystem;
use Mockery as m;

class AccountFileRestControllerTest extends TestCase
{
    use EntityGenerator;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        \Storage::shouldReceive('cloud')->andReturn(
            m::mock(Filesystem::class)
                ->shouldReceive('put')->andReturn(true)
                ->getMock()
        );
    }

    public function testIndexActionAdmin()
    {
        $this->actingAs($this->generateAdminAccount());
        $account = $this->generateAccount();
        $this->generateAccountFile($account, 'test1');
        $this->generateAccountFile($account, 'test2');
        $this->generateAccountFile($account, 'test3');

        $account2 = $this->generateAccount('test@test2.com');
        $this->generateAccountFile($account2, 'test4');

        $resp = $this->get(route('rest::v1.account.file.index'));
        $this->assertTrue($resp->status() === 200);
        $this->seeJsonSubset($resp, ['meta' => ['count' => 4]]);
    }

    public function testIndexActionWithLoggedInUser()
    {
        $account = $this->generateAccount();
        $this->generateAccountFile($account, 'test1');
        $this->generateAccountFile($account, 'test2');
        $this->generateAccountFile($account, 'test3');

        $account2 = $this->generateAccount('test@test.com2');
        $this->generateAccountFile($account2, 'test4');

        $resp = $this->actingAs($account)->get(route('rest::v1.account.file.index'));
        $this->assertTrue($resp->status() === 200);
        $this->seeJsonSubset($resp, ['meta' => ['count' => 3]]);
    }

    public function testShowAction()
    {
        $account = $this->generateAccount();
        $accountFile = $this->generateAccountFile($account, 'test_show.txt');
        $this->be($account);

        $resp = $this->get(route('rest::v1.account.file.show', $accountFile->getId()));
        $this->assertTrue($resp->status() === 200);
        $this->assertJson($resp->getContent());
    }

}
