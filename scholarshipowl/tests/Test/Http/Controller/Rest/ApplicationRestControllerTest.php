<?php namespace Test\Http\Controller\Rest;

use App\Entity\ApplicationEssayStatus;
use App\Entity\Package;
use App\Entity\Resource\AccountFileResource;
use App\Testing\TestCase;

class ApplicationRestControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        static::$truncate = ['eligibility_cache', 'application'];
    }

    public function testFreemiumCreditsApplication()
    {
        $this->actingAs($account = $this->generateAccount());
        $freemiumPackage = $this->generatePackage(Package::EXPIRATION_TYPE_NO_EXPIRY)
            ->setIsScholarshipsUnlimited(false)
            ->setIsFreemium(true)
            ->setFreemiumRecurrencePeriod(Package::EXPIRATION_PERIOD_TYPE_DAY)
            ->setFreemiumRecurrenceValue(1)
            ->setFreemiumCredits(3);
        $this->em->flush($freemiumPackage);
        $this->generateSubscription($freemiumPackage, $account);

        $scholarship1 = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();
        $scholarship3 = $this->generateScholarship();
        $scholarship4 = $this->generateScholarship();

        $resp = $this->post(route('rest::v1.application.store'), ['scholarshipId' => $scholarship1->getScholarshipId()]);
        $this->seeJsonSuccessSubset($resp, [
                'scholarshipId'  => $scholarship1->getScholarshipId(),
                'isFavorite'     => 0,
                'isSent'         => 0,
                'derivedStatus'  => 'SENT',
                'url'            => $scholarship1->getPublicUrl(),
                'logo'           => $scholarship1->getLogoUrl(),
                'title'          => 'test',
                'description'    => null,
                'externalUrl'    => 'test',
                'TOSUrl'         => null,
                'PPUrl'          => null,
                'amount'         => '10.00',
                'expirationDate' => $this->dateToArray($scholarship1->getExpirationDate()),
                'isRecurrent'    => false,
                'image'          => null,
                'requirements'   =>
                    [
                        'texts'  => [],
                        'files'  => [],
                        'images' => [],
                        'inputs' => [],
                    ],
                'application'    =>
                    [
                        'status' => 4,
                        'texts'  => [],
                        'files'  => [],
                        'images' => [],
                        'inputs' => [],
                    ],
            ], ['credits' => 2]);


        $resp = $this->post(route('rest::v1.application.store'),
            ['scholarshipId' => $scholarship2->getScholarshipId()]);
        $this->seeJsonSuccessSubset($resp, [
            'scholarshipId'  => $scholarship2->getScholarshipId(),
            'isFavorite'     => 0,
            'isSent'         => 0,
            'url'            => $scholarship2->getPublicUrl(),
            'logo'           => $scholarship2->getLogoUrl(),
            'title'          => 'test',
            'description'    => null,
            'externalUrl'    => 'test',
            'TOSUrl'         => null,
            'PPUrl'          => null,
            'amount'         => '10.00',
            'expirationDate' => $this->dateToArray($scholarship2->getExpirationDate()),
            'isRecurrent'    => false,
            'image'          => null,
            'requirements'   =>
                [
                    'texts'  => [],
                    'files'  => [],
                    'images' => [],
                    'inputs' => [],
                ],
            'application'    =>
                [
                    'status' => 4,
                    'texts'  => [],
                    'files'  => [],
                    'images' => [],
                    'inputs' => [],
                ],
        ], ['credits' => 1]);

        $resp = $this->post(route('rest::v1.application.store'), ['scholarshipId' => $scholarship3->getScholarshipId()]);
        $this->seeJsonSuccessSubset($resp,[
            'scholarshipId'  => $scholarship3->getScholarshipId(),
            'isFavorite'     => 0,
            'isSent'         => 0,
            'url'            => $scholarship3->getPublicUrl(),
            'logo'           => $scholarship3->getLogoUrl(),
            'title'          => 'test',
            'description'    => null,
            'externalUrl'    => 'test',
            'TOSUrl'         => null,
            'PPUrl'          => null,
            'amount'         => '10.00',
            'expirationDate' => $this->dateToArray($scholarship3->getExpirationDate()),
            'isRecurrent'    => false,
            'image'          => null,
            'requirements'   =>
                [
                    'texts'  => [],
                    'files'  => [],
                    'images' => [],
                    'inputs' => [],
                ],
            'application'    =>
                [
                    'status' => 4,
                    'texts'  => [],
                    'files'  => [],
                    'images' => [],
                    'inputs' => [],
                ],
        ], ['credits' => 0]);

        $resp = $this->post(route('rest::v1.application.store'), ['scholarshipId' => $scholarship4->getScholarshipId()]);
        $this->assertTrue($resp->status() === 409);
    }

    public function testApplicationApplyNoRequirements()
    {
        $this->actingAs($account = $this->generateAccount());
        $this->generateSubscription(null, $account);
        $scholarship = $this->generateScholarship();

        $resp = $this->post(route('rest::v1.application.store'), ['scholarshipId' => $scholarship->getScholarshipId()]);
        $this->seeJsonSuccessSubset($resp, [
            'scholarshipId'  => $scholarship->getScholarshipId(),
            'isFavorite'     => 0,
            'isSent'         => 0,
            'url'            => $scholarship->getPublicUrl(),
            'logo'           => $scholarship->getLogoUrl(),
            'title'          => 'test',
            'description'    => null,
            'externalUrl'    => 'test',
            'TOSUrl'         => null,
            'PPUrl'          => null,
            'amount'         => '10.00',
            'expirationDate' => $this->dateToArray($scholarship->getExpirationDate()),
            'isRecurrent'    => false,
            'image'          => null,
            'requirements'   =>
                [
                    'texts'  => [],
                    'files'  => [],
                    'images' => [],
                    'inputs' => [],
                ],
            'application'    =>
                [
                    'status' => 4,
                    'texts'  => [],
                    'files'  => [],
                    'images' => [],
                    'inputs' => [],
                ],
        ]);
    }

    public function testApplicationWithoutRequirements()
    {
        $this->actingAs($account = $this->generateAccount());
        $this->generateSubscription(null, $account);
        $scholarship = $this->generateScholarship();
        $requirementText = $this->generateRequirementText($scholarship);

        $resp = $this->post(route('rest::v1.application.store'), ['scholarshipId' => $scholarship->getScholarshipId()]);
        $this->seeJsonSubset($resp, [
            'status' => 400,
            'error' => 'Scholarship missing text requirement: ' . $requirementText->getId(),
        ]);

        $scholarship->removeRequirementText($requirementText);
        $requirementFile = $this->generateRequirementFile($scholarship);
        \EntityManager::flush();

        $resp = $this->post(route('rest::v1.application.store'), ['scholarshipId' => $scholarship->getScholarshipId()]);
        $this->seeJsonSubset($resp, [
            'status' => 400,
            'error' => 'Scholarship missing file requirement: ' . $requirementFile->getId(),
        ]);

        $scholarship->removeRequirementFile($requirementFile);
        $requirementImage = $this->generateRequirementImage($scholarship);
        \EntityManager::flush();

        $resp = $this->post(route('rest::v1.application.store'), ['scholarshipId' => $scholarship->getScholarshipId()]);
        $this->seeJsonSubset($resp, [
            'status' => 400,
            'error' => 'Scholarship missing image requirement: ' . $requirementImage->getId(),
        ]);
    }

    public function testApplicationWithRequirements()
    {
        $this->actingAs($account = $this->generateAccount());
        $this->generateSubscription(null, $account);
        $scholarship = $this->generateScholarship();
        $requirementText = $this->generateRequirementText($scholarship);
        $requirementFile = $this->generateRequirementFile($scholarship);
        $requirementImage = $this->generateRequirementImage($scholarship);
        $requirementInput1 = $this->generateRequirementInput($scholarship);
        $requirementInput2 = $this->generateRequirementInput($scholarship, null, 'test1', 'test2');
        $this->generateApplicationText($requirementText, null, 'test', $account);
        $this->generateApplicationFile($this->generateAccountFile($account), $requirementFile);
        $this->generateApplicationImage($this->generateAccountFile($account), $requirementImage);
        $this->generateApplicationInput($requirementInput1, $account, 'http://www.youtube.com/watch?v=123456');
        $this->generateApplicationInput($requirementInput2, $account, 'text');

        $resp = $this->post(route('rest::v1.application.store'), ['scholarshipId' => $scholarship->getScholarshipId()]);
        $this->seeJsonSuccessSubset($resp, [
            'scholarshipId'  => $scholarship->getScholarshipId(),
            'isFavorite'     => 0,
            'isSent'         => 0,
            'url'            => $scholarship->getPublicUrl(),
            'logo'           => $scholarship->getLogoUrl(),
            'title'          => 'test',
            'description'    => NULL,
            'externalUrl'    => 'test',
            'TOSUrl'         => NULL,
            'PPUrl'          => NULL,
            'amount'         => '10.00',
            'isRecurrent'    => false,
            'image'          => NULL,
            'requirements'   =>
                [
                    'texts'  =>
                        [
                            0 =>
                                [
                                    'id'               => 1,
                                    'scholarshipId'    => 1,
                                    'name'             => 'Essay',
                                    'type'             => 'text',
                                    'title'            => 'test',
                                    'description'      => 'test',
                                    'sendType'         => 'attachment',
                                    'attachmentType'   => 'doc',
                                    'attachmentFormat' => NULL,
                                    'allowFile'        => true,
                                    'fileExtension'    => NULL,
                                    'maxFileSize'      => NULL,
                                    'minWords'         => NULL,
                                    'maxWords'         => NULL,
                                    'minCharacters'    => NULL,
                                    'maxCharacters'    => NULL,
                                ],
                        ],
                    'files'  =>
                        [
                            0 =>
                                [
                                    'id'            => 1,
                                    'scholarshipId' => 1,
                                    'name'          => 'Video',
                                    'type'          => 'file',
                                    'title'         => 'test',
                                    'description'   => 'test',
                                    'fileExtension' => NULL,
                                    'maxFileSize'   => NULL,
                                ],
                        ],
                    'images' =>
                        [
                            0 =>
                                [
                                    'id'            => 1,
                                    'scholarshipId' => 1,
                                    'name'          => 'ProfilePic',
                                    'type'          => 'image',
                                    'title'         => 'test',
                                    'description'   => 'testse',
                                    'fileExtension' => NULL,
                                    'maxFileSize'   => NULL,
                                    'minWidth'      => NULL,
                                    'maxWidth'      => NULL,
                                    'minHeight'     => NULL,
                                    'maxHeight'     => NULL,
                                ],
                        ],
                    'inputs' =>
                        [
                            0 =>
                                [
                                    'id'            => 1,
                                    'scholarshipId' => 1,
                                    'name'          => 'Video link',
                                    'type'          => 'input',
                                    'title'         => 'test',
                                    'description'   => 'test',
                                ],
                            1 =>
                                [
                                    'id'            => 2,
                                    'scholarshipId' => 1,
                                    'name'          => 'Video link',
                                    'type'          => 'input',
                                    'title'         => 'test1',
                                    'description'   => 'test2',
                                ],
                        ],
                ],
            'application'    =>
                [
                    'status' => 4,
                    'texts'  =>
                        [
                            0 =>
                                [
                                    'id'                => 1,
                                    'accountId'         => 1,
                                    'requirementTextId' => 1,
                                    'accountFile'       => NULL,
                                    'text'              => 'test',
                                    'scholarshipId'     => 1,
                                ],
                        ],
                    'files'  =>
                        [
                            0 =>
                                [
                                    'id'                => 1,
                                    'accountId'         => 1,
                                    'accountFile'       =>
                                        [
                                            'id'        => 1,
                                            'path'      => '/account-files/1/other/test_account_file.txt',
                                            'filename'  => 'test_account_file.txt',
                                            'accountId' => 1,
                                            'category'  => 'Other',
                                            'publicUrl' => NULL,
                                        ],
                                    'requirementFileId' => 1,
                                    'scholarshipId'     => 1,
                                ],
                        ],
                    'images' =>
                        [
                            0 =>
                                [
                                    'id'                 => 1,
                                    'accountId'          => 1,
                                    'accountFile'        =>
                                        [
                                            'id'        => 2,
                                            'path'      => '/account-files/1/other/test_account_file.txt',
                                            'filename'  => 'test_account_file.txt',
                                            'accountId' => 1,
                                            'category'  => 'Other',
                                            'publicUrl' => NULL,
                                        ],
                                    'requirementImageId' => 1,
                                    'scholarshipId'      => 1,
                                ],
                        ],
                    'inputs' =>
                        [
                            0 =>
                                [
                                    'id'                 => 1,
                                    'accountId'          => 1,
                                    'requirementInputId' => 1,
                                    'text'               => 'http://www.youtube.com/watch?v=123456',
                                    'scholarshipId'      => 1,
                                ],
                            1 =>
                                [
                                    'id'                 => 2,
                                    'accountId'          => 1,
                                    'requirementInputId' => 2,
                                    'text'               => 'text',
                                    'scholarshipId'      => 1,
                                ],
                        ],
                ],
        ]);
    }

    protected function dateToArray($date){
        return json_decode(json_encode($date), true);
    }
}
