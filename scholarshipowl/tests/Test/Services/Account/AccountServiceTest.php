<?php namespace Test\Services\Account;

use App\Services\Account\AccountService;
use App\Services\CmsService;
use App\Testing\TestCase;
use Illuminate\Http\Request;
use Mockery as m;

class AccountServiceTest extends TestCase
{

    /**
     * @var AccountService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = app(AccountService::class);
    }

    public function testDeleteAccount()
    {
        $account = $this->generateAccount();

        $this->service->deleteAccount($account->getAccountId());

        $this->assertDatabaseHas('account', [
            'account_id' => $account->getAccountId()
        ]);
        $this->assertDatabaseMissing('account', [
            'account_id' => $account->getAccountId(),
            'deleted_at' => null
        ]);

        $result = $this->service->hardDeleteAccounts(0);
        $this->assertTrue($result['cnt'] === 1);
        $this->assertDatabaseMissing('account', [
            'account_id' => $account->getAccountId()
        ]);
    }
}
