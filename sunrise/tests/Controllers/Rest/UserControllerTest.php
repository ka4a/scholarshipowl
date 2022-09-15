<?php namespace Tests\Controllers\Rest;

use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_user_get_related_scholarships()
    {
        $user = $this->generateUser(1, false);
        $organisation = $this->generateOrganisation('Test org', $user);
        $this->actingAs($user);

        $this->json('get', route('user.show', $user->getId()))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $user->getId(),
                    'type' => $user->getResourceKey(),
                ]
            ]);

        $template = $this->generateScholarshipTemplate($organisation);
        $scholarship = $this->sm()->publish($template);

        $this->json('get', route('user.related.scholarships.show', $user->getId()))
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $scholarship->getId(),
                        'type' => $scholarship->getResourcekey(),
                    ]
                ]
            ]);
    }
}