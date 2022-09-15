<?php namespace Tests\Feature;

use App\Entities\Application;
use App\Entities\ApplicationStatus;
use App\Entities\ApplicationWinner;
use App\Entities\State;
use App\Events\ApplicationAwardedEvent;
use App\Events\ScholarshipDeadlineEvent;
use App\Services\ScholarshipManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ScholarshipWinnerChooserTest extends TestCase
{

    public function test_scholarship_one_winner_on_deadline()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        /** @var ScholarshipManager $manager */
        $manager = app(ScholarshipManager::class);
        $this->actingAs($user = $this->generateUser());
        $settings = $this->generateScholarshipTemplate();
        $scholarship = $manager->publish($settings);

        $applications = [];
        $applications[] = $this->generateApplication($scholarship, 'test@testsfsf1.com');
        $applications[] = $this->generateApplication($scholarship, 'test@testsfsf2.com');
        $applications[] = $this->generateApplication($scholarship, 'test@testsfsf3.com');
        $applications[] = $this->generateApplication($scholarship, 'test@testsfsf4.com');
        $applications[] = $this->generateApplication($scholarship, 'test@testsfsf5.com');

        $scholarship->setExpiredAt(new \DateTime());
        $this->em()->flush($scholarship);

        $winners = [];

        Event::listen(ApplicationAwardedEvent::class, function(ApplicationAwardedEvent $event) use (&$winners) {
            $winners[] = $event->getApplicationId();
        });

        ScholarshipDeadlineEvent::dispatch($scholarship);

        $this->assertCount(1, $winners);

        /** @var Application[] $wonApplications */
        $wonApplications = array_filter(
            array_map(
                function(Application $application) use ($winners) {
                    return (in_array($application->getId(), $winners)) ? $application : null;
                },
                $applications
            )
        );

        $this->assertCount(1, $wonApplications);

        /** @var Application[] $won */
        $won = [];
        foreach ($wonApplications as $winner) {
            $won[] = $winner;
            $this->json('GET', route('application.show', $winner->getId()).'?include=scholarship')
                ->assertJson([
                    'data' => [
                        'id' => $winner->getId(),
                        'type' => 'application',
                        'attributes' => [
                            'data' => [
                                'name' => 'Test name',
                            ]
                        ],
                        'relationships' => [
                            'scholarship' => ['data' => ['id' => $scholarship->getId()]],
                        ],
                    ]
                ]);
        }

        $data = [
            'data' => [
                'attributes' => [
                    'name' => 'Test name',
                    'testimonial' => 'Thank you!',
                    'dateOfBirth' => (new \DateTime('- 17 year'))->format('Y-m-d'),
                    'phone' => '+1(303)2346789',
                    'email' => 'new@test.com',
                    'paypal' => 'test@test.com',
                    'address' => 'new address',
                    'city' => 'New York',
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

        $response = $this->call('POST', route('application.related.winner.update', $won[0]->getId()), $data)
            ->assertJson(['data' => [
                'attributes' => [
                    'phone' => '3032346789',
                    'testimonial' => 'Thank you!',
                ]
            ]])
            ->assertStatus(200);

        $headers = $this->getOAuthClientHeaders('scholarships');
        $response = json_decode($response->getContent(), true);

        $this->get(route('application_file.download', $response['data']['relationships']['photo']['data']['id']), $headers)
            ->assertHeader('Content-Disposition', 'attachment; filename=winner-photo.png')
            ->assertStatus(200);

        $this->json('GET', route('scholarship.show', $scholarship->getId()).'?include=winners', [], $headers)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'relationships' => [
                        'winners' => [
                            'data' => [
                                [
                                    'type' => ApplicationWinner::getResourceKey(),
                                ],
                            ]
                        ]
                    ]
                ],
                'included' => [
                    [],[],[], [],
                    [
                        'type' => ApplicationWinner::getResourceKey(),
                        'attributes' => [
                            'name' => 'Test name'
                        ]
                    ]
                ]
            ]);
    }

}
