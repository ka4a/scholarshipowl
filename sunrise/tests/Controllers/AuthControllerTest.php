<?php namespace tests\Controllers\Admin;

use App\Entities\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_admin_password_login()
    {
        $password = str_random();
        $user = $this->generateUser();
        $user->setPassword(Hash::make($password));
        $this->em()->flush($user);

        $response = $this->json('post', route('auth.login'), [
            'email' => $user->getEmail(),
            'password' => $password,
        ])
            ->assertOk()
            ->assertJsonStructure([
                'access_token',
                'expires_in',
            ]);

        $jsonResponse = json_decode($response->getContent(), true);
        $this->json('get', route('user.me'), [], [
            'Authorization' => $jsonResponse['token_type'].' '.$jsonResponse['access_token']
        ])
            ->assertOk()
            ->assertJson([
                'data' => [
                    'attributes' => [
                        'email' => $user->getEmail(),
                    ]
                ]
            ]);
    }

    public function test_admin_registration()
    {
        $email = sprintf('%s@gmail.com', str_random());
        $password = str_random();
        $response = $this->post(route('auth.registration'), [
            'password' => $password,
            'email' => $email,
        ])
            ->assertOk()
            ->assertJsonStructure([
                'access_token',
                'expires_in',
            ]);

        $jsonResponse = json_decode($response->getContent(), true);
        $this->json('get', route('user.me'), [], [
            'Authorization' => $jsonResponse['token_type'].' '.$jsonResponse['access_token']
        ])
            ->assertOk()
            ->assertJson([
                'data' => [
                    'attributes' => [
                        'email' => $email,
                    ]
                ]
            ]);
    }
}
