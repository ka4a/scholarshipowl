<?php
namespace Test\Http\Controller\RestMobile;

use App\Services\Account\AccountLoginTokenService;
use App\Testing\TestCase;

class AuthTest extends TestCase
{
    public function testUserLoginsSuccessfully()
    {
        $email = 'test@test.com';
        $password = '123123';

        $this->generateAccount($email, 'f_name', 'l_name', \Hash::make($password));
        $payload = ['email' => $email, 'password' => $password];

        $resp = $this->json('POST', 'rest-mobile/v1/auth', $payload);
        $this->assertTrue($resp->status() === 200);

        $this->seeJsonStructure($resp, [
           'status',
           'data' => [
               'accountId',
               'token'
           ]
        ]);
    }

    public function testAuthenticateByMagicLink()
    {
        self::$truncate[] = 'account_login_token';

        $account = $this->generateAccount();

        /** @var AccountLoginTokenService $service */
        $service = app(AccountLoginTokenService::class);

        $token = $service->getLatestToken($account);

        \Cache::store('databaseCustom')->add("once-used-magic-token-{$token->getToken()}", 1, 5);
        $service->expireLoginToken($token);

        // must be ok when we use token one more time in 5 minutes
        $resp = $this->json('POST', route('rest-mobile::v1.auth.magicLink'), ['token' => $token->getToken()]);
        $this->assertTrue($resp->status() === 200);
        $this->seeJsonStructure($resp, [
           'status',
           'data' => [
               'accountId',
               'token'
           ]
        ]);

        // with used token
        $resp = $this->json('POST', route('rest-mobile::v1.auth.magicLink'), ['token' => $token->getToken()]);
        $this->assertTrue($resp->status() === 401);

        // with invalid token
        $resp = $this->json('POST', route('rest-mobile::v1.auth.magicLink'), ['token' => 'xxx']);
        $this->assertTrue($resp->status() === 401);
    }
}