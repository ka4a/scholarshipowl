<?php
# CrEaTeD bY FaI8T IlYa
# 2017

namespace Test\Http\Controller\ApplyMe;

use App\Entity\Domain;
use App\Services\Account\AccountService;
use App\Testing\TestCase;
use Illuminate\Http\Request;

class AuthControllerTest extends TestCase
{
	protected $V = 'v2';

	public function testAuth()
	{
		static::$truncate[] = 'account';
		static::$truncate[] = 'profile';

		$data = [
			'email'     => 'test@test.com',
			'firstname' => 'firstname',
			'lastname'  => 'lastname',
			'password'  => '123456'
		];
        $fset = $this->generateFeatureSet();
		$resp = $this->post(route('apply-me-api::' . $this->V . '.account.store'), $data);
		$this->assertTrue($resp->status() === 200);

		$data = [
			'email'     => 'test@test.com',
			'password'  => '123456'
		];

		$resp = $this->post(route('apply-me-api::v2.auth'), $data);
        $this->assertTrue($resp->status() === 200);
	}
}