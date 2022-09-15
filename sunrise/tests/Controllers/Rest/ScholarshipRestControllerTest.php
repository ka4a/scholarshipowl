<?php namespace Tests\Controllers\Rest;

use App\Entities\ApplicationWinner;
use App\Entities\Field;
use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplateField;
use App\Entities\ScholarshipWebsite;
use App\Entities\State;
use App\Repositories\ScholarshipRepository;
use Carbon\Carbon;
use Illuminate\Http\Response;
use League\Csv\Reader;
use Tests\TestCase;

class ScholarshipRestControllerTest extends TestCase
{
    protected $headers;

    public function setUp()
    {
        parent::setUp();
        $this->headers = $this->getOAuthClientHeaders('scholarships');
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function test_scholarship_republish_action()
    {
        $user = $this->registerUser();
        $template = $this->generateScholarshipTemplate($user->getOrganisationRoles()->first()->getOrganisation());
        $scholarship = $this->sm()->publish($template);

        $this->actingAs($user)
            ->json('get', route('scholarship.show', $scholarship->getId()))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $scholarship->getId(),
                    'type' => $scholarship->getResourceKey(),
                    'attributes' => [
                        'title' => 'Testing scholarship',
                    ]
                ]
            ]);

        $this->em()->flush($template->setTitle('Test title 2'));

        $this->actingAs($user)
            ->json('post', route('scholarship.republish', $scholarship->getId()))
             ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $scholarship->getId(),
                    'type' => $scholarship->getResourceKey(),
                    'attributes' => [
                        'title' => 'Test title 2',
                    ]
                ]
            ]);

        $this->actingAs($user)
            ->json('get', route('scholarship.show', $scholarship->getId()))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $scholarship->getId(),
                    'type' => $scholarship->getResourceKey(),
                    'attributes' => [
                        'title' => 'Test title 2',
                    ]
                ]
            ]);
    }

    public function test_get_only_my_scholarships()
    {
        $user = $this->registerUser();
        $template = $this->generateScholarshipTemplate($user->getOrganisationRoles()->first()->getOrganisation());
        $scholarship = $this->sm()->publish($template);

        $this->actingAs($user)
            ->json('GET', route('scholarship.index', ['page' => ['limit' => 10, 'start' => 0]]))
            ->assertJson([
                'data' => [
                    [
                        'id' => $scholarship->getId(),
                        'type' => $scholarship->getResourceKey(),
                    ]
                ],
                'meta' => [
                    'pagination' => [
                        'total' => 1,
                    ]
                ]
            ]);
    }

    public function test_application_eligible_same_as_batch()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');
        $this->actingAs($user = $this->generateUser());
        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);

        $data = [
            'data' => [
                'attributes' => [
                    Field::NAME => 'Test Name',
                    Field::EMAIL => 'Test@testst.com',
                    Field::PHONE => '+1 (330) 939 - 39393',
                    Field::STATE => State::STATE_ALABAMA,
                    Field::DATE_OF_BIRTH => Carbon::now()->subYears(18)->format('Y-m-d'),
                    Field::FIELD_OF_STUDY => 1,
                ]
            ]
        ];

        $url = route('scholarship.eligible', [
            'page' => [
                'number' => 1,
                'size' => 1,
            ],
            'filter' => [
                'id' => [
                    'operator' => 'in',
                    'value' => [$scholarship->getId()],
                ]
            ]
        ]);


        $eligible = $this->getJsonApiDocument(
            $this->json('post', $url, $data)
                ->assertJson([])
                ->assertOk()
        );

        $this->assertEquals($scholarship->getId(), $eligible[0]->id);

        $batchUrl = route('application_batch.create', [
            'include' => 'applications,applications.scholarship',
            'filter' => [
                'id' => [
                    'operator' => 'in',
                    'value' => [$scholarship->getId()],
                ]
            ],
        ]);

        $response = $this->json('post', $batchUrl, $data)
            ->assertJson([])
            ->assertStatus(Response::HTTP_CREATED);

        $batch = $this->getJsonApiDocument($response);

        $this->assertEquals($batch->type, 'application_batch');
        $this->assertEquals($batch->applications[0]->scholarship->id, $scholarship->getId());
    }

    public function test_application_batch_eligible_exception()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');
        $data = [
            'data' => [
                'attributes' => [
                    Field::NAME => 'Test Name',
                    Field::EMAIL => 'Test@testst.com',
                    Field::PHONE => '+1 (330) 939 - 39393',
                    Field::STATE => State::STATE_ALABAMA,
                    Field::DATE_OF_BIRTH => Carbon::now()->subYears(16)->format('Y-m-d'),
                    Field::FIELD_OF_STUDY => 1,
                ]
            ]
        ];

        $this->json('post', route('application_batch.create'), $data, $this->getOAuthClientHeaders())
            ->assertStatus(Response::HTTP_CREATED);

        $this->json('post', route('scholarship.eligible'), $data)
            ->assertOk()
            ->assertJson(['data' => []]);
    }

    public function test_scholarship_eligible_action_field_of_study()
    {

        $template = $this->generateScholarshipTemplate();
        $template->addFields(
            (new ScholarshipTemplateField())
                ->setField(Field::find(Field::DATE_OF_BIRTH))
                ->setEligibilityType(ScholarshipTemplateField::ELIGIBILITY_TYPE_GTE)
                ->setEligibilityValue(16)
        );
        $template->addFields(
            (new ScholarshipTemplateField())
                ->setField(Field::find(Field::FIELD_OF_STUDY))
                ->setEligibilityType(ScholarshipTemplateField::ELIGIBILITY_TYPE_IN)
                ->setEligibilityValue(implode(',', [1,2,3]))
        );

        $template2 = $this->generateScholarshipTemplate();
        $template2->addFields(
            (new ScholarshipTemplateField())
                ->setField(Field::find(Field::DATE_OF_BIRTH))
                ->setEligibilityType(ScholarshipTemplateField::ELIGIBILITY_TYPE_GTE)
                ->setEligibilityValue(16)
        );
        $template2->addFields(
            (new ScholarshipTemplateField())
                ->setField(Field::find(Field::FIELD_OF_STUDY))
                ->setEligibilityType(ScholarshipTemplateField::ELIGIBILITY_TYPE_EQUALS)
                ->setEligibilityValue('1')
        );

        $this->em()->flush();
        $scholarship = $this->sm()->publish($template);
        $scholarship2 = $this->sm()->publish($template2);

        $data = [
            'data' => [
                'attributes' => [
                    Field::NAME => 'Test Name',
                    Field::EMAIL => 'Test@testst.com',
                    Field::PHONE => '+1 (330) 939 - 39393',
                    Field::STATE => State::STATE_ALABAMA,
                    Field::DATE_OF_BIRTH => Carbon::now()->subYears(16)->format('Y-m-d'),
                    Field::FIELD_OF_STUDY => 1,
                ]
            ]
        ];

        $url = route('scholarship.eligible', [
            'page' => ['size' => 10, 'number' => 1],
            'filter' => [
                'id' => ['operator' => 'in', 'value' => [$scholarship->getId(), $scholarship2->getId()]]
            ]
        ]);

        $this->json('post', $url, $data)
            ->assertJson([
                'data' => [
                    [
                        'id' => $scholarship->getId(),
                        'type' => $scholarship->getResourceKey(),
                    ],
                    [
                        'id' => $scholarship2->getId(),
                        'type' => $scholarship2->getResourceKey(),
                    ]
                ],
                'meta' => [
                    'pagination' => [
                        'total' => 2
                    ]
                ]
            ])
            ->assertOk();

        $data['data']['attributes'][Field::FIELD_OF_STUDY] = 2;

        $this->json('post', $url, $data)
            ->assertJson(['data' => [
                [
                    'id' => $scholarship->getId(),
                    'type' => $scholarship->getResourceKey(),
                ],
            ]])
            ->assertJsonMissing(['data' => [
                [
                    'id' => $scholarship2->getId(),
                    'type' => $scholarship2->getResourceKey(),
                ]
            ]])
            ->assertOk();

        $data['data']['attributes'][Field::FIELD_OF_STUDY] = 4;

        $this->json('post', $url, $data)
            ->assertJson([
                'data' => [],
                'meta' => [
                    'pagination' => [
                        'total' => 0
                    ]
                ]
            ])
            ->assertOk();
    }

    public function test_scholarship_eligible_action_age_eligibility()
    {

        $template = $this->generateScholarshipTemplate();
        $template->addFields(
            (new ScholarshipTemplateField())
                ->setField(Field::find(Field::DATE_OF_BIRTH))
                ->setEligibilityType(ScholarshipTemplateField::ELIGIBILITY_TYPE_GTE)
                ->setEligibilityValue(16)
        );

        $this->em()->flush($template);

        $scholarship = $this->sm()->publish($template);

        $template2 = $this->generateScholarshipTemplate();
        $scholarship2 = $this->sm()->publish($template2);

        $data = [
            'data' => [
                'attributes' => [
                    Field::NAME => 'Test Name',
                    Field::EMAIL => 'Test@testst.com',
                    Field::PHONE => '+1 (330) 939 - 39393',
                    Field::STATE => State::STATE_ALABAMA,
                    Field::DATE_OF_BIRTH => Carbon::now()->subYears(15)->format('Y-m-d'),
                ]
            ]
        ];

        $url = route('scholarship.eligible', ['filter' => [
            'id' => ['operator' => 'in', 'value' => [$scholarship->getId(), $scholarship2->getId()]]
        ]]);

        $this->json('post', $url, $data)
            ->assertJson(['data' => [
                [
                    'id' => $scholarship2->getId(),
                    'type' => $scholarship2->getResourceKey(),
                ]
            ]])
            ->assertJsonMissing(['data' => [
                [
                    'id' => $scholarship->getId(),
                    'type' => $scholarship->getResourceKey(),
                ]
            ]])
            ->assertOk();

        $data = [
            'data' => [
                'attributes' => [
                    Field::NAME => 'Test Name',
                    Field::EMAIL => 'Test@testst.com',
                    Field::PHONE => '+1 (330) 939 - 39393',
                    Field::STATE => State::STATE_ALABAMA,
                    Field::DATE_OF_BIRTH => Carbon::now()->subYears(16)->format('Y-m-d'),
                ]
            ]
        ];

        $this->json('post', $url, $data)
            ->assertJson(['data' => [
                [
                    'id' => $scholarship->getId(),
                    'type' => $scholarship->getResourceKey(),
                ],
                [
                    'id' => $scholarship2->getId(),
                    'type' => $scholarship2->getResourceKey(),
                ]
            ]])
            ->assertOk();
    }

    public function test_scholarship_fetch_eligible_scholarships()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);

        /** @var ScholarshipRepository $repository */
        $repository = $this->em->getRepository(Scholarship::class);


        $url = route('scholarship.eligible', [
            'page' => [
                'start' => 0,
                'limit' => 1
            ],
            'filter' => [
                'id' => ['operator' => 'in', 'value' => [$scholarship->getId()]]
            ]
        ]);


        $data = [
            'data' => [
                'attributes' => [
                    Field::NAME => 'Test Name',
                    Field::EMAIL => 'Test@testst.com',
                    Field::PHONE => '+1 (330) 939 - 39393',
                    Field::STATE => State::STATE_ALABAMA,
                ]
            ]
        ];

        $this->json('post', $url, $data)
            ->assertJson([
                'data' => [
                    [
                        'id' => $scholarship->getId(),
                        'type' => $scholarship->getResourceKey(),
                    ]
                ],
                'meta' => [
                    'pagination' => [
                        'total' => 1,
                    ]
                ]
            ])
            ->assertOk();

        $this->em()->clear();

        $scholarship = $repository->findSinglePublishedByTemplate($template);
        $this->sm()->unpublish($scholarship);

        $this->json('post', $url, $data)
            ->assertJson([
                'meta' => [
                    'pagination' => [
                        'total' => 0,
                    ]
                ]
            ])
            ->assertOk();

    }

    public function test_scholarship_eligible_action_already_applied()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);

        $data = [
            'data' => [
                'attributes' => [
                    Field::NAME => 'Test Name',
                    Field::EMAIL => 'Test@testst.com',
                    Field::PHONE => '+1 (330) 939 - 39393',
                    Field::STATE => State::STATE_ALABAMA,
                ]
            ]
        ];

        $url = route('scholarship.eligible', ['filter' => [
            'id' => ['operator' => 'in', 'value' => [$scholarship->getId()]]
        ]]);

        $this->json('post', $url, $data)
            ->assertJson(['data' => [
                [
                    'id' => $scholarship->getId(),
                    'type' => $scholarship->getResourceKey(),
                ]
            ]])
            ->assertOk();

        $this->json('post', route('scholarship.apply', $scholarship->getId()), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->json('post', $url, $data)
            ->assertExactJson(['data' => []])
            ->assertOk();
    }

    public function test_scholarship_apply_action()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);

        $this->json('post', route('scholarship.apply', $scholarship->getId()), [
            'data' => [
                'attributes' => [
                    'source' => 'sgo',
                    Field::NAME => 'Test Name',
                    Field::EMAIL => 'Test@testst.com',
                    Field::PHONE => '+1 (330) 939 - 39393',
                    Field::STATE => State::STATE_ALABAMA,
                    'test' => 'suka',
                ]
            ]
        ])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'attributes' => [
                        'source' => 'sgo',
                        'data' => [
                            Field::NAME => 'Test Name',
                            Field::EMAIL => 'Test@testst.com',
                            Field::PHONE => '+1 (330) 939-39393',
                            Field::STATE => State::STATE_ALABAMA,
                        ],
                    ],
                    'meta' => [
                        'scholarship' => ''.$scholarship->getId(),
                    ],
                ],
            ]);
    }

    public function test_scholarship_fields_include()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $user = $this->generateUser();
        $organisation = $this->generateOrganisation('Test', $user);
        $template = $this->generateScholarshipTemplate($organisation);
        $scholarship = $this->sm()->publish($template);

        $url = route('scholarship.show', [
            'id' => $scholarship->getId(),
            'include' => 'fields',
        ]);

        $this->actingAs($user)
            ->json('get', $url)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => ''.$scholarship->getId(),
                    'type' => $scholarship->getResourceKey(),
                    'relationships' => [
                        'fields' => []
                    ]
                ],
                'included' => [
                    [
                        'id' => Field::NAME,
                        'type' => Field::getResourceKey(),
                    ],
                    [
                        'id' => Field::PHONE,
                        'type' => Field::getResourceKey(),
                    ],
                    [
                        'id' => Field::EMAIL,
                        'type' => Field::getResourceKey(),
                    ],
                    [
                        'id' => Field::STATE,
                        'type' => Field::getResourceKey(),
                    ],
                ]
            ]);
    }

    public function test_scholarship_relation_winners()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();
        $template->setAwards(3);
        $this->em()->flush($template);
        $scholarship = $this->sm()->publish($template);
        $this->generateApplication($scholarship, 'test1@test.com');
        $this->generateApplication($scholarship, 'test2@test.com');
        $this->generateApplication($scholarship, 'test3@test.com');
        $this->sm()->expire($scholarship);

        $this->json('get', route('scholarship.related.winner.show', $scholarship->getId()), [], $this->getOAuthClientHeaders())
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'type' => ApplicationWinner::getResourceKey(),
                    ],
                    [
                        'type' => ApplicationWinner::getResourceKey(),
                    ],
                    [
                        'type' => ApplicationWinner::getResourceKey(),
                    ],
                ],
            ]);
    }

    public function test_scholarship_relation_application()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        $this->actingAs($this->generateUser());
        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);
        $application1 = $this->generateApplication($scholarship, 'test1@test.com');
        $application2 = $this->generateApplication($scholarship, 'test2@test.com');
        $application3 = $this->generateApplication($scholarship, 'test3@test.com');
        $this->sm()->unpublish($scholarship);

        $scholarship2 = $this->sm()->publish($template);
        $this->generateApplication($scholarship2, 'test4@test.com');

        $this->json('get', route('scholarship.related.application.show', $scholarship->getId()))
            ->assertJson([
                'data' => [
                    [
                        'id' => $application1->getId(),
                        'type' => $application1->getResourceKey(),
                    ],
                    [
                        'id' => $application2->getId(),
                        'type' => $application2->getResourceKey(),
                    ],
                    [
                        'id' => $application3->getId(),
                        'type' => $application3->getResourceKey(),
                    ],
                ],
            ]);

        $filename = sprintf(
            '%s-%s-%s-%s.csv',
            str_slug($scholarship->getTitle()),
            $scholarship->getId(),
            $scholarship->getStart()->format('Y-m-d'),
            $scholarship->getDeadline()->format('Y-m-d')
        );

        $response = $this->get(route('scholarship.related.application.export', $scholarship->getId()))
            ->assertHeader('Content-Disposition', "attachment; filename=$filename")
            ->assertOk();

        $reader = Reader::createFromString($response->getContent());

        $this->assertEquals(
            [
                $application1->getCreatedAt()->format('Y-m-d H:i:s'),
                $application1->getStatus()->getName(),
                $application1->getSource(),
                $application1->getId(),
                $application1->getName(),
                phone_format_us($application1->getPhone()),
                $application1->getEmail(),
                $application1->getState()->getName(),
            ],
            $reader->fetchOne(1)
        );

        $this->assertEquals(
            [
                $application2->getCreatedAt()->format('Y-m-d H:i:s'),
                $application2->getStatus()->getName(),
                $application2->getSource(),
                $application2->getId(),
                $application2->getName(),
                phone_format_us($application2->getPhone()),
                $application2->getEmail(),
                $application2->getState()->getName(),
            ],
            $reader->fetchOne(2)
        );

        $this->assertEquals(
            [
                $application3->getCreatedAt()->format('Y-m-d H:i:s'),
                $application3->getStatus()->getName(),
                $application3->getSource(),
                $application3->getId(),
                $application3->getName(),
                phone_format_us($application3->getPhone()),
                $application3->getEmail(),
                $application3->getState()->getName(),
            ],
            $reader->fetchOne(3)
        );
    }

    public function test_scholarship_index_metadata()
    {
        $template1 = $this->generateScholarshipTemplate();
        $template2 = $this->generateScholarshipTemplate();
        $scholarship1 = $this->sm()->publish($template1);
        $scholarship2 = $this->sm()->publish($template2);

        $arguments = [
            'sort' => 'createdAt',
            'filter' => [
                'id' => ['operator' => 'in', 'value' => [
                    $scholarship1->getId(),
                    $scholarship2->getId(),
                ]]
            ]
        ];

        $response = $this->json('get', route('scholarship.index', $arguments), [], $this->headers);
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $scholarship1->getId(),
                        'type' => $scholarship1->getResourceKey(),
                        'meta' => [
                            'next' => $scholarship1->getNextDate()->format('c')
                        ]
                    ],
                    [
                        'id' => $scholarship2->getId(),
                        'type' => $scholarship2->getResourceKey(),
                        'meta' => [
                            'next' => $scholarship2->getNextDate()->format('c')
                        ]
                    ]
                ]
            ]);
    }

    public function test_scholarship_find_by_domain_expired_scholarship()
    {
        $this->actingAs($this->generateUser());
        $template = $this->generateScholarshipTemplate();
        $template->getWebsite()->setDomain('test');
        $this->em()->flush();
        $scholarship = $this->sm()->publish($template);

        /**
         * Provide date to expire and unpublish function because in tests expire date will be same second.
         * And in queries we do order by expire date.
         */
        $now = Carbon::now();

        $this->json('get', route('scholarship.showByDomain', 'test.sunrise.local'), [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $scholarship->getId(),
                ],
            ]);

        $this->sm()->expire($scholarship, clone $now->addSecond());

        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = $this->em()->getRepository(Scholarship::class);
        $scholarship2 = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $this->json('get', route('scholarship.showByDomain', 'test.sunrise.local'), [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $scholarship2->getId(),
                ],
            ]);

        $this->sm()->expire($scholarship2, clone $now->addSecond());
        $scholarship3 = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $this->json('get', route('scholarship.showByDomain', 'test.sunrise.local'), [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $scholarship3->getId(),
                ],
            ]);

        $this->sm()->unpublish($scholarship3, clone $now->addSecond());

        $this->json('get', route('scholarship.showByDomain', 'test.sunrise.local'), [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $scholarship3->getId(),
                ],
            ]);
    }

    public function test_scholarship_find_by_hosted_domain()
    {
        $this->actingAs($this->generateUser());
        $template = $this->generateScholarshipTemplate();
        $template->getWebsite()->setDomain('test');
        $this->em()->flush();
        $scholarship = $this->sm()->publish($template);

        $this->json('get', route('scholarship.showByDomain', 'test.sunrise.local'), [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $scholarship->getId(),
                ],
            ]);
    }

    public function test_scholarship_index_find_by_domain()
    {
        $this->actingAs($this->generateUser());
        $template = $this->generateScholarshipTemplate();
        $template->getWebsite()->setDomain('www.test.com')->setDomainHosted(false);
        $this->em()->flush();
        $scholarship = $this->sm()->publish($template);

        $this->json('GET', route('scholarship.showByDomain', 'test2.com'))->assertStatus(404);

        $url = route('scholarship.showByDomain', 'test.com').'?'.http_build_query(['include' => 'website']);
        $this->json('get', $url, [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $scholarship->getId(),
                    'meta' => [
                        'next' => $scholarship->getNextDate()->format('c'),
                    ],
                    'relationships' => [
                        'website' => [
                            'data' => [
                                'id' => $scholarship->getTemplate()->getWebsite()->getId(),
                                'type' => $scholarship->getTemplate()->getWebsite()->getResourceKey(),
                            ],
                        ],
                    ],
                ],
                'included' => [
                    [
                        'id' => $scholarship->getTemplate()->getWebsite()->getId(),
                        'type' => ScholarshipWebsite::getResourceKey(),
                        'attributes' => [
                            'domain' => 'www.test.com',
                            'layout' => 'default',
                            'variant' => 'default',
                            'companyName' => $template->getOrganisation()->getName(),
                            'title' => 'Test title',
                            'intro' => 'Test intro',
                        ]
                    ],
                ]
            ]);

        $this->json('get', route('scholarship.showByDomain', 'not-found.com'))
            ->assertStatus(404)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'entity-not-found',
                        'source' => [
                            'type' => 'scholarship',
                            'domain' => 'not-found.com',
                        ],
                        'detail' => 'Scholarship for specified domain not found!'
                    ]
                ]
            ]);
    }

    public function test_scholarship_for_barn_project()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');
        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);

        $application = $this->generateApplication($scholarship, 'test@test.com');
        $winner = $this->generateApplicationWinner($application);

        $query = ['include' => 'content,website,winners'];
        $url = route('scholarship.show', $scholarship->getId()).'?'.http_build_query($query);
        $this->json('get', $url, [], $this->headers)
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/vnd.api+json')
            ->assertJson([
                'data' => [
                    'id' => $scholarship->getId(),
                    'meta' => [
                        'next' => $scholarship->getNextDate()->format('c'),
                    ],
                    'relationships' => [
                        'website' => [
                            'data' => [
                                'id' => $scholarship->getTemplate()->getWebsite()->getId(),
                                'type' => $scholarship->getTemplate()->getWebsite()->getResourceKey(),
                            ],
                        ],
                        'winners' => [
                            'data' => [
                                [
                                    'id' => $winner->getId(),
                                    'type' => $winner->getResourceKey(),
                                ]
                            ]
                        ]
                    ],
                ],
                'included' => [
                    [
                        'id' => $application->getState()->getId(),
                        'type' => $application->getState()->getResourceKey(),
                    ],
                    [
                        'id' => $application->getWinner()->getPhoto()->getId(),
                        'type' => $application->getWinner()->getPhoto()->getResourceKey(),
                    ],
                    [
                        'id' => $scholarship->getContent()->getId(),
                        'type' => $scholarship->getContent()->getResourceKey(),
                    ],
                    [
                        'id' => $scholarship->getTemplate()->getWebsite()->getId(),
                        'type' => $scholarship->getTemplate()->getWebsite()->getResourceKey(),
                    ],
                    [
                        'id' => $winner->getId(),
                        'type' => $winner->getResourceKey(),
                        'attributes' => [
                            'name' => $application->getName()
                        ]
                    ]
                ]
            ]);
    }

}
