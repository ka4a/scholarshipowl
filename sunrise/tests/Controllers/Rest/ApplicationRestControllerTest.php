<?php namespace Tests\Controllers\Rest;

use App\Entities\ApplicationStatus;
use App\Events\ApplicationAwardedEvent;
use App\Entities\Application;
use App\Entities\Organisation;
use App\Entities\ScholarshipTemplate;
use App\Entities\State;
use App\Entities\ApplicationWinner;

use Illuminate\Container\Container;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\Testing\File;

use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ApplicationRestControllerTest extends TestCase
{

    /**
     * @var Organisation
     */
    protected $organisation;

    /**
     * @var ScholarshipTemplate
     */
    protected $template;

    const BASIC_DATA = [
        'data' => [
            'type' => 'application',
            'attributes' => [
                'name' => 'Test Full',
                'email' => 'test@teststs.com',
                'phone' => '+1 (300) 484-7898',
                'source' => 'test_application',
            ],
            'relationships' => [
                'scholarship' => ['data' => ['type' => 'scholarship', 'id' => null]],
                'state' => ['data' => ['type' => 'state', 'id' => State::STATE_ALABAMA]]
            ]
        ]
    ];

    public function setUp()
    {
        parent::setUp();
        $this->organisation = $this->generateOrganisation();
        $this->template = $this->generateScholarshipTemplate($this->organisation);
    }

    public function test_apply_to_expired_scholarship()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);
        $this->sm()->unpublish($scholarship);

        $testData = [
            'data' => [
                'type' => 'application',
                'attributes' => [
                    'name' => 'Test Full',
                    'email' => 'test@teststs.com',
                    'phone' => '+9 (300) 484-7898'
                ],
                'relationships' => [
                    'state' => ['data' => ['type' => 'state', 'id' => State::STATE_ALABAMA]],
                    'scholarship' => ['data' => ['type' => 'scholarship', 'id' => $scholarship->getId()]],
                ]
            ]
        ];

        $this->json('post', route('application.create'), $testData)
            ->assertJsonStructure(['errors' => []])
            ->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);

    }

    public function test_old_application_create_controller_test()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $testData = [
            'data' => [
                'type' => 'application',
                'attributes' => [
                    'name' => 'Test Full',
                    'email' => 'test@teststs.com',
                    'phone' => '+9 (300) 484-7898'
                ],
                'relationships' => [
                    'state' => ['data' => ['type' => 'state', 'id' => State::STATE_ALABAMA]],
                    'scholarship' => ['data' => ['type' => 'scholarship', 'id' => null]],
                ]
            ]
        ];

        $settings1 = $this->template;
        $settings2 = $this->generateScholarshipTemplate($this->organisation);

        $scholarship1 = $this->sm()->publish($settings1);
        $scholarship2 = $this->sm()->publish($settings2);

        $basicData = $testData;
        $basicData['data']['relationships']['scholarship']['data']['id'] = $scholarship1->getId();
        $this->json('post', route('application.create'), $basicData)
            ->assertJson(['data' => []])
            ->assertStatus(Response::HTTP_CREATED);

        $basicData = $testData;
        $basicData['data']['relationships']['scholarship']['data']['id'] = $scholarship2->getId();
        $this->json('post', route('application.create'), $basicData)
            ->assertJson(['data' => []])
            ->assertStatus(Response::HTTP_CREATED);

        $basicData = $testData;
        $basicData['data']['relationships']['scholarship']['data']['id'] = $scholarship1->getId();
        $this->json('post', route('application.create'), $basicData)
            ->assertJson(['errors' => [
                [
                    'detail' => ['Student with such email already applied for the scholarship!'],
                ]
            ]])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $basicData = $testData;
        $basicData['data']['relationships']['scholarship']['data']['id'] = $scholarship2->getId();
        $this->json('post', route('application.create'), $basicData)
            ->assertJson(['errors' => [
                [
                    'detail' => ['Student with such email already applied for the scholarship!'],
                ]
            ]])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    public function test_simple_application_create()
    {
        $scholarship = $this->manager->publish($this->template);

        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $basicData = static::BASIC_DATA;
        $basicData['data']['relationships']['scholarship']['data']['id'] = ''.$scholarship->getId();
        $expected = $basicData;
        $expected['data']['attributes']['phone'] = '3004847898';
        unset($expected['data']['relationships']['scholarship']);

        $response = $this->json('post', route('application.create'), $basicData)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson($expected);

        $application = json_decode($response->getContent(), true)['data'];
        $this->assertNotNull($this->em()->find(Application::class, $application['id']));

        $expected = static::BASIC_DATA;
        $expected['data']['attributes']['email'] = 'test@sfadsfasdf.com';
        unset($expected['data']['relationships']['scholarship']);
        $expected['data']['attributes']['phone'] = '3004847898';
        $expected['data']['relationships']['status'] = [
            'data' => [
                'id' => ApplicationStatus::ACCEPTED,
                'type' => ApplicationStatus::getResourceKey()
            ]
        ];
        $basicData['data']['attributes']['email'] = 'test@sfadsfasdf.com';
        $response = $this->json('post', route('application.create'), $basicData)
            ->assertJson($expected)
            ->assertStatus(Response::HTTP_CREATED);

        $application = json_decode($response->getContent(), true)['data'];
        $this->assertNotNull($this->em()->find(Application::class, $application['id']));

        $expected['data']['attributes']['phone'] = '+1 (300) 484-7898';
        $source = $expected['data']['attributes']['source'];
        unset($expected['data']['attributes']['source']);

        $expected['data']['attributes'] = [
            'data' => $expected['data']['attributes'],
            'source' => $source
        ];
        $expected['data']['relationships'] = [
            'status' => $expected['data']['relationships']['status'],
        ];

        $this->actingAs($this->generateUser())
            ->json('get', route('application.show', $application['id']).'?include=scholarship,status')
            ->assertStatus(200)
            ->assertJson($expected);
    }

    public function test_api_application_post_winner_form()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        $awarded = null;
        $winner = null;
        $scholarship = $this->sm()->publish($this->template);
        $application = $this->generateApplication($scholarship, 'test@test1.com');


        $this->json('GET', route('application.related.winner.show', $application->getId()))
            ->assertStatus(200)
            ->assertJson(['data' => null]);

        Event::listen(ApplicationAwardedEvent::class, function(ApplicationAwardedEvent $event) use (&$awarded, &$winner) {
            $awarded = $this->em->getRepository(Application::class)->find($event->getApplicationId());
            $winner = $this->em->getRepository(ApplicationWinner::class)->find($event->getScholarshipWinnerId());
        });

        $this->sm()->maintain(new \DateTime('+4 days'), [$scholarship->getId()]);

        /** @var Application $awarded */
        /** @var ApplicationWinner $winner */
        $this->assertInstanceOf(Application::class, $awarded);
        $this->assertInstanceOf(ApplicationWinner::class, $winner);
        $this->assertEquals($application->getId(), $awarded->getId());
        $this->assertEquals($awarded->getName(), $winner->getName());

        $this->json('GET', route('application.related.winner.show', $awarded->getId()))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $winner->getId(),
                    'type' => $winner->getResourceKey(),
                ]
            ]);

        $dateOfBirth = new \DateTime('- 17 year');
        $data = [
            'data' => [
                'attributes' => [
                    'name' => 'New winner name',
                    'testimonial' => 'Thank you!',
                    'dateOfBirth' => $dateOfBirth->format('Y-m-d'),
                    'phone' => '+123456789',
                    'email' => 'new@test.com',
                    'paypal' => 'test@test.com',
                    'address' => 'new address',
                    'city' => 'New York',
                    'zip' => '12345',
                ],
                'relationships' => [
                    'state' => ['data' => ['type' => State::getResourceKey(), 'id' => State::STATE_ALABAMA]],
                    'photo' => ['data' => File::image('winner-photo.png', 2000, 2000)],
                    'affidavit' => ['data' => [File::create('affidavit.pdf')]],
                ]
            ]
        ];

        $url = route('application.related.winner.update', [
            'id' => $awarded->getId(),
            'include' => 'state',
        ]);

        $this->json('POST', $url, $data)
            ->assertJson([
                'data' => [
                    'attributes' => [
                        'name' => 'New winner name',
                        'testimonial' => 'Thank you!',
                    ],
                    'relationships' => [
                        'state' => ['data' => ['type' => State::getResourceKey(), 'id' => State::STATE_ALABAMA]],
                    ]
                ]
            ])
            ->assertStatus(200);
    }
}
