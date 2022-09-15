<?php namespace Tests\Feature;

use App\Doctrine\Types\RecurrenceConfigType\OneTimeConfig;
use App\Entities\ApplicationStatus;
use App\Entities\Field;
use App\Entities\Requirement;
use App\Entities\Scholarship;
use App\Entities\State;
use App\Repositories\ScholarshipRepository;
use App\Services\ApplicationService;
use App\Services\PubSubService;
use Carbon\Carbon;
use Google\Cloud\PubSub\PubSubClient;
use Google\Cloud\PubSub\Topic;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Tests\TestCase;

class PubSubServiceTest extends TestCase
{

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     */
    public function test_prepare_scholarship_data()
    {
        /** @var PubSubService $pubsubService */
        $pubsubService = app(PubSubService::class);
        $template = $this->generateScholarshipTemplate();

        /** @var Requirement $essay */
        $text = Requirement::find(Requirement::ESSAY);
        $link = Requirement::find(Requirement::LINK);
        $file = Requirement::find(Requirement::PROOF_OF_ENROLLMENT);
        $image = Requirement::find(Requirement::GENERIC_PICTURE);

        $this->generateTemplateRequirement($template, $text);
        $this->generateTemplateRequirement($template, $link);
        $this->generateTemplateRequirement($template, $file);
        $this->generateTemplateRequirement($template, $image);

        $scholarship = $this->sm()->publish($template);

        $serviceReflection = new \ReflectionClass(PubSubService::class);
        $method = $serviceReflection->getMethod('prepareScholarshipData');
        $method->setAccessible(true);
        $method->invoke($pubsubService, $scholarship);

    }

    public function test_verified_email_passed_to_pubsub_notification()
    {
        $template = $this->generateScholarshipTemplate();

        /**
         * Check verified is false for the scholarships
         */
        $this->pubsubClient = \Mockery::mock(PubSubClient::class);
        $testTopic = \Mockery::mock(Topic::class);
        $testTopic->shouldReceive('publish')->once()->withArgs(function($message) {
            $this->assertJson($message['data']);
            $this->assertEquals(PubSubService::MESSAGE_SCHOLARSHIP_PUBLISHED, $message['attributes']['event']);
            $data = json_decode($message['data'], true);
            $this->assertArrayHasKey('isFree', $data);
            $this->assertFalse($data['isFree']);
            return true;
        });

        $this->pubsubClient->shouldReceive('topic')->once()->andReturn($testTopic);
        $this->app->instance(PubSubClient::class, $this->pubsubClient);

        $this->sm()->publish($template);

        /**
         * Check verified is true if it is set
         */
        $template = $this->generateScholarshipTemplate();
        $template->setIsFree(true);

        $this->pubsubClient = \Mockery::mock(PubSubClient::class);
        $testTopic = \Mockery::mock(Topic::class);
        $testTopic->shouldReceive('publish')->once()->withArgs(function($message) {
            $this->assertJson($message['data']);
            $this->assertEquals(PubSubService::MESSAGE_SCHOLARSHIP_PUBLISHED, $message['attributes']['event']);
            $data = json_decode($message['data'], true);
            $this->assertArrayHasKey('isFree', $data);
            $this->assertTrue($data['isFree']);
            return true;
        });

        $this->pubsubClient->shouldReceive('topic')->once()->andReturn($testTopic);
        $this->app->instance(PubSubClient::class, $this->pubsubClient);

        $this->sm()->publish($template);

    }

    public function test_application_events()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');
        $this->mauticService->shouldReceive('notifyWinner');
        $this->mauticService->shouldReceive('markContactAsWinner');

        $user = $this->generateUser();
        $organisation = $this->generateOrganisation('Test organisation', $user);
        $template = $this->generateScholarshipTemplate($organisation);
        $scholarship = $this->sm()->publish($template);

        $this->pubsubClient = \Mockery::mock(PubSubClient::class);
        $testTopic = \Mockery::mock(Topic::class);
        $testTopic->shouldReceive('publish')->once()->withArgs(function($message) {
            $this->assertTrue(is_array($message));
            $this->assertTrue(isset($message['data']));
            $this->assertTrue(isset($message['attributes']['id']));
            $this->assertTrue(isset($message['attributes']['event']));
            $this->assertEquals(PubSubService::MESSAGE_APPLICATION_APPLIED, $message['attributes']['event']);
            return true;
        });
        $this->pubsubClient->shouldReceive('topic')->once()->andReturn($testTopic);
        $this->app->instance(PubSubClient::class, $this->pubsubClient);

        $application = $this->generateApplication($scholarship, 'test@test.com');

        $this->pubsubClient = \Mockery::mock(PubSubClient::class);
        $testTopic = \Mockery::mock(Topic::class);
        $testTopic->shouldReceive('publish')->times(3)->withArgs(function($message) {
            if (is_array($message) && in_array($message['attributes']['event'], [
                PubSubService::MESSAGE_SCHOLARSHIP_PUBLISHED,
                PubSubService::MESSAGE_SCHOLARSHIP_DEADLINE,
            ])) {
                return true;
            }

            $this->assertTrue(is_array($message));
            $this->assertTrue(isset($message['data']));
            $this->assertTrue(isset($message['attributes']['id']));
            $this->assertTrue(isset($message['attributes']['event']));
            $this->assertEquals(PubSubService::MESSAGE_APPLICATION_WINNER, $message['attributes']['event']);
            return true;
        });
        $this->pubsubClient->shouldReceive('topic')->times(3)->andReturn($testTopic);
        $this->app->instance(PubSubClient::class, $this->pubsubClient);

        $this->actingAs($user)
            ->json('patch', route('application.update', ['id' => $application->getId(), 'include' => 'status']),
            [
                'data' => [
                    'relationships' => [
                        'status' => [
                            'data' => [
                                'id' => ApplicationStatus::ACCEPTED,
                                'type' => ApplicationStatus::getResourceKey()
                            ]
                        ]
                    ]
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $application->getId(),
                    'relationships' => [
                        'status' => [
                            'data' => [
                                'id' => ApplicationStatus::ACCEPTED,
                                'type' => ApplicationStatus::getResourceKey()
                            ]
                        ]
                    ]
                ]
            ]);

        $this->sm()->expire($scholarship);

        $this->pubsubClient = \Mockery::mock(PubSubClient::class);
        $testTopic = \Mockery::mock(Topic::class);
        $testTopic->shouldReceive('publish')->times(1)->withArgs(function($message) use ($application) {
            $this->assertTrue(is_array($message));
            $this->assertTrue(isset($message['data']));
            $this->assertTrue(isset($message['attributes']['id']));
            $this->assertTrue(isset($message['attributes']['event']));
            $this->assertEquals($application->getId(), $message['attributes']['id']);
            $this->assertEquals(PubSubService::MESSAGE_APPLICATION_WINNER_DISQUALIFIED, $message['attributes']['event']);
            return true;
        });
        $this->pubsubClient->shouldReceive('topic')->times(1)->andReturn($testTopic);
        $this->app->instance(PubSubClient::class, $this->pubsubClient);

        $date = (new \DateTime('+2 day'))->format('Y-m-d');
        Artisan::call("scholarship:winner:notification", ['--date' => $date, '--ids' => $scholarship->getId()]);
        $date = (new \DateTime('+4 day'))->format('Y-m-d');
        Artisan::call("scholarship:winner:notification", ['--date' => $date, '--ids' => $scholarship->getId()]);

        /** @var ScholarshipRepository $repository */
        $repository = $this->em()->getRepository(Scholarship::class);
        $published = $repository->findSinglePublishedByTemplate($template);

        $this->pubsubClient = \Mockery::mock(PubSubClient::class);
        $testTopic = \Mockery::mock(Topic::class);
        $testTopic->shouldReceive('publish')->once()->withArgs(function($message) use ($published) {
            $this->assertTrue(is_array($message));
            $this->assertTrue(isset($message['data']));
            $this->assertTrue(isset($message['attributes']['id']));
            $this->assertTrue(isset($message['attributes']['event']));
            $this->assertEquals(ApplicationStatus::RECEIVED, json_decode($message['data'])->status);
            $this->assertEquals($published->getId(), $message['attributes']['scholarship_id']);
            $this->assertEquals(PubSubService::MESSAGE_APPLICATION_APPLIED, $message['attributes']['event']);
            return true;
        });
        $this->pubsubClient->shouldReceive('topic')->once()->andReturn($testTopic);
        $this->app->instance(PubSubClient::class, $this->pubsubClient);

        $application2 = $this->generateApplication($published, 'test@test.com', [
            ApplicationService::APPLICATION_STATUS => ApplicationStatus::RECEIVED,
        ]);

        $this->pubsubClient = \Mockery::mock(PubSubClient::class);
        $testTopic = \Mockery::mock(Topic::class);
        $testTopic->shouldReceive('publish')->once()->withArgs(function($message) use ($published) {
            $this->assertTrue(is_array($message));
            $this->assertTrue(isset($message['data']));
            $this->assertTrue(isset($message['attributes']['id']));
            $this->assertTrue(isset($message['attributes']['event']));
            $this->assertEquals(ApplicationStatus::ACCEPTED, json_decode($message['data'])->status);
            $this->assertEquals($published->getId(), $message['attributes']['scholarship_id']);
            $this->assertEquals(PubSubService::MESSAGE_APPLICATION_STATUS_CHANGED, $message['attributes']['event']);
            return true;
        });
        $this->pubsubClient->shouldReceive('topic')->once()->andReturn($testTopic);
        $this->app->instance(PubSubClient::class, $this->pubsubClient);

        $this->actingAs($user)
            ->json('patch', route('application.update', ['id' => $application2->getId(), 'include' => 'status']),
            [
                'data' => [
                    'relationships' => [
                        'status' => [
                            'data' => [
                                'id' => ApplicationStatus::ACCEPTED,
                                'type' => ApplicationStatus::getResourceKey()
                            ]
                        ]
                    ]
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $application2->getId(),
                    'relationships' => [
                        'status' => [
                            'data' => [
                                'id' => ApplicationStatus::ACCEPTED,
                                'type' => ApplicationStatus::getResourceKey()
                            ]
                        ]
                    ]
                ]
            ]);

        $this->pubsubClient = \Mockery::mock(PubSubClient::class);
        $testTopic = \Mockery::mock(Topic::class);
        $testTopic->shouldReceive('publish')->times(3)->withArgs(function($message) use ($application2) {
            if (is_array($message) && in_array($message['attributes']['event'], [
                PubSubService::MESSAGE_SCHOLARSHIP_PUBLISHED,
                PubSubService::MESSAGE_SCHOLARSHIP_DEADLINE,
            ])) {
                return true;
            }

            $this->assertTrue(is_array($message));
            $this->assertTrue(isset($message['data']));
            $this->assertTrue(isset($message['attributes']['id']));
            $this->assertTrue(isset($message['attributes']['event']));
            $this->assertEquals($application2->getId(), $message['attributes']['id']);
            $this->assertEquals(PubSubService::MESSAGE_APPLICATION_WINNER, $message['attributes']['event']);
            return true;
        });
        $this->pubsubClient->shouldReceive('topic')->times(3)->andReturn($testTopic);
        $this->app->instance(PubSubClient::class, $this->pubsubClient);

        $this->sm()->expire($published);

        $data = [
            'data' => [
                'attributes' => [
                    'name' => 'Test name',
                    'testimonial' => 'Thank you!',
                    'dateOfBirth' => (new \DateTime('- 17 year'))->format('Y-m-d'),
                    'phone' => '+123456789',
                    'email' => 'new@test.com',
                    'paypal' => 'test@test.com',
                    'city' => 'New York',
                    'address' => 'new address',
                    'zip' => '12345',
                ],
                'relationships' => [
                    'state' => ['data' => ['type' => State::getResourceKey(), 'id' => State::STATE_ALABAMA]],
                    'photo' => ['data' => UploadedFile::fake()->image('winner-photo.png', 2000, 2000)],
                    'affidavit' => [
                        'data' => [
                            UploadedFile::fake()->create('affidavit.pdf')
                        ]
                    ],
                ]
            ]
        ];

        $this->pubsubClient = \Mockery::mock(PubSubClient::class);
        $testTopic = \Mockery::mock(Topic::class);
        $testTopic->shouldReceive('publish')->once()->withArgs(function($message) use ($application2) {
            $this->assertTrue(is_array($message));
            $this->assertTrue(isset($message['data']));
            $this->assertTrue(isset($message['attributes']['id']));
            $this->assertTrue(isset($message['attributes']['event']));
            $this->assertEquals($application2->getId(), $message['attributes']['id']);
            $this->assertEquals(PubSubService::MESSAGE_APPLICATION_WINNER_FILLED, $message['attributes']['event']);
            return true;
        });
        $this->pubsubClient->shouldReceive('topic')->once()->andReturn($testTopic);
        $this->app->instance(PubSubClient::class, $this->pubsubClient);

        $this->call('POST', route('application.related.winner.update', $application2->getId()), $data)
            ->assertStatus(200)
            ->assertJson(['data' => [
                'attributes' => [
                    'testimonial' => 'Thank you!',
                ]
            ]]);

        $this->pubsubClient = \Mockery::mock(PubSubClient::class);
        $testTopic = \Mockery::mock(Topic::class);
        $testTopic->shouldReceive('publish')->once()->withArgs(function($message) use ($application2) {
            $this->assertTrue(is_array($message));
            $this->assertTrue(isset($message['data']));
            $this->assertTrue(isset($message['attributes']['id']));
            $this->assertTrue(isset($message['attributes']['event']));
            $this->assertEquals($application2->getId(), $message['attributes']['id']);

            $this->assertJson($message['data']);
            $data = json_decode($message['data'], true);
            $this->assertTrue(isset($data['winner']['wonDate']));
            $this->assertEquals($application2->getWinner()->getCreatedAt()->format('c'), $data['winner']['wonDate']);

            $this->assertEquals(PubSubService::MESSAGE_APPLICATION_WINNER_PUBLISHED, $message['attributes']['event']);
            return true;
        });
        $this->pubsubClient->shouldReceive('topic')->once()->andReturn($testTopic);
        $this->app->instance(PubSubClient::class, $this->pubsubClient);

        $this->json('post', route('scholarship_winner.create'), [
            'data' => [
                'attributes' => [
                    'name' => 'Test T.',
                    'testimonial' => '<b>Test</b>',
                ],
                'relationships' => [
                    'image' => ['data' => UploadedFile::fake()->image('winner-photo.png', 300, 300)],
                    'applicationWinner' => [
                        'data' => [
                            'id' => $application2->getWinner()->getId(),
                            'type' => $application2->getWinner()->getResourceKey(),
                        ]
                    ]
                ]
            ]
        ])
            ->assertStatus(201);

    }

    public function test_scholarship_events()
    {
        $template = $this->generateScholarshipTemplate();
        $template->setRecurrenceConfig(
            new OneTimeConfig(
                Carbon::create()->addDay(1),
                Carbon::create()->addDay(2)
            )
        );

        $this->pubsubClient = \Mockery::mock(PubSubClient::class);
        $testTopic = \Mockery::mock(Topic::class);
        $testTopic->shouldReceive('publish')->once()->withArgs(function($message) {
            $this->assertTrue(is_array($message));
            $this->assertTrue(isset($message['data']));

            $this->assertJson($message['data']);
            $data = json_decode($message['data'], true);
            $this->assertEquals(Scholarship::STATUS_UNPUBLISHED, $data['status']);

            $this->assertArrayHasKey('fields', $data);
            $this->assertCount(4, $data['fields']);
            $this->assertArrayHasKey('field', $data['fields'][0]);
            $this->assertArrayHasKey('field', $data['fields'][1]);
            $this->assertArrayHasKey('field', $data['fields'][2]);
            $this->assertArrayHasKey('field', $data['fields'][3]);
            $this->assertArrayHasKey('id', $data['fields'][0]['field']);
            $this->assertArrayHasKey('id', $data['fields'][1]['field']);
            $this->assertArrayHasKey('id', $data['fields'][2]['field']);
            $this->assertArrayHasKey('id', $data['fields'][3]['field']);
            $this->assertEquals(Field::NAME, $data['fields'][0]['field']['id']);
            $this->assertEquals(Field::PHONE, $data['fields'][1]['field']['id']);
            $this->assertEquals(Field::EMAIL, $data['fields'][2]['field']['id']);
            $this->assertEquals(Field::STATE, $data['fields'][3]['field']['id']);

            $this->assertTrue(isset($message['attributes']['id']));
            $this->assertTrue(isset($message['attributes']['event']));
            $this->assertTrue(in_array($message['attributes']['event'], [
                PubSubService::MESSAGE_SCHOLARSHIP_PUBLISHED,
            ]));
            return true;
        });

        $this->pubsubClient->shouldReceive('topic')->once()->andReturn($testTopic);
        $this->app->instance(PubSubClient::class, $this->pubsubClient);

        $scholarship = $this->sm()->publish($template);

        $this->pubsubClient = \Mockery::mock(PubSubClient::class);
        $testTopic = \Mockery::mock(Topic::class);
        $testTopic->shouldReceive('publish')->once()->withArgs(function($message) {
            $this->assertTrue(is_array($message));
            $this->assertTrue(isset($message['data']));

            $this->assertJson($message['data']);
            $data = json_decode($message['data'], true);
            $this->assertEquals(Scholarship::STATUS_PUBLISHED, $data['status']);

            $this->assertTrue(isset($message['attributes']['id']));
            $this->assertTrue(isset($message['attributes']['event']));
            $this->assertTrue(in_array($message['attributes']['event'], [
                PubSubService::MESSAGE_SCHOLARSHIP_STATUS_CHANGED,
            ]));
            return true;
        });

        $this->pubsubClient->shouldReceive('topic')->once()->andReturn($testTopic);
        $this->app->instance(PubSubClient::class, $this->pubsubClient);

        $this->sm()->maintain(Carbon::now()->addDays(2), [$scholarship->getId()]);

        $this->pubsubClient = \Mockery::mock(PubSubClient::class);
        $testTopic = \Mockery::mock(Topic::class);
        $testTopic->shouldReceive('publish')->once()->withArgs(function($message) {
            $this->assertTrue(is_array($message));
            $this->assertTrue(isset($message['data']));
            $this->assertTrue(isset($message['attributes']['id']));
            $this->assertTrue(isset($message['attributes']['event']));
            $this->assertTrue(in_array($message['attributes']['event'], [
                PubSubService::MESSAGE_SCHOLARSHIP_PUBLISHED,
                PubSubService::MESSAGE_SCHOLARSHIP_DEADLINE,
            ]));

            $this->assertJson($message['data']);
            $data = json_decode($message['data'], true);
            $this->assertEquals(Scholarship::STATUS_EXPIRED, $data['status']);

            return true;
        });

        $this->pubsubClient->shouldReceive('topic')->once()->andReturn($testTopic);
        $this->app->instance(PubSubClient::class, $this->pubsubClient);

        $this->sm()->expire($scholarship);
    }
}
