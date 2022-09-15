<?php

namespace Test\Http\Controller\Rest;

use App\Testing\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class ApplicationSpecialEligibilityRestControllerTest extends TestCase
{
    public function testSimpleCrudAction()
    {
        static::$truncate[] = 'application_special_eligibility';
        $this->actingAs($account = $this->generateAccount());
        $scholarship = $this->generateScholarship();
        $requirementSpecialEligibility = $this->generateRequirementSpecialEligibility($scholarship, 'test');

        $applicationRequirement = $this->generateApplicationSpecialEligibility($requirementSpecialEligibility, $account, 1);
        $this->generateApplication($scholarship, $account);

        $resp = $this->post(route('rest::v1.application.special-eligibility.store'), [
            'requirementId' => $requirementSpecialEligibility->getId(),
            'val' => 1,
        ]);
        $this->assertDatabaseHas('application_special_eligibility', [
                'requirement_id' => $requirementSpecialEligibility->getId(),
                'account_id' => $account->getAccountId()
            ]
        );
        $this->seeJsonSuccessSubset($resp, [
            'requirementId' => $requirementSpecialEligibility->getId(),
            'val' => 1,
        ]);

        $resp = $this->get(route('rest::v1.application.special-eligibility.show', $applicationRequirement->getId()));
        $this->seeJsonSuccessSubset($resp, [
            'requirementId' => $requirementSpecialEligibility->getId(),
            'val' => 1,
        ]);

        $resp = $this->delete(route('rest::v1.application.special-eligibility.destroy', $applicationRequirement->getId()));
        $this->seeJsonSuccessSubset($resp, ['scholarshipId' => 1]);
        $this->assertDatabaseMissing('application_special_eligibility', [
            'requirement_id' => $requirementSpecialEligibility->getId(),
            'account_id' => $account->getAccountId()]
        );
    }
}
