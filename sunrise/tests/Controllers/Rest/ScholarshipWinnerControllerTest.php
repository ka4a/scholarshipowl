<?php namespace Tests\Controllers\Rest;

use App\Entities\ApplicationStatus;
use App\Entities\Scholarship;
use App\Entities\State;
use App\Repositories\ScholarshipRepository;
use Tests\TestCase;

class ScholarshipWinnerControllerTest extends TestCase
{
    public function test_scholarship_winner_index_action_filter_by_source()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        $user = $this->generateUser(1, false);
        $organisation = $this->generateOrganisation('Test org', $user);
        $template = $this->generateScholarshipTemplate($organisation);

        $scholarship1 = $this->sm()->publish($template);
        $application1 = $this->generateApplication($scholarship1, 'test@test.com', [], 'src1');
        $this->sm()->expire($scholarship1);
        $winner1 = $this->generateScholarshipWinner($application1->getWinner());

        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = $this->em()->getRepository(Scholarship::class);
        $scholarship2 = $scholarshipRepository->findSinglePublishedByTemplate($template);
        $application2 = $this->generateApplication($scholarship2, 'test@test.com', [], 'src2');
        $this->sm()->expire($scholarship2);
        $winner2 = $this->generateScholarshipWinner($application2->getWinner());

        $this->actingAs($user);

        $this->json('get', route('scholarship_winner.index').'?'.http_build_query(['source' => 'src1']))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $winner1->getId(),
                        'type' => $winner1->getResourceKey(),
                    ],
                ],
            ]);

        $this->json('get', route('scholarship_winner.index').'?'.http_build_query(['source' => 'src2']))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $winner2->getId(),
                        'type' => $winner2->getResourceKey(),
                    ],
                ],
            ]);
    }

    public function test_scholarship_winner_show_action()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        $user = $this->generateUser(1, false);
        $organisation = $this->generateOrganisation('Test org', $user);
        $template = $this->generateScholarshipTemplate($organisation);

        $scholarship1 = $this->sm()->publish($template);
        $application1 = $this->generateApplication($scholarship1, 'test@test.com', [], 'src1');

        $this->sm()->expire($scholarship1);
        $application1->getWinner()->setDateOfBirth(new \DateTime('-16 years'));
        $application1->getWinner()->setState(State::find(State::STATE_ALABAMA));
        $application1->getWinner()->setZip('12345');
        // $this->em()->flush($application1->getWinner());

        $winner1 = $this->generateScholarshipWinner($application1->getWinner());

        $this->actingAs($user);
        $url = route('scholarship_winner.show', $winner1->getId()) . '?' . http_build_query(['include' => 'scholarship']);
        $this->json('get', $url)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'type' => $winner1->getResourceKey(),
                    'id' => ''.$winner1->getId(),
                    'attributes' => [
                        'age' => 16,
                        'state' => 'AL',
                        'zip' => '12345',
                        'amount' => $scholarship1->getAmount(),
                    ]
                ],
                'included' => [
                    [],
                    [
                        'id' => $scholarship1->getId(),
                        'type' => $scholarship1->getResourceKey(),
                    ]
                ]
            ]);
    }
}
