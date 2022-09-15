<?php namespace Test\Http\Controller\Rest;

use App\Entity\AccountFile;
use App\Testing\TestCase;
use Illuminate\Http\UploadedFile;

class ApplicationFileRestControllerTest extends TestCase
{

    public function testApplicationFileScholarshipRequirementsValidations()
    {
        static::$truncate[] = 'application_file';

        $this->actingAs($account = $this->generateAccount('test@testsetset.com'));
        $scholarship = $this->generateScholarship();
        $requirementFile = $this->generateRequirementFile($scholarship);
        $requirementFile->setFileExtension('doc, docx, pdf');
        $accountFile = $this->generateAccountFile($account);
        \EntityManager::flush();

        $resp = $this->post(route('rest::v1.application.file.store'), [
            'requirementFileId' => $requirementFile->getId(),
            'accountFileId' => $accountFile->getId(),
        ]);

        $this->seeJsonSubset($resp, [
            'status' => 400,
            'error' => ['extension' => ['The file format is incorrect. Please try one of these - doc, docx, pdf']],
        ]);

        $accountFile = $this->generateAccountFile($account, 'test.doc');
        $resp = $this->post(route('rest::v1.application.file.store'), [
            'requirementFileId' => $requirementFile->getId(),
            'accountFileId' => $accountFile->getId(),
        ]);

        $this->seeJsonSubset($resp, [
            'status' => 200,
            'data' => [
                'requirementFileId' => $requirementFile->getId(),
                'scholarship' => ['scholarshipId' => $scholarship->getScholarshipId()],
            ],
        ]);

        $requirementFile->setMaxFileSize(1);
        \EntityManager::flush();

        $file = UploadedFile::fake()->create('test.doc', 2 * 1024);
       // $file = new UploadedFile(__FILE__, 'test.doc', null, 24 * 1024 * 1024, null, true);
        $resp = $this->call('POST', route('rest::v1.application.file.store'), ['requirementFileId' => $requirementFile->getId()], [], ['file' => $file]);
        $this->seeJsonSubset($resp, [
            'status' => 400,
            'error' => ['size' => ['File size should be maximum: 1 Mb.']],
        ]);

        $file = new UploadedFile(__FILE__, 'test.doc', null, null, true);
        $accountFile = new AccountFile($file, $account);
        \EntityManager::persist($accountFile);
        \EntityManager::flush();

        $resp = $this->post(route('rest::v1.application.file.store'), [
            'requirementFileId' => $requirementFile->getId(),
            'accountFileId' => $accountFile->getId(),
        ]);
        $this->seeJsonSubset($resp,     [
            'status' => 200,
            'data' => [
                'requirementFileId' => $requirementFile->getId(),
                'scholarship' => ['scholarshipId' => $scholarship->getScholarshipId()],
            ],
        ]);
    }

    public function testBase64File()
    {
        static::$truncate[] = 'application_file';

        $this->actingAs($account = $this->generateAccount('test@testsetset.com'));
        $scholarship = $this->generateScholarship();
        $requirementFile = $this->generateRequirementFile($scholarship);
        $accountFile = $this->generateAccountFile($account);
        $requirementFile->setFileExtension('pdf');
        \EntityManager::flush();

        $resp = $this->post(route('rest::v1.application.file.store'), [
            'requirementFileId' => $requirementFile->getId(),
            'fileBase64' => $this->generateBase64pdf()
        ]);

        $this->assertTrue($resp->status() === 200);
        $this->assertDatabaseHas('application_file', [
            'requirement_file_id' => $requirementFile->getId(),
            'account_id' => $account->getAccountId()]
        );

        $this->assertTrue(substr($this->decodeResponseJson($resp)['data']['accountFile']['path'], -4) == '.pdf');
    }

    public function testSimpleCrudAction()
    {
        static::$truncate[] = 'application_file';

        $this->actingAs($account = $this->generateAccount('test@testsetset.com'));
        $scholarship = $this->generateScholarship();
        $requirementFile = $this->generateRequirementFile($scholarship);
        $accountFile = $this->generateAccountFile($account);

        $this->assertDatabaseMissing('application_file', ['id' => 1]);
        $resp = $this->post(route('rest::v1.application.file.store'), [
            'requirementFileId' => $requirementFile->getId(),
            'accountFileId' => $accountFile->getId(),
        ]);
        $this->seeJsonSuccessSubset($resp, [
            'id' => 1,
            'scholarship' => [
                'scholarshipId' => $scholarship->getScholarshipId(),
            ],
            'requirementFileId' => $requirementFile->getId(),
        ]);
        $this->assertDatabaseHas('application_file', ['id' => 1]);

        $resp = $this->get(route('rest::v1.application.file.show', 1));
        $this->seeJsonSuccessSubset($resp, [
            'id' => 1,
            'scholarship' => [
                'scholarshipId' => $scholarship->getScholarshipId(),
            ],
            'requirementFileId' => $requirementFile->getId(),
        ]);

        $file = new UploadedFile(__FILE__, 'test.doc', null, null, true);
        $resp = $this->call('PUT', route('rest::v1.application.file.update', 1), [], [], ['file' => $file]);

        $this->seeJsonSuccessSubset($resp, [
            'id' => 1,
            'accountFile' => [
                'id' => 2,
                'accountId' => $account->getAccountId(),
            ],
            'scholarship' => [
                'scholarshipId' => $scholarship->getScholarshipId(),
            ],
            'requirementFileId' => $requirementFile->getId(),
        ]);

        $resp = $this->delete(route('rest::v1.application.file.destroy', 1));
        $this->seeJsonSuccessSubset($resp, ['scholarshipId' => 1]);
        $this->assertDatabaseMissing('application_file', ['id' => 1]);
    }

    public function testIndexAction()
    {
        $this->actingAs($this->generateAdminAccount());
        $account1 = $this->generateAccount('test@teststestestse.com');
        $account2 = $this->generateAccount('test@teststestestse2.com');
        $accountFile1 = $this->generateAccountFile($account1);
        $accountFile2 = $this->generateAccountFile($account2);
        $scholarship1 = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();
        $requirementFile1 = $this->generateRequirementFile($scholarship1);
        $requirementFile2 = $this->generateRequirementFile($scholarship2);

        $applicationFile1 = $this->generateApplicationFile($accountFile1, $requirementFile1);
        $applicationFile2 = $this->generateApplicationFile($accountFile1, $requirementFile2);
        $applicationFile3 = $this->generateApplicationFile($accountFile2, $requirementFile1);
        $applicationFile4 = $this->generateApplicationFile($accountFile2, $requirementFile2);

        $resp = $this->get(route('rest::v1.application.file.index'));
        $this->seeJsonSubset($resp, [
            'status' => 200,
            'data' => [
                [
                    'id' => $applicationFile1->getId(),
                    'accountId' => $account1->getAccountId(),
                    'requirementFileId' => $requirementFile1->getId(),
                    'scholarshipId' => $scholarship1->getScholarshipId(),
                ],
                [
                    'id' => $applicationFile2->getId(),
                    'accountId' => $account1->getAccountId(),
                    'requirementFileId' => $requirementFile2->getId(),
                    'scholarshipId' => $scholarship2->getScholarshipId(),
                ],
                [
                    'id' => $applicationFile3->getId(),
                    'accountId' => $account2->getAccountId(),
                    'requirementFileId' => $requirementFile1->getId(),
                    'scholarshipId' => $scholarship1->getScholarshipId(),
                ],
                [
                    'id' => $applicationFile4->getId(),
                    'accountId' => $account2->getAccountId(),
                    'requirementFileId' => $requirementFile2->getId(),
                    'scholarshipId' => $scholarship2->getScholarshipId(),
                ],
            ],
            'meta' => [
                'count' => 4,
            ],
        ]);

        $this->actingAs($account1);
        $resp = $this->get(route('rest::v1.application.file.index'));
        $this->assertTrue($resp->status() == 403);
    }

}
