<?php namespace Tests\Controllers\Front;

use Tests\TestCase;

class WinnerInformationControllerTest extends TestCase
{
    public function test_affidavit_download()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);
        $application = $this->generateApplication($scholarship, 'tes@tsfds.com');
        $this->sm()->expire($scholarship);

        $this->get(route('winner-information.affidavit', $application->getId()))
            ->assertStatus(200)
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('content-disposition', 'attachment; filename=affidavit.pdf');
    }
}
