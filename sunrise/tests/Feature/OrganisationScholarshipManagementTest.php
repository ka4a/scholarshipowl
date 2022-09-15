<?php namespace Aests\Feature;

use App\Contracts\Recurrable;
use App\Doctrine\Types\RecurrenceConfigType\WeeklyConfig;
use App\Entities\Organisation;
use App\Entities\Passport\OauthClient;
use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplate;
use App\Entities\User;
use App\Services\ScholarshipManager;
use Illuminate\Http\Response;
use Tests\TestCase;

class OrganisationScholarshipManagementTest extends TestCase
{
    /**
     * @var Organisation
     */
    protected $organisation;

    /**
     * @var ScholarshipManager
     */
    protected $manager;

    /**
     * @var User
     */
    protected $user;

    /**
     * Create organisation test data to work with.
     */
    public function setUp()
    {
        parent::setUp();
        $this->user = $this->generateUser();
        $this->manager = app(ScholarshipManager::class);
        $this->organisation = $this->generateOrganisation('Test organisation', $this->user);
        $this->generateOrganisation('Test organisation 2');
        $this->generateOrganisation('Test organisation 3');
    }

    public function test_create_organisation_scholarship()
    {
        $start = (new \DateTime('Monday'))->modify('+1 week');
        $deadline = (new \DateTime('Monday'))->setTime(23, 59, 59)->modify('+2 week');

        $data = [
            'data' => [
                'attributes' => [
                    'title' => 'Test title',
                    'description' => 'Test title',
                    'amount' => 500,
                    'awards' => 2,
                    'timezone' => Scholarship::DEFAULT_TIMEZONE,
                ],
                'relationships' => [
                    'organisation' => [
                        'data' => [
                            'id' => $this->organisation->getId(),
                            'type' => $this->organisation->getResourceKey(),
                        ]
                    ]
                ]
            ]
        ];

        $this->actingAs($this->user)
            ->json('post', route('scholarship_template.create', ['include' => 'organisation']), $data)
            ->assertJson($data);
    }

    public function test_get_simple_scholarship()
    {
        $headers = $this->getOAuthClientHeaders('scholarships');
        $settings = $this->generateScholarshipTemplate($this->organisation);
        $scholarship = $this->manager->publish($settings);

        $this->json('GET', route('scholarship.show', $scholarship->getId()), [], $headers)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $scholarship->getId(),
                    'type' => $scholarship->getResourceKey(),
                    'attributes' => [
                        'title' => 'Testing scholarship',
                        'description' => '',
                        'amount' => '100',

                        'start' => $scholarship->getStart()->format('c'),
                        'deadline' => $scholarship->getDeadline()->format('c'),
                        'timezone' => ScholarshipTemplate::DEFAULT_TIMEZONE,

                        'recurringValue' => 1,
                        'recurringType' => Recurrable::PERIOD_TYPE_WEEK,
                    ],
                    'links' => [
                        'self' => '/scholarship/'.$scholarship->getId()
                    ]
                ]
            ]);

    }

    public function test_get_organisation_scholarships()
    {
        $headers = $this->getOAuthClientHeaders('scholarships');

        $scholarship1 = $this->generateScholarshipTemplate($this->organisation);
        $scholarship2 = $this->generateScholarshipTemplate($this->organisation);
        $scholarship3 = $this->generateScholarshipTemplate($this->organisation);

        $this->json('GET', route('organisation.related.scholarships.show', $this->organisation->getId()), [], $headers)
            ->assertJson([
                'data' => [
                    [
                        'id' => $scholarship1->getId(),
                        'type' => $scholarship1->getResourceKey(),
                        'attributes' => [
                            'title' => 'Testing scholarship',
                            'description' => '',
                            'amount' => 100,
                        ],
                        'links' => [
                            'self' => '/scholarship_template/'.$scholarship1->getId()
                        ]
                    ],
                    [
                        'id' => ''.$scholarship2->getId(),
                        'type' => $scholarship1->getResourceKey(),
                    ],
                    [
                        'id' => ''.$scholarship3->getId(),
                        'type' => $scholarship1->getResourceKey(),
                    ],
                ]
            ])
            ->assertStatus(Response::HTTP_OK);

        $this->json('GET', route('organisation.related.scholarships.show', $this->organisation->getId()).
            '?fields[scholarship]=id', [], $headers)
            ->assertJson([
                'data' => [
                    [
                        'id' => ''.$scholarship1->getId(),
                        'type' => $scholarship1->getResourceKey(),
                        'attributes' => [],
                        'links' => [
                            'self' => '/scholarship_template/'.$scholarship1->getId()
                        ]
                    ],
                    [
                        'id' => ''.$scholarship2->getId(),
                        'type' => $scholarship2->getResourceKey(),
                        'attributes' => [],
                        'links' => [
                            'self' => '/scholarship_template/'.$scholarship2->getId()
                        ]
                    ],
                    [
                        'id' => ''.$scholarship3->getId(),
                        'type' => $scholarship3->getResourceKey(),
                        'attributes' => [],
                        'links' => [
                            'self' => '/scholarship_template/'.$scholarship3->getId()
                        ]
                    ],
                ]
            ], true)
            ->assertStatus(Response::HTTP_OK);

    }

    public function test_get_organisation_me()
    {

        $this->actingAs($this->organisation, 'organisation');
        $this->json('GET', route('organisation.me'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $this->organisation->getId(),
                    'type' => $this->organisation->getResourceKey(),
                    'attributes' => [
                        'name' => 'Test organisation',
                    ],
                ]
            ]);

        $scholarship1 = $this->generateScholarshipTemplate($this->organisation);
        $scholarship2 = $this->generateScholarshipTemplate($this->organisation);

        $qs = http_build_query(['include' => 'scholarships']);
        $this->json('GET', route('organisation.me').'?'.$qs)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $this->organisation->getId(),
                    'type' => $this->organisation->getResourceKey(),
                    'relationships' => [
                        'scholarships' => [
                            'data' => [
                                [
                                    'id' => $scholarship1->getId(),
                                    'type' => $scholarship1->getResourceKey(),
                                ],
                                [
                                    'id' => $scholarship2->getId(),
                                    'type' => $scholarship1->getResourceKey(),
                                ],
                            ],
                        ]
                    ]
                ],
                'included' => [
                    [
                        'id' => $scholarship1->getId(),
                        'type' => $scholarship1->getResourceKey(),
                    ],
                    [
                        'id' => $scholarship2->getId(),
                        'type' => $scholarship1->getResourceKey(),
                    ]
                ]
            ]);

    }
}
