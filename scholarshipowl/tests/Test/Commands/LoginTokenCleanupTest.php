<?php

namespace Test\Commands;

use App\Entity\AccountLoginToken;
use App\Services\Account\AccountLoginTokenService;
use App\Testing\TestCase;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LoginTokenCleanupTest extends TestCase
{
    /**
     * @var AccountLoginTokenService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = app(AccountLoginTokenService::class);

        static::$truncate = ['account_login_token'];
    }

    public function testLoginTokenCleanupCommand()
    {
        $account = $this->generateAccount();

         /** @var AccountLoginToken $token */
        $token = $this->service->getLatestToken($account);
        $this->assertDatabaseHas('account_login_token', ['token' => $token->getToken()]);

        // see token still there
        \Artisan::call('account:login-token-cleanup');
        $this->assertDatabaseHas('account_login_token', ['token' => $token->getToken()]);

        $token->setCreatedAt((new Carbon())->subDays(300));
        \EntityManager::flush();
        \Artisan::call('account:login-token-cleanup');
        $this->assertDatabaseMissing('account_login_token', ['token' => $token->getToken()]);
    }
}
