<?php namespace Tests\Controllers\Rest;

use App\Entities\ApplicationFile;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ApplicationFileControllerTest extends TestCase
{
    public function test_controller_upload_file()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');
        $this->actingAs($user = $this->generateUser());
        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);
        $application = $this->generateApplication($scholarship, 'test@test.com');

        $file = File::image('winner-photo.png', 2000, 2000);
        $this->call('POST', route('application_file.create', [
            'id' => $application->getId(),
            'include' => 'application'
        ]), [
            'data' => [
                'type' => ApplicationFile::getResourceKey(),
                'relationships' => [
                    'application' => [
                        'data' => ['type' => $application->getResourceKey(), 'id' => $application->getId()]
                    ]
                ]
            ]
        ], [], ['file' => $file])
            ->assertStatus(201)
            ->assertJson(['data' => [
                'attributes' => [
                    'mimeType' => 'image/png',
                    'size' => $file->getSize(),
                ],
                'relationships' => [
                    'application' => [
                        'data' => ['type' => $application->getResourceKey(), 'id' => $application->getId()]
                    ]
                ],
            ]]);
    }
}
