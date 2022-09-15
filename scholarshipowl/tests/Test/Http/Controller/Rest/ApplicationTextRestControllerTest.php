<?php namespace Test\Http\Controller\Rest;

use App\Testing\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class ApplicationTextRestControllerTest extends TestCase
{
    public function testFilesValidations()
    {
        static::$truncate[] = 'application_text';
        $this->actingAs($account = $this->generateAccount());
        $scholarship = $this->generateScholarship();
        $requirementText = $this->generateRequirementText($scholarship, false);

        $params = ['requirementTextId' => $requirementText->getId()];
        $file = UploadedFile::fake()->create('test.doc', 1 * 1024);

        $resp = $this->call('POST', route('rest::v1.application.text.store'), $params, [], ['file' => $file]);
        $this->assertDatabaseMissing('application_text', [
                'requirement_text_id' => $requirementText->getId(),
                'account_id' => $account->getAccountId()]
        );
        $this->seeJsonSubset($resp, [
            'status' => JsonResponse::HTTP_BAD_REQUEST,
            'error' => 'Requirement text (1) not allowing uploading files!',
        ]);

        $requirementText->setAllowFile(true);
        $requirementText->setFileExtension('pdf');
        \EntityManager::flush($requirementText);
        $resp = $this->call('POST', route('rest::v1.application.text.store'), $params, [], ['file' => $file]);
        $this->assertDatabaseMissing('application_text', [
                'requirement_text_id' => $requirementText->getId(),
                'account_id' => $account->getAccountId()]
        );
        $this->seeJsonSubset($resp, [
            'status' => JsonResponse::HTTP_BAD_REQUEST,
            'error' => [
                'extension' => ['The file format is incorrect. Please try one of these - pdf'],
            ],
        ]);

        $requirementText->setMaxFileSize(2);
        \EntityManager::flush($requirementText);
        $file = UploadedFile::fake()->create('test.pdf', 5 * 1024);
        $resp = $this->call('POST', route('rest::v1.application.text.store'), $params, [], ['file' => $file]);
        $this->assertDatabaseMissing('application_text', [
            'requirement_text_id' => $requirementText->getId(),
            'account_id' => $account->getAccountId()]
        );
        $this->seeJsonSubset($resp, [
            'status' => 400,
            'error' => [
                'size' => ['File size should be maximum: 2 Mb.'],
            ],
        ]);

        $file = new UploadedFile(__FILE__, 'test.pdf', null, null, true);
        $resp = $this->call('POST', route('rest::v1.application.text.store'), $params, [], ['file' => $file]);

        $this->assertTrue($resp->status() === 200);
        $this->assertDatabaseHas('application_text', [
            'requirement_text_id' => $requirementText->getId(),
            'account_id' => $account->getAccountId()]
        );
    }

    public function testBase64EssayFile()
    {
        static::$truncate[] = 'application_text';
        $this->actingAs($account = $this->generateAccount());
        $scholarship = $this->generateScholarship();
        $requirementText = $this->generateRequirementText($scholarship, false);
        $requirementText->setAllowFile(true);
        $requirementText->setFileExtension('pdf');
        \EntityManager::flush($requirementText);

        $params = [
            'requirementTextId' => $requirementText->getId(),
            'fileBase64' => $this->generateBase64pdf()
        ];

        $resp = $this->call('POST', route('rest::v1.application.text.store'), $params, []);

        $this->assertTrue($resp->status() === 200);
        $this->assertDatabaseHas('application_text', [
            'requirement_text_id' => $requirementText->getId(),
            'account_id' => $account->getAccountId()]
        );

        $this->assertTrue(substr($this->decodeResponseJson($resp)['data']['accountFile']['path'], -4) == '.pdf');
    }

    public function testSimpleCrudAction()
    {
        static::$truncate[] = 'application_text';
        $this->actingAs($account = $this->generateAccount());
        $scholarship = $this->generateScholarship();
        $requirementText = $this->generateRequirementText($scholarship, true);

        $applied = $this->generateAccount('applied@test.com');
        $this->generateApplicationText($requirementText, null, 'teststes', $applied);
        $this->generateApplication($scholarship, $account);

        $this->assertDatabaseMissing('application_text', [
            'requirement_text_id' => $requirementText->getId(),
            'account_id' => $account->getAccountId()]
        );
        $resp = $this->post(route('rest::v1.application.text.store'), [
            'requirementTextId' => $requirementText->getId(),
            'text' => 'test',
        ]);
        $this->assertDatabaseHas('application_text', [
                'requirement_text_id' => $requirementText->getId(),
                'account_id' => $account->getAccountId()]
        );
        $this->seeJsonSuccessSubset($resp, [
            'requirementTextId' => $requirementText->getId(),
            'accountFile' => null,
            'text' => 'test',
        ]);

        $resp = $this->post(route('rest::v1.application.text.store'), [
            'requirementTextId' => $requirementText->getId(),
            'text' => 'test2',
        ]);
        $this->seeJsonSuccessSubset($resp, [
            'requirementTextId' => $requirementText->getId(),
            'accountFile' => null,
            'text' => 'test2',
        ]);

        $resp = $this->call('PUT', route('rest::v1.application.text.update', 2), [], [], [
            'file' => new UploadedFile(__FILE__, 'test.doc', null, null, true),
        ]);
        $this->seeJsonSuccessSubset($resp, [
            'requirementTextId' => $requirementText->getId(),
            'accountFile' => ['realname' => 'test.doc'],
            'text' => 'test2',
        ]);

        $resp = $this->get(route('rest::v1.application.text.show', 2));
        $this->seeJsonSuccessSubset($resp, [
            'requirementTextId' => $requirementText->getId(),
            'accountFile' => ['realname' => 'test.doc'],
            'text' => 'test2',
        ]);

        $resp = $this->delete(route('rest::v1.application.text.destroy', 2));
        $this->seeJsonSuccessSubset($resp, ['scholarshipId' => 1]);
        $this->assertDatabaseMissing('application_text', [
                'requirement_text_id' => $requirementText->getId(),
                'account_id' => $account->getAccountId()]
        );
    }

    public function testSimpleIndexAction()
    {
        $this->actingAs($this->generateAdminAccount());
        $account1 = $this->generateAccount('test@teststestestse.com');
        $account2 = $this->generateAccount('test@teststestestse2.com');
        $accountFile1 = $this->generateAccountFile($account1);
        $accountFile2 = $this->generateAccountFile($account2);
        $scholarship1 = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();
        $requirementFile1 = $this->generateRequirementText($scholarship1);
        $requirementFile2 = $this->generateRequirementText($scholarship2, false);

        $applicationText1 = $this->generateApplicationText($requirementFile1, $accountFile1);
        $applicationText2 = $this->generateApplicationText($requirementFile1, $accountFile2);
        $applicationText3 = $this->generateApplicationText($requirementFile2, null, 'test', $account1);
        $applicationText4 = $this->generateApplicationText($requirementFile2, null, 'test', $account2);

        $resp = $this->get(route('rest::v1.application.text.index'));
        $this->seeJsonContains($resp, ['status' => 200, 'meta' => ['count' => 4, 'limit' => 1000, 'start' => 0]]);
        $this->seeJsonSubset($resp, ['data' => [
            0 => [
                'id' => $applicationText1->getId(),
                'accountId' => $account1->getAccountId(),
                'accountFile' => ['id' => $accountFile1->getId()],
                'requirementTextId' => $requirementFile1->getId(),
                'scholarshipId' => $scholarship1->getScholarshipId(),
                'text' => null,
            ]
        ]]);
        $this->seeJsonSubset($resp, ['data' => [
            1 => [
                'id' => $applicationText2->getId(),
                'accountId' => $account2->getAccountId(),
                'accountFile' => ['id' => $accountFile2->getId()],
                'requirementTextId' => $requirementFile1->getId(),
                'scholarshipId' => $scholarship1->getScholarshipId(),
                'text' => null,
            ]
        ]]);
        $this->seeJsonSubset($resp, ['data' => [
            2 => [
                'id' => $applicationText3->getId(),
                'accountId' => $account1->getAccountId(),
                'accountFile' => null,
                'requirementTextId' => $requirementFile2->getId(),
                'scholarshipId' => $scholarship2->getScholarshipId(),
                'text' => 'test',
            ]
        ]]);
        $this->seeJsonSubset($resp, ['data' => [
            3 => [
                'id' => $applicationText4->getId(),
                'accountId' => $account2->getAccountId(),
                'accountFile' => null,
                'requirementTextId' => $requirementFile2->getId(),
                'scholarshipId' => $scholarship2->getScholarshipId(),
                'text' => 'test',
            ],
        ]]);

        $this->actingAs($account1);
        $resp = $this->get(route('rest::v1.application.file.index'));
        $this->assertTrue($resp->status() === JsonResponse::HTTP_FORBIDDEN);
    }
}
