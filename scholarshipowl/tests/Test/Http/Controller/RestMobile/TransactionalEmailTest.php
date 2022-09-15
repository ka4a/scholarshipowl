<?php
namespace Test\Http\Controller\RestMobile;

use App\Testing\TestCase;

class TransactionalEmailTest extends TestCase
{
    public function testAppInvite()
    {
        $account = $this->generateAccount();

        $resp = $this->callWithJwt($account, 'GET', route('rest-mobile::v1.transactional-emails.app-invite'));
        $this->seeJsonContains($resp, ['status' => 200, 'data' => []]);
    }

    public function testAppMagicLink()
    {
        $account = $this->generateAccount();
        $resp = $this->get(route('rest-mobile::v1.transactional-emails.app-magic-link', [$account->getEmail()]));
        $this->seeJsonContains($resp, ['status' => 200, 'data' => []]);
    }

    public function testPasswordReset()
    {
        static::$truncate[] = 'forgot_password';

        $account = $this->generateAccount();

        $resp = $this->get(route('rest-mobile::v1.transactional-emails.password-reset', [
            'email' => 'nonexistent@t.com',
        ]));
        $this->seeJsonSubset($resp, ['status' => 400]);
        $this->seeJsonSubset($resp, ['error' => 'Account with specified email is not found']);
        $this->assertDatabaseMissing('forgot_password', [
            'account_id' => $account->getAccountId()
        ]);

        $resp = $this->get(route('rest-mobile::v1.transactional-emails.password-reset', [
            'email' => $account->getEmail(),
        ]));
        $this->seeJsonSubset($resp, ['status' => 200]);
        $this->assertDatabaseHas('forgot_password', [
            'account_id' => $account->getAccountId()
        ]);
    }
}