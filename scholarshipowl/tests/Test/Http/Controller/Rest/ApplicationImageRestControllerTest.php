<?php namespace Test\Http\Controller\Rest;

use App\Testing\TestCase;
use Doctrine\Common\Util\Debug;
use Illuminate\Http\UploadedFile;

class ApplicationImageRestControllerTest extends TestCase
{

    public function testValidationRequirementImage()
    {
        static::$truncate[] = 'application_image';

        $this->actingAs($account = $this->generateAccount());
        $scholarship = $this->generateScholarship();
        $requirementImage = $this->generateRequirementImage($scholarship);
        $requirementImage->setFileExtension('gif');
        $requirementImage->setMaxFileSize(3);
        \EntityManager::flush();

        $accountFile = $this->generateAccountFile($account, $this->generateImage());
        $resp = $this->post(route('rest::v1.application.image.store', [
            'requirementImageId' => $requirementImage->getId(),
            'accountFileId' => $accountFile->getId(),
        ]));
        $this->seeJsonSubset($resp, [
            'status' => 400,
            'error' => [
                'extension' => ['The file format is incorrect. Please try one of these - gif'],
            ],
        ]);

        $file = UploadedFile::fake()->create('test.gif', 5 * 1024);
        $resp = $this->call('POST', route('rest::v1.application.image.store'), [
            'requirementImageId' => $requirementImage->getId(),
        ], [], [
            'file' => $file,
        ]);
        $this->seeJsonSubset($resp, [
            'status' => 400,
            'error' => [
                'size' => ['File size should be maximum: 3 Mb.'],
            ],
        ]);

        $requirementImage->setMinWidth(101);
        $requirementImage->setMaxWidth(null);
        $requirementImage->setMinHeight(null);
        $requirementImage->setMaxHeight(null);
        $requirementImage->setFileExtension('jpg');
        \EntityManager::flush();
        $accountFile = $this->generateAccountFile($account, $this->generateImage(25, 25));
        $resp = $this->post(route('rest::v1.application.image.store', [
            'requirementImageId' => $requirementImage->getId(),
            'accountFileId' => $accountFile->getId(),
        ]));
        $this->seeJsonSubset($resp, ['status' => 400, 'error' => ['file' => ['Image width should be at least 101 pixels.']]]);
        $requirementImage->setMinWidth(100);
        $requirementImage->setMaxWidth(null);
        $requirementImage->setMinHeight(null);
        $requirementImage->setMaxHeight(null);
        \EntityManager::flush();
        $accountFile = $this->generateAccountFile($account, $this->generateImage());
        $resp = $this->post(route('rest::v1.application.image.store', [
            'requirementImageId' => $requirementImage->getId(),
            'accountFileId' => $accountFile->getId(),
        ]));
        $this->assertTrue($resp->status() === 200);

        $requirementImage->setMinWidth(null);
        $requirementImage->setMaxWidth(99);
        $requirementImage->setMinHeight(null);
        $requirementImage->setMaxHeight(null);
        \EntityManager::flush();
        $accountFile = $this->generateAccountFile($account, $this->generateImage());
        $resp = $this->post(route('rest::v1.application.image.store', [
            'requirementImageId' => $requirementImage->getId(),
            'accountFileId' => $accountFile->getId(),
        ]));
        $this->seeJsonSubset($resp, ['status' => 400, 'error' => ['file' => ['Image width should be most 99 pixels.']]]);
        $requirementImage->setMinWidth(null);
        $requirementImage->setMaxWidth(100);
        $requirementImage->setMinHeight(null);
        $requirementImage->setMaxHeight(null);
        \EntityManager::flush();
        $accountFile = $this->generateAccountFile($account, $this->generateImage());
        $resp = $this->post(route('rest::v1.application.image.store', [
            'requirementImageId' => $requirementImage->getId(),
            'accountFileId' => $accountFile->getId(),
        ]));

        $requirementImage->setMinWidth(null);
        $requirementImage->setMaxWidth(null);
        $requirementImage->setMinHeight(101);
        $requirementImage->setMaxHeight(null);
        \EntityManager::flush();
        $accountFile = $this->generateAccountFile($account, $this->generateImage());
        $resp = $this->post(route('rest::v1.application.image.store', [
            'requirementImageId' => $requirementImage->getId(),
            'accountFileId' => $accountFile->getId(),
        ]));
        $this->seeJsonSubset($resp, ['status' => 400, 'error' => ['file' => ['Image height should be at least 101 pixels.']]]);
        $requirementImage->setMinWidth(null);
        $requirementImage->setMaxWidth(null);
        $requirementImage->setMinHeight(100);
        $requirementImage->setMaxHeight(null);
        \EntityManager::flush();
        $accountFile = $this->generateAccountFile($account, $this->generateImage());
        $resp = $this->post(route('rest::v1.application.image.store', [
            'requirementImageId' => $requirementImage->getId(),
            'accountFileId' => $accountFile->getId(),
        ]));
        $this->seeJsonSubset($resp, ['status' => 200]);

        $requirementImage->setMinWidth(null);
        $requirementImage->setMaxWidth(null);
        $requirementImage->setMinHeight(null);
        $requirementImage->setMaxHeight(99);
        \EntityManager::flush();
        $accountFile = $this->generateAccountFile($account, $this->generateImage());
        $resp = $this->post(route('rest::v1.application.image.store', [
            'requirementImageId' => $requirementImage->getId(),
            'accountFileId' => $accountFile->getId(),
        ]));
        $this->seeJsonSubset($resp, ['status' => 400, 'error' => ['file' => ['Image height should be most 99 pixels.']]]);
        $requirementImage->setMinWidth(null);
        $requirementImage->setMaxWidth(null);
        $requirementImage->setMinHeight(null);
        $requirementImage->setMaxHeight(100);
        $requirementImage->setFileExtension('jpg');
        \EntityManager::flush();
        $accountFile = $this->generateAccountFile($account, $this->generateImage());
        $resp = $this->post(route('rest::v1.application.image.store', [
            'requirementImageId' => $requirementImage->getId(),
            'accountFileId' => $accountFile->getId(),
        ]));
        $this->seeJsonSubset($resp, ['status' => 200]);

        $requirementImage->setMinWidth(99);
        $requirementImage->setMaxWidth(99);
        $requirementImage->setMinHeight(null);
        $requirementImage->setMaxHeight(null);
        \EntityManager::flush();
        $accountFile = $this->generateAccountFile($account, $this->generateImage());
        $resp = $this->post(route('rest::v1.application.image.store', [
            'requirementImageId' => $requirementImage->getId(),
            'accountFileId' => $accountFile->getId(),
        ]));
        $this->seeJsonSubset($resp, ['status' => 400, 'error' => ['file' => ['Image width should be 99 pixels.']]]);

        $requirementImage->setMinWidth(null);
        $requirementImage->setMaxWidth(null);
        $requirementImage->setMinHeight(101);
        $requirementImage->setMaxHeight(101);
        \EntityManager::flush();
        $accountFile = $this->generateAccountFile($account, $this->generateImage());
        $resp = $this->post(route('rest::v1.application.image.store', [
            'requirementImageId' => $requirementImage->getId(),
            'accountFileId' => $accountFile->getId(),
        ]));
        $this->seeJsonSubset($resp, ['status' => 400, 'error' => ['file' => ['Image height should be 101 pixels.']]]);

        $requirementImage->setMinWidth(100);
        $requirementImage->setMaxWidth(100);
        $requirementImage->setMinHeight(100);
        $requirementImage->setMaxHeight(100);
        \EntityManager::flush();
        $resp = $this->post(route('rest::v1.application.image.store', [
            'requirementImageId' => $requirementImage->getId(),
            'accountFileId' => $accountFile->getId(),
        ]));
        $this->seeJsonSubset($resp, ['status' => 200]);
    }

    public function testBase64ImageFile()
    {
        static::$truncate[] = 'application_image';

        $this->actingAs($account = $this->generateAccount());
        $scholarship = $this->generateScholarship();
        $requirementImage = $this->generateRequirementImage($scholarship);
        $requirementImage->setFileExtension('png');
        \EntityManager::flush();

        $params = [
            'requirementImageId' => $requirementImage->getId(),
            'fileBase64' => $this->generateBase64png()
        ];

        $resp = $this->call('POST', route('rest::v1.application.image.store'), $params, []);

        $this->assertTrue($resp->status() === 200);
        $this->assertDatabaseHas('application_image', [
            'requirement_image_id' => $requirementImage->getId(),
            'account_id' => $account->getAccountId()]
        );

        $this->assertTrue(substr($this->decodeResponseJson($resp)['data']['accountFile']['path'], -4) == '.png');
    }


    public function testSimpleCrudAction()
    {
        static::$truncate[] = 'application_image';

        $this->actingAs($account = $this->generateAccount());
        $requirementImage = $this->generateRequirementImage($scholarship = $this->generateScholarship());
        $accountFile = $this->generateAccountFile($account, $image = $this->generateImage());

        $this->assertDatabaseMissing('application_image', ['id' => 1]);
        $resp = $this->post(route('rest::v1.application.image.store'), [
            'requirementImageId' => $requirementImage->getId(),
            'accountFileId' => $accountFile->getId(),
        ]);
        $this->assertDatabaseHas('application_image', ['id' => 1]);
        $this->seeJsonSubset($resp, [
            'status' => 200,
            'data' => [
                'id' => 1,
                'accountId' => $account->getAccountId(),
                'accountFile' => [
                    'filename' => $image->getFilename(),
                ],
                'requirementImageId' => $requirementImage->getId(),
                'scholarship' => ['scholarshipId' => $scholarship->getScholarshipId()],
            ],
        ]);

        $resp = $this->get(route('rest::v1.application.image.show', 1));
        $this->seeJsonSubset($resp, [
            'status' => 200,
            'data' => [
                'id' => 1,
                'accountId' => $account->getAccountId(),
                'accountFile' => [
                    'filename' => $image->getFilename(),
                ],
                'requirementImageId' => $requirementImage->getId(),
                'scholarship' => ['scholarshipId' => $scholarship->getScholarshipId()],
            ],
        ]);

        $image = $this->generateImage();
        $file = new UploadedFile($image, $image->getFilename(), null, null, true);
        $resp = $this->call('PUT', route('rest::v1.application.image.update', 1), [], [], [
            'file' => $file,
        ]);
        $this->seeJsonSubset($resp, [
            'status' => 200,
            'data' => [
                'id' => 1,
                'accountId' => $account->getAccountId(),
                'accountFile' => [
                    'realname' => $image->getFilename(),
                ],
                'requirementImageId' => $requirementImage->getId(),
                'scholarship' => ['scholarshipId' => $scholarship->getScholarshipId()],
            ],
        ]);

        $resp = $this->delete(route('rest::v1.application.image.destroy', 1));
        $this->seeJsonSuccessSubset($resp, ['scholarshipId' => 1]);
        $this->assertDatabaseMissing('application_image', ['id' => 1]);


        $resp = $this->post(route('rest::v1.application.image.store'), [
            'requirementImageId' => $requirementImage->getId(),
            'accountFileId' => $accountFile->getId(),
            'fromCamera' =>  1
        ]);
        $this->assertDatabaseHas('application_image', ['id' => 2]);
        $this->seeJsonSubset($resp, [
            'status' => 200,
            'data' => [
                'id' => 2,
                'accountId' => $account->getAccountId(),
                'accountFile' => [
                    'filename' => $accountFile->getFilename(),
                ],
                'requirementImageId' => $requirementImage->getId(),
                'scholarship' => ['scholarshipId' => $scholarship->getScholarshipId()],
                'fromCamera' => 1
            ],
        ]);
    }

    public function testIndexAction()
    {
        $account1 = $this->generateAccount('test@teststestestse.com');
        $account2 = $this->generateAccount('test@teststestestse2.com');
        $accountFile1 = $this->generateAccountFile($account1, $this->generateImage());
        $accountFile2 = $this->generateAccountFile($account2, $this->generateImage());
        $scholarship1 = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();
        $requirementImage1 = $this->generateRequirementImage($scholarship1);
        $requirementImage2 = $this->generateRequirementImage($scholarship2);

        $applicationImage1 = $this->generateApplicationImage($accountFile1, $requirementImage1);
        $applicationImage2 = $this->generateApplicationImage($accountFile1, $requirementImage2);
        $applicationImage3 = $this->generateApplicationImage($accountFile2, $requirementImage1);
        $applicationImage4 = $this->generateApplicationImage($accountFile2, $requirementImage2);

        $this->actingAs($this->generateAdminAccount());
        $resp = $this->get(route('rest::v1.application.image.index'));
        $this->seeJsonSubset($resp, [
            'status' => 200,
            'meta' => ['count' => 4],
            'data' => [
                [
                    'id' => $applicationImage1->getId(),
                    'accountId' => $account1->getAccountId(),
                    'accountFile' => ['id' => $accountFile1->getId()],
                    'requirementImageId' => $requirementImage1->getId(),
                    'scholarshipId' => $scholarship1->getScholarshipId(),
                ],
                [
                    'id' => $applicationImage2->getId(),
                    'accountId' => $account1->getAccountId(),
                    'accountFile' => ['id' => $accountFile1->getId()],
                    'requirementImageId' => $requirementImage2->getId(),
                    'scholarshipId' => $scholarship2->getScholarshipId(),
                ],
                [
                    'id' => $applicationImage3->getId(),
                    'accountId' => $account2->getAccountId(),
                    'accountFile' => ['id' => $accountFile2->getId()],
                    'requirementImageId' => $requirementImage1->getId(),
                    'scholarshipId' => $scholarship1->getScholarshipId(),
                ],
                [
                    'id' => $applicationImage4->getId(),
                    'accountId' => $account2->getAccountId(),
                    'accountFile' => ['id' => $accountFile2->getId()],
                    'requirementImageId' => $requirementImage2->getId(),
                    'scholarshipId' => $scholarship2->getScholarshipId(),
                ],
            ],
        ]);

        $this->actingAs($account1);
        $resp = $this->get(route('rest::v1.application.file.index'));
        $this->assertTrue($resp->status() === 403);
    }

}
