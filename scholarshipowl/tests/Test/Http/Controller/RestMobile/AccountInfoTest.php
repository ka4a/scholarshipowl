<?php
namespace Test\Http\Controller\RestMobile;

use App\Testing\TestCase;
use Illuminate\Http\JsonResponse;

class AccountInfoTest extends TestCase
{
    public function testGetData()
    {
        $account = $this->generateAccount();

        $resp = $this->callWithJwt($account, 'GET', route('rest-mobile::v1.account-info', ['fields' => 'scholarship']));
        $this->seeJsonSuccess($resp, [
            'scholarship' => [
                'eligibleCount' => 0,
                'eligibleAmount' => 0
            ]
        ]);

        $resp = $this->callWithJwt($account, 'GET', route('rest-mobile::v1.account-info', ['fields' => 'application']));
        $this->seeJsonSuccess($resp, [
            'application' => [
                'total' => 0
            ]
        ]);

        $resp = $this->callWithJwt($account, 'GET', route('rest-mobile::v1.account-info', ['fields' => 'mailbox']));
        $this->seeJsonStructure($resp, [
            'status',
            'data' => [
                'mailbox' => [
                    'inbox',
                    'sent',
                ]
            ]
        ]);

        $resp = $this->callWithJwt($account, 'GET', route('rest-mobile::v1.account-info', ['fields' => 'account']));
        $this->seeJsonStructure($resp, [
            'status',
            'data' => [
                'account' => [
                    'accountId',
                    'username',
                    'email',
                    'avatar'
                ]
            ]
        ]);

        $resp = $this->callWithJwt($account, 'GET', route('rest-mobile::v1.account-info', ['fields' => 'profile']));
        $this->seeJsonStructure($resp, [
            'status',
            'data' => [
                'profile' => []
            ]
        ]);

        $resp = $this->callWithJwt($account, 'GET', route('rest-mobile::v1.account-info', ['fields' => 'socialAccount,marketing,membership']));
        $this->seeJsonStructure($resp, [
            'status',
            'data' => [
                'socialAccount',
                'marketing',
                'membership'
            ]
        ]);

        $resp = $this->callWithJwt($account, 'GET', route('rest-mobile::v1.account-info', []));
        $this->seeJsonStructure($resp, [
            'status',
            'data' => [
                'scholarship',
                'application',
                'mailbox',
                'account',
                'profile',
                'socialAccount',
                'marketing',
                'membership'
            ]
        ]);
    }
}