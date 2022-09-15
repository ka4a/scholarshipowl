<?php

namespace Test\Services\Account;

use App\Entity\AccountLoginToken;
use App\Services\Account\AccountLoginTokenService;
use App\Services\Account\AccountService;
use App\Services\CmsService;
use App\Testing\TestCase;
use Illuminate\Http\Request;
use Mockery as m;

class AccountLoginTokenServiceTest extends TestCase
{
    /**
     * @var AccountLoginTokenService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = app(AccountLoginTokenService::class);

        static::$truncate = ['account_login_token', 'eligibility_cache'];
    }

    public function testFindByAccounts()
    {
        $account = $this->generateAccount();

        $token = $this->service->generateTokens([$account])[$account->getAccountId()];
         /** @var AccountLoginToken $foundToken */
        $foundToken = $this->service->findByAccounts([$account])[0];

        $this->assertTrue($token === $foundToken->getToken());
    }

    public function testGetLatestToken()
    {
        $account = $this->generateAccount();

         /** @var AccountLoginToken $token */
        $token = $this->service->getLatestToken($account);
        $this->assertDatabaseHas('account_login_token', ['token' => $token->getToken()]);
    }

    public function testVerifyLoginToken()
    {
        $account = $this->generateAccount();
        $token = $this->service->generateTokens([$account])[$account->getAccountId()];
        /** @var AccountLoginToken $foundToken */
        $foundToken = $this->service->verifyLoginToken($token);
        $this->assertTrue($foundToken->getToken() === $token);
        $this->assertTrue($foundToken->getAccount()->getAccountId() === $account->getAccountId());
    }

    public function testExpireLoginToken()
    {
        $account = $this->generateAccount();
        /** @var AccountLoginToken $token */
        $token = $this->service->generateTokens([$account], false)[$account->getAccountId()];

        $account2 = $this->generateAccount('test2@test.com');
        /** @var AccountLoginToken $token2 */
        $token2 = $this->service->generateTokens([$account2], false)[$account2->getAccountId()];

        $this->service->expireLoginToken($token);
        $this->assertDatabaseHas('account_login_token', [
            'token' => $token->getToken(),
            'is_used' => 1
        ]);

        $this->assertDatabaseHas('account_login_token', [
            'token' => $token2->getToken(),
            'is_used' => 0
        ]);
    }

    public function testGenerateTokens()
    {
        $account = $this->generateAccount();

        for ($i = 0; $i < AccountLoginTokenService::MAX_TOKEN_COUNT; $i++) {
            $this->service->generateTokens([$account])[$account->getAccountId()];
        }

        $foundTokens = $this->service->findByAccounts([$account]);
        $this->assertTrue(count($foundTokens) === AccountLoginTokenService::MAX_TOKEN_COUNT);

        // add one more token and make sure we not exceeded the limit
        $this->service->generateTokens([$account])[$account->getAccountId()];
        $foundTokens = $this->service->findByAccounts([$account]);
        $this->assertTrue(count($foundTokens) === AccountLoginTokenService::MAX_TOKEN_COUNT);
    }

    public function testDeleteTokens()
    {
        $account = $this->generateAccount();

        /** @var AccountLoginToken $token */
        $token = $this->service->getLatestToken($account);
        $this->assertDatabaseHas('account_login_token', [
            'token' => $token->getToken()
        ]);

        $this->service->deleteTokens([$token], true);
        $this->assertDatabaseMissing('account_login_token', [
            'token' => $token->getToken()
        ]);
    }

    public function testPluckTokenIds()
    {
        //we should disable event because generateAccount triggers UpdateAccountEvent
        $this->withoutEvents();
        $account = $this->generateAccount();

        $this->service->generateTokens([$account]);
        $this->service->generateTokens([$account]);
        $this->service->generateTokens([$account]);

        $tokens = $this->service->findByAccounts([$account]);
        $ids = $this->service->pluckTokenIds($tokens);
        $this->assertTrue(count($ids) === 3);
    }

    public function testDeleteOutdated()
    {
        $this->withoutEvents();
        $account = $this->generateAccount();

        $this->service->generateTokens([$account]);
        $this->service->generateTokens([$account]);
        $token = $this->service->generateTokens([$account])[$account->getAccountId()];

        $this->assertDatabaseHas('account_login_token', [
            'token' => $token
        ]);

        $this->service->setExpireInDays(-1);
        $cnt = $this->service->deleteOutdated();
        $this->assertTrue($cnt === 3);

        $this->assertDatabaseMissing('account_login_token', [
            'token' => $token
        ]);
    }
}
