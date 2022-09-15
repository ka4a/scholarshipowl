<?php
/**
 * Created by PhpStorm.
 * User: r3volut1oner
 * Date: 28/02/19
 * Time: 22:40
 */

namespace Tests\Feature;

use App\Auth\ApiTokenGuard;
use App\Entities\UserToken;
use Pz\Doctrine\Rest\RestResponse;
use Tests\TestCase;

class UserTokenManagementTest extends TestCase
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function test_token_usage()
    {
        $user = $this->generateUser();
        $token = $this->generateUserToken($user);

        $this->json('get', route('user.me'), [], [
            ApiTokenGuard::HEADER => $token->getToken()
        ])
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $user->getId()
                ]
            ]);

        $this->json('delete', route('user_token.delete', $token->getId()), [], [
            ApiTokenGuard::HEADER => $token->getToken()
        ])
            ->assertStatus(RestResponse::HTTP_NO_CONTENT);

        $this->json('get', route('user.me'), [], [
            ApiTokenGuard::HEADER => $token->getToken()
        ])
            ->assertStatus(401);

        $this->json('get', route('user.me'), [], [
            'Authorization' => 'Bearer ' . $token->getToken()
        ])
            ->assertStatus(401);
    }

    public function test_create_new_access_token()
    {
        $this->actingAs($user = $this->generateUser());

        $data = [
            'data' => [
                'attributes' => [
                    'name' => 'test name',
                ]
            ]
        ];

        $this->json('get', route('user.related.tokens.show', $user->getId()))
            ->assertOk()
            ->assertJson(['data' => []]);

        $response = $this->json('post', route('user_token.create'), $data)
            ->assertJson([
                'data' => [
                    'type' => UserToken::getResourceKey(),
                    'attributes' => [
                        'name' => 'test name',
                    ]
                ]
            ]);

        $data = json_decode($response->getContent(), true);

        $this->json('patch', route('user_token.update', $data['data']['id']), [
                'data' => [
                    'attributes' => [
                        'name' => 'updated name',
                    ]
                ]
            ])
            ->assertJson([
                'data' => [
                    'attributes' => [
                        'name' => 'updated name',
                    ]
                ]
            ]);

        $this->json('delete', route('user_token.delete', $data['data']['id']))
            ->assertStatus(RestResponse::HTTP_NO_CONTENT);

        $this->json('get', route('user.related.tokens.show', $user->getId()))
            ->assertOk()
            ->assertJson(['data' => []]);
    }
}