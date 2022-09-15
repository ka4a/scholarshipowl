<?php namespace Test\Services\ApplicationService;

use App\Entity\AccountFile;
use App\Entity\RequirementText;
use App\Entity\Scholarship;
use App\Services\ApplicationService\ApplicationSenderEmail;
use App\Testing\TestCase;
use Carbon\Carbon;
use Illuminate\Mail\Message;
use Symfony\Component\HttpFoundation\File\File;

use Mockery as m;

class ApplicationSenderEmailTest extends TestCase
{

    /**
     * @var ApplicationSenderEmail
     */
    protected $sender;

    public function setUp(): void
    {
        $this->createApplication();
        $this->sender = new ApplicationSenderEmail();
        parent::setUp();
    }

    public function testSendApplication()
    {
        $scholarship = $this->generateScholarship();
        $scholarship->setApplicationType(Scholarship::APPLICATION_TYPE_EMAIL);
        $scholarship->setSendToPrivate(true);
        $scholarship->setEmail('test@example.com');
        $account = $this->generateAccount();

        $submitData = [
            'to' => $scholarship->getEmail(),
            'from' => [$account->getInternalEmail(), $account->getProfile()->getFullName()],
            'subject' => $account->mapTags($scholarship->getEmailSubject()),
            'replyTo' => $scholarship->getSendToPrivate() ? $account->getEmail() : null,
            'body' => 'Testing email body',
            'attachments' => [['fileContent' => (new AccountFile(new File(__FILE__), $account))->getFileContent(),  'name' => 'test.php']],
        ];

        \Mail::shouldReceive('send')
            ->once()
            ->andReturnUsing(function($view, $data, $callback) use ($submitData) {
                $this->assertEquals(['html' => 'emails.user.application'], $view);
                $this->assertEquals(['content' => $submitData['body']], $data);

                $message = m::mock(Message::class);
                $message->shouldReceive('to')->once()->with($submitData['to']);
                $message->shouldReceive('subject')->once()->with($submitData['subject']);
                $message->shouldReceive('from')->once()->with($submitData['from'][0], $submitData['from'][1]);
                $message->shouldReceive('replyTo')->once()->with($submitData['replyTo']);
                $message->shouldReceive('attachData')->withAnyArgs();

                call_user_func($callback, $message);
            });

        \Mail::shouldReceive('failures')->andReturn([]);
        $this->sender->sendApplication($scholarship, $submitData, $this->generateApplication($scholarship, $account));
    }

    public function testSendApplicationWrongType()
    {
        $scholarship = $this->generateScholarship();
        $scholarship->setApplicationType(Scholarship::APPLICATION_TYPE_ONLINE);
        $this->expectException(\InvalidArgumentException::class, 'Can send only email applications!');
        $this->sender->sendApplication($scholarship, [], $this->generateApplication($scholarship));
    }

    public function testSubmitDataAllRequirements()
    {
        $account = $this->generateAccount();
        $age = Carbon::createFromDate(2000, 9, 6)->age;
        $this->fillProfileData($account->getProfile());
        $scholarship = $this->generateScholarship();
        $scholarship->setEmailSubject('Test [[first_name]] subject');
        $scholarship->setSendToPrivate(true);
        $requirementTextBody = $this->generateRequirementText($scholarship);
        $requirementTextBody->setSendType(RequirementText::SEND_TYPE_BODY);
        $requirementTextGenerated = $this->generateRequirementText($scholarship);
        $requirementTextGenerated->setAttachmentFormat('[[first_name]]_[[age]].doc');
        $requirementText = $this->generateRequirementText($scholarship);
        $requirementFile = $this->generateRequirementFile($scholarship);
        $requirementImage = $this->generateRequirementImage($scholarship);
        $this->generateApplicationText($requirementTextBody, null, 'test body', $account);
        $this->generateApplicationText($requirementTextGenerated, null, 'test text', $account);
        $this->generateApplicationText($requirementText, $textFile = $this->generateAccountFile($account));
        $this->generateApplicationFile($file = $this->generateAccountFile($account), $requirementFile);
        $this->generateApplicationImage($image = $this->generateAccountFile($account), $requirementImage);
        \EntityManager::flush();

        $scholarship->setEmailMessage("Test [[last_name]] body\n[[text-" . $requirementTextBody->getId() . ']]');
        \EntityManager::flush($scholarship);

        $scholarship = $this->sender->prepareScholarship($scholarship, $account);
        $submitData = $this->sender->prepareSubmitData($scholarship, $account);

        $this->assertArrayHasKey('to', $submitData);
        $this->assertArrayHasKey('from', $submitData);
        $this->assertArrayHasKey('subject', $submitData);
        $this->assertArrayHasKey('replyTo', $submitData);
        $this->assertArrayHasKey('body', $submitData);
        $this->assertArrayHasKey('attachments', $submitData);

        $this->assertEquals($scholarship->getEmail(), $submitData['to']);
        $this->assertCount(2, $submitData['from']);
        $this->assertEquals($account->getInternalEmail(), $submitData['from'][0]);
        $this->assertEquals($account->getProfile()->getFullName(), $submitData['from'][1]);
        $this->assertEquals('Test Testfirstname subject', $submitData['subject']);
        $this->assertEquals($account->getEmail(), $submitData['replyTo']);

        /** @var \Illuminate\View\View $body */
        $body = $submitData['body'];
        $this->assertTrue($body->getData()['body'] === nl2br("Test Testlastname body\ntest body"));

        $this->assertCount(4, $submitData['attachments']);
        $this->assertArrayContains([0 => ['name' => 'Testfirstname_'.$age.'.doc']], $submitData['attachments']);
        $this->assertArrayContains([1 => [
            'fileContent' => $textFile->getFileContent(),
            'name' => 'Testfirstname_Testlastname__test.txt',
        ]], $submitData['attachments']);
        $this->assertArrayContains([2 => [
            'fileContent' => $file->getFileContent(),
            'name' => 'test_account_file.txt',
        ]], $submitData['attachments']);
        $this->assertArrayContains([3 => [
            'fileContent' => $image->getFileContent(),
            'name' => 'test_account_file.txt',
        ]], $submitData['attachments']);
    }
}
