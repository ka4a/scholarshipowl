<?php namespace Tests\Feature;

use App\Entities\Application;
use App\Entities\ApplicationBatch;
use App\Entities\Scholarship;
use Art4\JsonApiClient\Helper\Parser;
use Illuminate\Http\Response;
use Tests\TestCase;
use WoohooLabs\Yang\JsonApi\Hydrator\ClassHydrator;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;
use WoohooLabs\Yang\JsonApi\Serializer\JsonDeserializer;

class ScholarshipGoIntegrationTest extends TestCase
{
    public function test_scholarship_go_batch_apply_and_fetch_application_data()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $this->actingAs($this->generateUser());

        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);
        $email = str_random().'@test.com';

        $data = [
            'data' => [
                'attributes' => [
                    'name' => 'test name',
                    'email' => $email,
                    'phone' => '+1 (123) 374-4444',
                    'state' => '1',
                    'source' => 'sgo',
                ]
            ]
        ];

        $url = route('application_batch.create', [
            'filter' => [
                'id' => ['operator' => 'eq', 'value' => $scholarship->getId()]
            ]
        ]);

        $response = $this->json('POST', $url, $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'type' => ApplicationBatch::getResourceKey(),
                    'attributes' => [
                        'status' => ApplicationBatch::STATUS_FINISHED,
                        'errors' => 0,
                    ]
                ]
            ]);

        $applicationBatch = json_decode($response->getContent(), true);
        $this->assertNotNull($applicationBatch['data']['id']);
        $this->assertEquals(
            $applicationBatch['data']['attributes']['eligible'],
            $applicationBatch['data']['attributes']['applied'],
            'Applied scholarships dos\'t match eligible.'
        );

        $this->json('GET', route('application_batch.related.applications.show', [
            'id' => $applicationBatch['data']['id'],
             'include' => 'scholarship',
        ]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'type' => Application::getResourceKey(),
                        'attributes' => [
                            'source' => 'sgo'
                        ],
                        'relationships' => [
                            'scholarship' => [
                                'data' => [
                                    'type' => Scholarship::getResourceKey(),
                                    'id' => $scholarship->getId(),
                                ]
                            ]
                        ]
                    ]
                ],
            ]);
    }
}
