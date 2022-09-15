<?php
# CrEaTeD bY FaI8T IlYa      
# 2016  

namespace Test\Http\Controller\ApplyMe;

use App\Testing\TestCase;
use App\Entity\Domain;

class NotificationControllerTest extends TestCase
{
	protected $V = 'v2';

    public function testNotificationCreated()
    {
        $this->actingAs($account = $this->generateAccount(
            $email = 'test@test.com',
            $firstName = 'testFirstName',
            $lastName = 'testLastName',
            $password = 'testPassword',
            $domain = Domain::APPLYME
        ));

        self::$truncate[] = 'onesignal_account';

        $data = [
            'userId' => 'ooFooph7eeKae3zaegieXu2ie',
            'provider'    => 'iOS'
        ];

        $resp = $this->post(route('apply-me-api::' . $this->V . '.notification'), $data);
        $this->assertTrue($resp->status() === 200);

        $this->assertDatabaseHas('onesignal_account', [
            'account_id'   => $account->getAccountId(),
            'user_id' => $data['userId'],
            'app'     => $data['provider']
        ]);
    }
}