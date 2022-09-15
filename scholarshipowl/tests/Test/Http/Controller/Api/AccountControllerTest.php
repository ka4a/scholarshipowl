<?php namespace Test\Http\Controller\Api;

use App\Testing\TestCase;
use App\Testing\Traits\JsonResponseAsserts;

class AccountControllerTest extends TestCase
{
    use JsonResponseAsserts;

    public function testAccountAction()
    {
        $account = $this->generateAccount();

        /** @var \Illuminate\Foundation\Testing\TestResponse  $result */
        $resp = $this->actingAs($account)->get(route('api::account.index'));
        $this->assertTrue($resp->status() === 200);
    }
}
