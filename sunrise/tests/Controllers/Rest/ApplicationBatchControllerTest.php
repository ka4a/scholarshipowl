<?php namespace Tests\Controllers\Rest;

use App\Entities\Application;
use App\Entities\ApplicationBatch;
use Tests\TestCase;

class ApplicationBatchControllerTest extends TestCase
{
    public function test_create_batch_application()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $user = $this->generateUser();
        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);

        $data = [
            'data' => [
                'attributes' => [
                    'name' => 'Test Full',
                    'email' => 'test@teststs.com',
                    'phone' => '+13004847833398',
                    'state' => '1',
                    'source' => 'test_application',
                ]
            ]
        ];

        $url = route('application_batch.create', [
            'filter' => [
                'id' => ['operator' => 'eq', 'value' => $scholarship->getId()]
            ]
        ]);

        $response = $this->actingAs($user)
            ->json('post', $url, $data)
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

        $this->json('get', route('application_batch.show', $applicationBatch['data']['id']))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'type' => ApplicationBatch::getResourceKey(),
                    'id' => $applicationBatch['data']['id'],
                    'attributes' => [
                        'status' => ApplicationBatch::STATUS_FINISHED,
                        'applied' => $applicationBatch['data']['attributes']['eligible'],
                        'errors' => 0,
                    ]
                ]
            ]);

        $this->json('get', route('application_batch.related.applications.show', $applicationBatch['data']['id']))
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'type' => Application::getResourceKey()
                    ]
                ]
            ]);

        $this->json('delete', route('application_batch.delete', $applicationBatch['data']['id']))
            ->assertOk();
    }
}
