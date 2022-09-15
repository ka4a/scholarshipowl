<?php namespace Tests\Controllers\Rest;

use App\Entities\Scholarship;
use App\Entities\ScholarshipWinner;
use App\Repositories\ScholarshipRepository;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ScholarshipWebsiteControllerTest extends TestCase
{
    public function test_simple_website_show_action()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        $headers = $this->getOAuthClientHeaders('*');
        $template = $this->generateScholarshipTemplate();
        $website = $template->getWebsite();

        $scholarship1 = $this->sm()->publish($template);
        $application1 = $this->generateApplication($scholarship1, 'test@test.com');
        $this->sm()->expire($scholarship1);
        $winner1 = $this->generateScholarshipWinner($application1->getWinner());

        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = $this->em()->getRepository(Scholarship::class);
        $scholarship2 = $scholarshipRepository->findSinglePublishedByTemplate($template);
        $application2 = $this->generateApplication($scholarship2, 'test@test.com');
        $this->sm()->expire($scholarship2);
        $winner2 = $this->generateScholarshipWinner($application2->getWinner());

        $url = route('scholarship_website.show', ['id' => $website->getId(), 'include' => 'winners']);
        $this->json('GET', $url, [], $headers)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => ''.$website->getId(),
                    'type' => $website->getResourceKey(),
                    'attributes' => [
                        'domain' => $website->getDomain(),
                        'gtm' => 'TT-GTMIDTEST',
                    ],
                    'relationships' => [
                        'winners' => [
                            'data' => [
                                ['type' => $winner1->getResourceKey(), 'id' => $winner1->getId()],
                                ['type' => $winner2->getResourceKey(), 'id' => $winner2->getId()],
                            ]
                        ]
                    ]
                ]
            ]);
    }
}
