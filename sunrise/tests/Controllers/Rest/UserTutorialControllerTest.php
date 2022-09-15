<?php namespace Tests\Controllers\Rest;

use App\Entities\UserTutorial;
use Tests\TestCase;

class UserTutorialControllerTest extends TestCase
{
    public function test_user_tutorial_update()
    {
        $user = $this->registerUser();

        $this->actingAs($user)
            ->get(route('user.me', ['include' => 'tutorials']))
            ->assertOk()
            ->assertJson([
                'included' => [
                    [
                        'id' => $user->getId(),
                        'type' => UserTutorial::getResourceKey(),
                        'attributes' => [
                            'newScholarship' => false
                        ]
                    ]
                ]
            ]);

        $this->actingAs($user)
            ->patch(route('user_tutorial.update', $user->getId()), [
                'data' => [
                    'attributes' => [
                        'newScholarship' => true,
                    ]
                ]
            ])
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => '' . $user->getId(),
                    'type' => UserTutorial::getResourceKey(),
                    'attributes' => [
                        'newScholarship' => true,
                    ]
                ]
            ]);

        $this->actingAs($user)
            ->get(route('user.me', ['include' => 'tutorials']))
            ->assertOk()
            ->assertJson([
                'included' => [
                    [
                        'id' => '' . $user->getId(),
                        'type' => UserTutorial::getResourceKey(),
                        'attributes' => [
                            'newScholarship' => true
                        ]
                    ]
                ]
            ]);

        $this->actingAs($user)
            ->get(route('user_tutorial.show', $user->getId()))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => '' . $user->getId(),
                    'type' => UserTutorial::getResourceKey(),
                    'attributes' => [
                        'newScholarship' => true
                    ]
                ]
            ]);
    }
}
