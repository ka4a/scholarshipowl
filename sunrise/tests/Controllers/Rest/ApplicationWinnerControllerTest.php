<?php namespace tests\Controllers\Rest;

use Tests\TestCase;

class ApplicationWinnerControllerTest extends TestCase
{
    public function test_application_winner_filter_by_scholarship_template()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        $user = $this->generateUser(1, false);
        $organisation = $this->generateOrganisation('Test org', $user);
        $template = $this->generateScholarshipTemplate($organisation);
        $published = $this->sm()->publish($template);
        $application1 = $this->generateApplication($published, 'test@test1.com');
        $application2 = $this->generateApplication($published, 'test@test2.com');
        $winner1 = $this->generateApplicationWinner($application1);
        $winner2 = $this->generateApplicationWinner($application2);
        $winner2->getCreatedAt()->add(new \DateInterval('PT5S'));
        $this->em()->flush($winner2);

        $user2 = $this->generateUser(22, false);
        $organisation2 = $this->generateOrganisation('Test org2', $user2);
        $template2 = $this->generateScholarshipTemplate($organisation2);
        $published2 = $this->sm()->publish($template2);
        $application3 = $this->generateApplication($published2, 'test@test3.com');
        $winner3 = $this->generateApplicationWinner($application3);

        $url = route('application_winner.index', [
            'filter' => [
                'scholarship_template' => $template->getId(),
            ]
        ]);

        $this
            ->actingAs($user)
            ->json('get', $url)
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => ''.$winner1->getId(),
                        'type' => $winner1->getResourceKey()
                    ],
                    [
                        'id' => ''.$winner2->getId(),
                        'type' => $winner2->getResourceKey()
                    ],
                ],
            ]);

        $this->actingAs($user2)->json('get', $url)->assertStatus(403);

        $url = route('application_winner.index', [
            'filter' => [
                'scholarship_template' => $template2->getId(),
            ]
        ]);

        $this
            ->actingAs($user2)
            ->json('get', $url)
            ->assertJson([
                'data' => [
                    [
                        'id' => ''.$winner3->getId(),
                        'type' => $winner3->getResourceKey()
                    ],
                ],
            ]);

        $this->actingAs($user)->json('get', $url)->assertStatus(403);
    }
}
