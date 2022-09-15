<?php namespace Tests\Controllers\Rest;

use App\Entities\Application;
use App\Entities\Country;
use App\Entities\Organisation;
use App\Entities\OrganisationRole;
use App\Entities\State;
use Tests\TestCase;

class OrganisationControllerTest extends TestCase
{

    public function test_organisation_change_information()
    {
        $this->actingAs($user = $this->registerUser());
        $user2 = $this->generateUser(1, false);

        /** @var Organisation $organisation */
        $organisation = $user->getOrganisationRoles()
            ->filter(function(OrganisationRole $role) {
                return $role->isOwner();
            })
            ->first()
            ->getOrganisation();

        $this->actingAs($user2)
            ->json('get', route('organisation.show', $organisation->getId()))
            ->assertStatus(403);

        $this->actingAs($user);

        $this->json('get', route('organisation.show', $organisation->getId()))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $organisation->getId(),
                    'type' => $organisation->getResourceKey(),
                    'attributes' => [
                        'name' => $organisation->getName(),
                        'website' => null,
                        'email' => null,
                        'phone' => null,
                    ]
                ]
            ]);

        $data = [
            'data' => [
                'attributes' => [
                    'name' => 'Test name1',
                    'businessName' => 'business name1',
                    'city' => 'test city',
                    'address' => 'Address line1',
                    'address2' => 'Address line2',
                    'zip' => '123456',
                    'website' => 'www.test.com',
                    'email' => 'test@test.com',
                    'phone' => '+1 (303) 393489'
                ],
                'relationships' => [
                    'state' => [
                        'data' => [
                            'id' => State::STATE_CONNECTICUT,
                            'type' => State::getResourceKey(),
                        ]
                    ],
                    'country' => [
                        'data' => [
                            'id' => Country::USA,
                            'type' => Country::getResourceKey(),
                        ]
                    ]
                ]
            ]
        ];

        $url = route('organisation.update', [
            'id' => $organisation->getId(),
            'include' => 'state,country',
        ]);
        $this->json('patch', $url, $data)
            ->assertOk()
            ->assertJson($data);
    }

    public function test_organisation_related_winners_show()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        $organisation = $this->generateOrganisation();
        $this->actingAs($user = $this->generateUser(1, false));
        $user->addOrganisationRoles($organisation->getOwnerRole());
        $template = $this->generateScholarshipTemplate($organisation);
        $scholarship = $this->sm()->publish($template);

        $application1 = $this->generateApplication($scholarship, 'test@test1.com');
        $application2 = $this->generateApplication($scholarship, 'test@test2.com');

        $applicationWinner1 = $this->generateApplicationWinner($application1);
        $applicationWinner2 = $this->generateApplicationWinner($application2);

        $this->em()->flush($applicationWinner1->setDisqualifiedAt(new \DateTime));

        $params = ['id' => $organisation->getId(), 'filter' => ['disqualifiedAt' => ['operator' => 'eq', 'value' => '']]];
        $response = $this->json('GET', route('organisation.related.winners.show', $params))
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $applicationWinner2->getId(),
                        'type' => $applicationWinner2->getResourceKey(),
                    ]
                ]
            ])
            ->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertCount(1, $data);
    }

    public function test_forbidden_winners_api()
    {
        $this->actingAs($user = $this->generateUser(1, false));
        $organisation = $this->generateOrganisation();

        $this->json('GET', route('organisation.related.winners.show', $organisation->getId()))->assertStatus(403);

        $user->addOrganisationRoles($organisation->getOwnerRole());
        $this->em()->flush($user);
        $this->json('GET', route('organisation.related.winners.show', $organisation->getId()))->assertStatus(200);
    }
}
