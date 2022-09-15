<?php namespace Test\Http\Controller\Rest;

use App\Testing\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class ApplicationInputRestControllerTest extends TestCase
{
    public function testSimpleCrudAction()
    {
        static::$truncate[] = 'application_input';
        $this->actingAs($account = $this->generateAccount());
        $scholarship = $this->generateScholarship();
        $requirementInput = $this->generateRequirementInput($scholarship);

        $text = 'http://www.youtube.com/watch?v=wqeweqw';

        $applied = $this->generateAccount('applied@test.com');
        $this->generateApplicationInput($requirementInput, $applied);
        $this->generateApplication($scholarship, $account);

        $this->assertDatabaseMissing('application_input', [
            'requirement_input_id' => $requirementInput->getId(),
            'account_id' => $account->getAccountId()]
        );
        $resp = $this->post(route('rest::v1.application.input.store'), [
            'requirementInputId' => $requirementInput->getId(),
            'text' => $text,
        ]);
        $this->assertDatabaseHas('application_input', [
                'requirement_input_id' => $requirementInput->getId(),
                'account_id' => $account->getAccountId()]
        );
        $this->seeJsonSuccessSubset($resp, [
            'requirementInputId' => $requirementInput->getId(),
            'text' => $text,
        ]);

        $resp = $this->post(route('rest::v1.application.input.store'), [
            'requirementInputId' => $requirementInput->getId(),
            'text' => $text,
        ]);
        $this->seeJsonSuccessSubset($resp, [
            'requirementInputId' => $requirementInput->getId(),
            'text' => $text,
        ]);

        $resp = $this->call('PUT', route('rest::v1.application.input.update', 2), ["text" => $text]);
        $this->seeJsonSuccessSubset($resp, [
            'requirementInputId' => $requirementInput->getId(),
            'text' => $text,
        ]);

        $resp = $this->get(route('rest::v1.application.input.show', 2));
        $this->seeJsonSuccessSubset($resp, [
            'requirementInputId' => $requirementInput->getId(),
            'text' => $text,
        ]);

        $resp = $this->delete(route('rest::v1.application.input.destroy', 2));
        $this->seeJsonSuccessSubset($resp, ['scholarshipId' => 1]);
        $this->assertDatabaseMissing('application_input', [
                'requirement_input_id' => $requirementInput->getId(),
                'account_id' => $account->getAccountId()]
        );
    }

    public function testSimpleIndexAction()
    {
        $this->actingAs($this->generateAdminAccount());
        $account1 = $this->generateAccount('test@teststestestse.com');
        $account2 = $this->generateAccount('test@teststestestse2.com');
        $scholarship1 = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();
        $requirementInput1 = $this->generateRequirementInput($scholarship1);
        $requirementInput2 = $this->generateRequirementInput($scholarship2);

        $applicationInput1 = $this->generateApplicationInput($requirementInput1, $account1);
        $applicationInput2 = $this->generateApplicationInput($requirementInput2, $account1);
        $applicationInput3 = $this->generateApplicationInput($requirementInput1, $account2, 'test');
        $applicationInput4 = $this->generateApplicationInput($requirementInput2, $account2, 'test');

        $resp = $this->get(route('rest::v1.application.input.index'));
        $this->seeJsonContains($resp, ['status' => 200, 'meta' => ['count' => 4, 'limit' => 1000, 'start' => 0]]);
        $this->seeJsonSubset($resp, ['data' => [
            0 => [
                'id' => $applicationInput1->getId(),
                'accountId' => $account1->getAccountId(),
                'requirementInputId' => $requirementInput1->getId(),
                'scholarshipId' => $scholarship1->getScholarshipId(),
                'text' => null
            ]
        ]]);
        $this->seeJsonSubset($resp, ['data' => [
            1 => [
                'id' => $applicationInput2->getId(),
                'accountId' => $account1->getAccountId(),
                'requirementInputId' => $requirementInput2->getId(),
                'scholarshipId' => $scholarship2->getScholarshipId(),
                'text' => null,
            ]
        ]]);
        $this->seeJsonSubset($resp, ['data' => [
            2 => [
                'id' => $applicationInput3->getId(),
                'accountId' => $account2->getAccountId(),
                'requirementInputId' => $requirementInput1->getId(),
                'scholarshipId' => $scholarship1->getScholarshipId(),
                'text' => 'test',
            ]
        ]]);
        $this->seeJsonSubset($resp, ['data' => [
            3 => [
                'id' => $applicationInput4->getId(),
                'accountId' => $account2->getAccountId(),
                'requirementInputId' => $requirementInput2->getId(),
                'scholarshipId' => $scholarship2->getScholarshipId(),
                'text' => 'test',
            ],
        ]]);

        $this->actingAs($account1);
        $resp = $this->get(route('rest::v1.application.input.index'));
        $this->assertTrue($resp->status() === JsonResponse::HTTP_FORBIDDEN);
    }
}
