<?php
# CrEaTeD bY FaI8T IlYa
# 2016

namespace Test\Http\Controller\ApplyMe;

use App\Entity\Domain;
use App\Testing\TestCase;

class PaymentControllerTest extends TestCase
{
	protected $V = 'v2';

    protected $data = [
        'sum'           => 450.20,
        'response'      => 'good response',
        'status'        => 'verified'
    ];

    /**
     * Register first step
     */
    public function testPost()
    {
        static::$truncate[] = 'applyme_payments';

        $this->actingAs($account = $this->generateAccount(
            $email = 'test@test.com',
            $firstName = 'testFirstName',
            $lastName = 'testLastName',
            $password = 'testPassword',
            $domain  = Domain::APPLYME
        ));

        $resp = $this->post(route('apply-me-api::' . $this->V . '.create.payment'), $this->data);
        $this->assertTrue($resp->status() === 200);
    }
}
