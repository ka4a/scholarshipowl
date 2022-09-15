<?php namespace Test\Http\Controller\Api;

use App\Testing\TestCase;

class WebsiteControllerTest extends TestCase
{

    public function testFailedListYourShcolarshipInput()
    {
        $resp = $this->json('post', route('post-list-scholarship'));
        $this->followRedirects($resp);
        $this->assertTrue($resp->status() === 500);
    }

    public function testListYourScholarship()
    {
        $resp = $this->post(route('post-list-scholarship'), [
            'name' => 'TestName',
            'email' => 'test@test.com',
            'content' => 'TestContent',
            'phone' => '1111',
        ]);

        $this->followRedirects($resp);
        $this->assertTrue($resp->status() === 200);
    }

}
