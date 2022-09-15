<?php namespace Test\Services\ApplicationService;

use App\Entity\AccountFile;
use App\Entity\CareerGoal;
use App\Entity\Citizenship;
use App\Entity\Country;
use App\Entity\Degree;
use App\Entity\DegreeType;
use App\Entity\Ethnicity;
use App\Entity\Form;
use App\Entity\Scholarship;
use App\Entity\SchoolLevel;
use App\Entity\State;
use App\Services\ApplicationService\ApplicationSenderOnline;
use App\Testing\TestCase;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Mockery as m;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\File\File;

class ApplicationSenderOnlineTest extends TestCase
{
    /**
     * @var ApplicationSenderOnline
     */
    protected $sender;

    public function setUp(): void
    {
        $this->sender = new ApplicationSenderOnline();

        parent::setUp();
    }

    /*public function testSendApplicationPost()
    {
        $scholarship = $this->generateScholarship();
        $scholarship->setApplicationType(Scholarship::APPLICATION_TYPE_ONLINE);
        $scholarship->setFormMethod(Scholarship::FORM_METHOD_POST);

        $data = [
            'test_text_field' => 'text',
            'test_int_field' => 666,
            'test_bool_field' => false,
            ApplicationSenderOnline::REQUEST_FILES => [
                [
                    'name' => 'test_file_field',
                    'contents' => new File(__FILE__),
                    'filename' => 'test.doc',
                ]
            ],
        ];

        $response = m::mock(ResponseInterface::class)
            ->shouldReceive('getStatusCode')->once()->andReturn(200)
            ->shouldReceive('getBody')->once()->andReturn('ok')
            ->getMock();

        $client = m::mock(Client::class)->shouldReceive('post')
            ->once()
            ->with(
                $scholarship->getFormAction(),
                m::on(function($options) use ($scholarship) {
                    $this->assertArrayHasKey('verify', $options);
                    $this->assertArrayHasKey('headers', $options);
                    $this->assertArrayHasKey('multipart', $options);
                    $this->assertEquals(false, $options['verify']);
                    $this->assertArrayContains([0 => [
                        'name' => 'test_file_field',
                        'filename' => 'test.doc',
                    ]], $options['multipart']);
                    $this->assertTrue($options['multipart'][0]['contents'] instanceof File);
                    $this->assertArrayContains([1 => [
                        'name' => 'test_text_field',
                        'contents' => 'text',
                    ]], $options['multipart']);
                    $this->assertArrayContains([2 => [
                        'name' => 'test_int_field',
                        'contents' => 666,
                    ]], $options['multipart']);
                    $this->assertArrayContains([3 => [
                        'name' => 'test_bool_field',
                        'contents' => false,
                    ]], $options['multipart']);

                    return true;
                })
            )
            ->andReturn($response)
            ->getMock();

        $this->sender->setHttpClient($client)->sendApplication(
            $scholarship, $data, $this->generateApplication($scholarship)
        );
    }

    public function testSendApplicationGet()
    {
        $scholarship = $this->generateScholarship();
        $scholarship->setApplicationType(Scholarship::APPLICATION_TYPE_ONLINE);
        $scholarship->setFormMethod(Scholarship::FORM_METHOD_GET);

        $data = [
            'test_text_field' => 'text',
            'test_int_field' => 666,
            'test_bool_field' => false,
            ApplicationSenderOnline::REQUEST_FILES => [
                [
                    'name' => 'test_file_field',
                    'contents' => new File(__FILE__),
                    'filename' => 'test.doc',
                ]
            ],
        ];

        $response = m::mock(ResponseInterface::class)
            ->shouldReceive('getStatusCode')->once()->andReturn(200)
            ->shouldReceive('getBody')->once()->andReturn('ok')
            ->getMock();

        $client = m::mock(Client::class)
            ->shouldReceive('get')->once()
            ->with(
                $scholarship->getFormAction(),
                m::on(function($options) use ($scholarship) {
                    $this->assertArrayHasKey('verify', $options);
                    $this->assertArrayHasKey('headers', $options);
                    $this->assertArrayHasKey('query', $options);
                    $this->assertEquals(false, $options['verify']);
                    $this->assertEquals([
                        'test_text_field' => 'text',
                        'test_int_field' => 666,
                        'test_bool_field' => false,
                    ], $options['query']);

                    return true;
                })
            )
            ->andReturn($response)
            ->getMock();

        $this->sender->setHttpClient($client)->sendApplication(
            $scholarship, $data, $this->generateApplication($scholarship)
        );
    }

    public function testSendApplicationFailure()
    {
        $scholarship = $this->generateScholarship();
        $scholarship->setApplicationType(Scholarship::APPLICATION_TYPE_ONLINE);
        $scholarship->setFormMethod(Scholarship::FORM_METHOD_POST);

        $response = m::mock(ResponseInterface::class)
            ->shouldReceive('getStatusCode')->twice()->andReturn(500)
            ->shouldReceive('getReasonPhrase')->once()->andReturn('FAILED')
            ->shouldReceive('getBody')->once()->andReturn('failed')
            ->getMock();

        $client = m::mock(Client::class)
            ->shouldReceive('post')->once()->andReturn($response)
            ->getMock();

        $this->expectException(
            \RuntimeException::class,
            "Failed request to send application.\nStatus: 500\nError: FAILED\nMessage: failed\n"
        );
        $this->sender->setHttpClient($client)->sendApplication(
            $scholarship, [], $this->generateApplication($scholarship)
        );
    }

    public function testSendApplicationNotOnline()
    {
        $this->expectException(\InvalidArgumentException::class, 'Can send only online applications!');
        $scholarship = $this->generateScholarship();
        $scholarship->setApplicationType(Scholarship::APPLICATION_TYPE_NONE);
        $this->sender->sendApplication($scholarship, [], $this->generateApplication());
    }

    public function testSubmitDataFormValuesMapping()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $this->fillProfileData($account->getProfile());

        $this->generateForm($scholarship, 'citizenship', Form::CITIZENSHIP, null, ['testCitizenship' => [1,2,3]]);
        $this->generateForm($scholarship, 'ethnicity', Form::ETHNICITY, null, '[]');
        $this->generateForm($scholarship, 'school_level', Form::SCHOOL_LEVEL, null, ['testSchoolLevel' => [2,3]]);
        $this->generateForm($scholarship, 'degree', Form::DEGREE, null, ['testDegree' => [1]]);
        $this->generateForm($scholarship, 'degree_type', Form::DEGREE_TYPE, null, ['testDegree' => 'test']);
        $this->generateForm($scholarship, 'gpa_range', Form::GPA_RANGE, null, ['testGpaRange' => ['test_gpa_value']]);
        $this->generateForm($scholarship, 'career_goal', Form::CAREER_GOAL, null, ['testCareerGoal' => [1]]);
        $this->generateForm($scholarship, 'study_online', Form::STUDY_ONLINE, null, ['testStudyOnline' => ['Yes']]);
        $this->generateForm($scholarship, 'highschool', Form::HIGHSCHOOL, null, ['testHighschool' => ['Highschool value']]);
        $this->generateForm($scholarship, 'university', Form::UNIVERSITY, null, ['testUniversity' => ['University value']]);

        $submitData = $this->sender->prepareSubmitData($scholarship, $account);

        $this->assertArrayHasKey('citizenship', $submitData);
        $this->assertEquals('testCitizenship', $submitData['citizenship']);
        $this->assertArrayHasKey('ethnicity', $submitData);
        $this->assertEquals('1', $submitData['ethnicity']);
        $this->assertArrayHasKey('school_level', $submitData);
        $this->assertEquals('', $submitData['school_level']);
        $this->assertArrayHasKey('degree', $submitData);
        $this->assertEquals('testDegree', $submitData['degree']);
        $this->assertArrayHasKey('degree_type', $submitData);
        $this->assertEquals('', $submitData['degree_type']);
        $this->assertArrayHasKey('gpa_range', $submitData);
        $this->assertEquals('testGpaRange', $submitData['gpa_range']);
        $this->assertArrayHasKey('study_online', $submitData);
        $this->assertEquals('testStudyOnline', $submitData['study_online']);
        $this->assertArrayHasKey('highschool', $submitData);
        $this->assertEquals('testHighschool', $submitData['highschool']);
        $this->assertArrayHasKey('university', $submitData);
        $this->assertEquals('testUniversity', $submitData['university']);
    }

    public function testSubmitDataFormMapping()
    {
        $dateOfBirth = new \DateTime('2000-09-02 19:52:58');
        $account = $this->generateAccount('test@test.com');
        $scholarship = $this->generateScholarship();
        $this->fillProfileData($account->getProfile());
        $account->getProfile()->setDateOfBirth($dateOfBirth);
        $age = Carbon::createFromDate(2000, 9, 2)->age;
        \EntityManager::flush();

        $this->generateForm($scholarship, 'email', Form::EMAIL);
        $this->generateForm($scholarship, 'email_conformation', Form::EMAIL_CONFIRMATION);
        $this->generateForm($scholarship, 'first_name', Form::FIRST_NAME);
        $this->generateForm($scholarship, 'last_name', Form::LAST_NAME);
        $this->generateForm($scholarship, 'full_name', Form::FULL_NAME);

        $this->generateForm($scholarship, 'phone', Form::PHONE);
        $this->generateForm($scholarship, 'phone_area', Form::PHONE_AREA);
        $this->generateForm($scholarship, 'phone_prefix', Form::PHONE_PREFIX);
        $this->generateForm($scholarship, 'phone_local', Form::PHONE_LOCAL);

        $this->generateForm($scholarship, 'date_of_birth', Form::DATE_OF_BIRTH);
        $this->generateForm($scholarship, 'date_of_birth_day', Form::DATE_OF_BIRTH_DAY);
        $this->generateForm($scholarship, 'date_of_birth_month', Form::DATE_OF_BIRTH_MONTH);
        $this->generateForm($scholarship, 'date_of_birth_year', Form::DATE_OF_BIRTH_YEAR);

        $this->generateForm($scholarship, 'age', Form::AGE);
        $this->generateForm($scholarship, 'gender', Form::GENDER);
        $this->generateForm($scholarship, 'citizenship', Form::CITIZENSHIP);
        $this->generateForm($scholarship, 'citizenship_name', Form::CITIZENSHIP_NAME);
        $this->generateForm($scholarship, 'ethnicity', Form::ETHNICITY);
        $this->generateForm($scholarship, 'ethnicity_name', Form::ETHNICITY_NAME);
        $this->generateForm($scholarship, 'country', Form::COUNTRY);
        $this->generateForm($scholarship, 'country_abbreviation', Form::COUNTRY_ABBREVIATION);
        $this->generateForm($scholarship, 'state', Form::STATE);
        $this->generateForm($scholarship, 'state_abbreviation', Form::STATE_ABBREVIATION);
        $this->generateForm($scholarship, 'city', Form::CITY);
        $this->generateForm($scholarship, 'address', Form::ADDRESS);
        $this->generateForm($scholarship, 'zip', Form::ZIP);

        $this->generateForm($scholarship, 'school_level', Form::SCHOOL_LEVEL);
        $this->generateForm($scholarship, 'school_level_name', Form::SCHOOL_LEVEL_NAME);
        $this->generateForm($scholarship, 'degree', Form::DEGREE);
        $this->generateForm($scholarship, 'degree_name', Form::DEGREE_NAME);
        $this->generateForm($scholarship, 'degree_type', Form::DEGREE_TYPE);
        $this->generateForm($scholarship, 'degree_type_name', Form::DEGREE_TYPE_NAME);
        $this->generateForm($scholarship, 'enrollment_year', Form::ENROLLMENT_YEAR);
        $this->generateForm($scholarship, 'enrollment_month', Form::ENROLLMENT_MONTH);
        $this->generateForm($scholarship, 'graduation_year', Form::GRADUATION_YEAR);
        $this->generateForm($scholarship, 'graduation_month', Form::GRADUATION_MONTH);
        $this->generateForm($scholarship, 'gpa', Form::GPA);
        $this->generateForm($scholarship, 'gpa_range', Form::GPA_RANGE);
        $this->generateForm($scholarship, 'career_goal', Form::CAREER_GOAL);
        $this->generateForm($scholarship, 'career_goal_name', Form::CAREER_GOAL_NAME);
        $this->generateForm($scholarship, 'study_online', Form::STUDY_ONLINE);
        $this->generateForm($scholarship, 'highschool', Form::HIGHSCHOOL);
        $this->generateForm($scholarship, 'university', Form::UNIVERSITY);
        $this->generateForm($scholarship, 'accept_confirmation', Form::ACCEPT_CONFIRMATION, 'AC');
        $this->generateForm($scholarship, 'hidden_field', Form::HIDDEN_FIELD, 'HF');
        $this->generateForm($scholarship, 'static_field', Form::STATIC_FIELD, 'SF');
        $this->generateForm($scholarship, 'submit_field', Form::SUBMIT_FIELD, 'SUBF');

        $submitData = $this->sender->prepareSubmitData($scholarship, $account);

        $this->assertArrayHasKey('email', $submitData);
        $this->assertEquals('test@application-inbox.com', $submitData['email']);
        $this->assertArrayHasKey('email_conformation', $submitData);
        $this->assertEquals('test@application-inbox.com', $submitData['email_conformation']);
        $this->assertArrayHasKey('first_name', $submitData);
        $this->assertEquals('testFirstName', $submitData['first_name']);
        $this->assertArrayHasKey('last_name', $submitData);
        $this->assertEquals('testLastName', $submitData['last_name']);
        $this->assertArrayHasKey('full_name', $submitData);
        $this->assertEquals('testFirstName testLastName', $submitData['full_name']);

        $this->assertArrayHasKey('phone', $submitData);
        $this->assertEquals('1234567890', $submitData['phone']);
        $this->assertArrayHasKey('phone_area', $submitData);
        $this->assertEquals('123', $submitData['phone_area']);
        $this->assertArrayHasKey('phone_prefix', $submitData);
        $this->assertEquals('456', $submitData['phone_prefix']);
        $this->assertArrayHasKey('phone_local', $submitData);
        $this->assertEquals('7890', $submitData['phone_local']);

        $this->assertArrayHasKey('date_of_birth', $submitData);
        $this->assertEquals('09/02/2000', $submitData['date_of_birth']);
        $this->assertArrayHasKey('date_of_birth_day', $submitData);
        $this->assertEquals('2', $submitData['date_of_birth_day']);
        $this->assertArrayHasKey('date_of_birth_month', $submitData);
        $this->assertEquals('9', $submitData['date_of_birth_month']);
        $this->assertArrayHasKey('date_of_birth_year', $submitData);
        $this->assertEquals('2000', $submitData['date_of_birth_year']);

        $this->assertArrayHasKey('age', $submitData);
        $this->assertEquals("$age", $submitData['age']);
        $this->assertArrayHasKey('gender', $submitData);
        $this->assertEquals('male', $submitData['gender']);
        $this->assertArrayHasKey('citizenship', $submitData);
        $this->assertEquals('1', $submitData['citizenship']);
        $this->assertArrayHasKey('citizenship_name', $submitData);
        $this->assertEquals(Citizenship::find(1), $submitData['citizenship_name']);
        $this->assertArrayHasKey('ethnicity', $submitData);
        $this->assertEquals('1', $submitData['ethnicity']);
        $this->assertArrayHasKey('ethnicity_name', $submitData);
        $this->assertEquals(Ethnicity::find(1), $submitData['ethnicity_name']);
        $this->assertArrayHasKey('country', $submitData);
        $this->assertEquals(Country::find(Country::USA), $submitData['country']);
        $this->assertArrayHasKey('country_abbreviation', $submitData);
        $this->assertEquals('US', $submitData['country_abbreviation']);
        $this->assertArrayHasKey('state', $submitData);
        $this->assertEquals(State::find(1), $submitData['state']);
        $this->assertArrayHasKey('state_abbreviation', $submitData);
        $this->assertEquals('AL', $submitData['state_abbreviation']);
        $this->assertArrayHasKey('city', $submitData);
        $this->assertEquals('New York', $submitData['city']);
        $this->assertArrayHasKey('address', $submitData);
        $this->assertEquals('Street Name 1 apt. 1', $submitData['address']);
        $this->assertArrayHasKey('zip', $submitData);
        $this->assertEquals('12345', $submitData['zip']);

        $this->assertArrayHasKey('school_level', $submitData);
        $this->assertEquals('1', $submitData['school_level']);
        $this->assertArrayHasKey('school_level_name', $submitData);
        $this->assertEquals(SchoolLevel::find(1), $submitData['school_level_name']);
        $this->assertArrayHasKey('degree', $submitData);
        $this->assertEquals('1', $submitData['degree']);
        $this->assertArrayHasKey('degree_name', $submitData);
        $this->assertEquals(Degree::find(1), $submitData['degree_name']);
        $this->assertArrayHasKey('degree_type', $submitData);
        $this->assertEquals('1', $submitData['degree_type']);
        $this->assertArrayHasKey('degree_type_name', $submitData);
        $this->assertEquals(DegreeType::find(1), $submitData['degree_type_name']);
        $this->assertArrayHasKey('enrollment_year', $submitData);
        $this->assertEquals('2015', $submitData['enrollment_year']);
        $this->assertArrayHasKey('enrollment_month', $submitData);
        $this->assertEquals('9', $submitData['enrollment_month']);
        $this->assertArrayHasKey('graduation_year', $submitData);
        $this->assertEquals('2014', $submitData['graduation_year']);
        $this->assertArrayHasKey('graduation_month', $submitData);
        $this->assertEquals('8', $submitData['graduation_month']);
        $this->assertArrayHasKey('gpa', $submitData);
        $this->assertEquals('test_gpa_value', $submitData['gpa']);
        $this->assertArrayHasKey('gpa_range', $submitData);
        $this->assertEquals('test_gpa_value', $submitData['gpa_range']);
        $this->assertArrayHasKey('career_goal', $submitData);
        $this->assertEquals('1', $submitData['career_goal']);
        $this->assertArrayHasKey('career_goal_name', $submitData);
        $this->assertEquals(CareerGoal::find(1), $submitData['career_goal_name']);
        $this->assertArrayHasKey('study_online', $submitData);
        $this->assertEquals('Yes', $submitData['study_online']);
        $this->assertArrayHasKey('highschool', $submitData);
        $this->assertEquals('Highschool value', $submitData['highschool']);
        $this->assertArrayHasKey('university', $submitData);
        $this->assertEquals('University value', $submitData['university']);
        $this->assertArrayHasKey('accept_confirmation', $submitData);
        $this->assertEquals('AC', $submitData['accept_confirmation']);
        $this->assertArrayHasKey('hidden_field', $submitData);
        $this->assertEquals('HF', $submitData['hidden_field']);
        $this->assertArrayHasKey('static_field', $submitData);
        $this->assertEquals('SF', $submitData['static_field']);
        $this->assertArrayHasKey('submit_field', $submitData);
        $this->assertEquals('SUBF', $submitData['submit_field']);
    }*/

    public function testSubmitDataWithApplicationRequirements()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $requirementText = $this->generateRequirementText($scholarship);
        $requirementTextGenerated = $this->generateRequirementText($scholarship);
        $requirementTextFile = $this->generateRequirementText($scholarship, true);
        $requirementImage = $this->generateRequirementImage($scholarship);
        $requirementFile = $this->generateRequirementFile($scholarship);
        $requirementInput = $this->generateRequirementInput($scholarship);

        $accountFile = $this->generateAccountFile($account, 'test_text_file.doc');

        $this->generateApplicationText($requirementText, null, 'test_text_requirement', $account);
        $this->generateApplicationText($requirementTextFile, $accountFile);
        $this->generateApplicationText($requirementTextGenerated, null, 'test_text_generated', $account);
        $this->generateApplicationFile($this->generateAccountFile($account, 'filez.pdf'), $requirementFile);;
        $this->generateApplicationImage($this->generateAccountFile($account, 'image.jpg'), $requirementImage);
        $this->generateApplicationInput($requirementInput, $account, 'http://www.youtube.com/watch?v=123456');

        $this->generateForm($scholarship, 'test_text', Form::TEXT, $requirementText->getId());
        $this->generateForm($scholarship, 'test_text_file', Form::REQUIREMENT_UPLOAD_TEXT, $requirementTextFile->getId());
        $this->generateForm($scholarship, 'test_text_generated_file', Form::REQUIREMENT_UPLOAD_TEXT, $requirementTextGenerated->getId());
        $this->generateForm($scholarship, 'test_file', Form::REQUIREMENT_UPLOAD_FILE, $requirementFile->getId());
        $this->generateForm($scholarship, 'test_image', Form::REQUIREMENT_UPLOAD_IMAGE, $requirementImage->getId());
        $this->generateForm($scholarship, 'test_input', Form::INPUT, $requirementImage->getId());

        $scholarship = $this->sender->prepareScholarship($scholarship, $account);
        $submitData = $this->sender->prepareSubmitData($scholarship, $account);
        $this->assertArrayHasKey('test_text', $submitData);
        $this->assertEquals('test_text_requirement', $submitData['test_text']);

        $this->assertArrayHasKey('test_input', $submitData);
        $this->assertEquals('http://www.youtube.com/watch?v=123456', $submitData['test_input']);

        $this->assertArrayHasKey(ApplicationSenderOnline::REQUEST_FILES, $submitData);
        $this->assertCount(4, $submitData[ApplicationSenderOnline::REQUEST_FILES]);

        $this->assertArrayHasKey('name', $submitData[ApplicationSenderOnline::REQUEST_FILES][0]);
        $this->assertArrayHasKey('contents', $submitData[ApplicationSenderOnline::REQUEST_FILES][0]);
        $this->assertArrayHasKey('filename', $submitData[ApplicationSenderOnline::REQUEST_FILES][0]);
        $this->assertEquals($accountFile->getFileContent(), $submitData[ApplicationSenderOnline::REQUEST_FILES][0]['contents']);
        $this->assertEquals('Testfirstname_Testlastname__test.doc', $submitData[ApplicationSenderOnline::REQUEST_FILES][0]['filename']);

        $this->assertArrayHasKey('name', $submitData[ApplicationSenderOnline::REQUEST_FILES][1]);
        $this->assertArrayHasKey('contents', $submitData[ApplicationSenderOnline::REQUEST_FILES][1]);
        $this->assertArrayHasKey('filename', $submitData[ApplicationSenderOnline::REQUEST_FILES][1]);
        $this->assertEquals('Testfirstname_Testlastname__test.doc', $submitData[ApplicationSenderOnline::REQUEST_FILES][1]['filename']);

        $this->assertArrayHasKey('name', $submitData[ApplicationSenderOnline::REQUEST_FILES][2]);
        $this->assertArrayHasKey('contents', $submitData[ApplicationSenderOnline::REQUEST_FILES][2]);
        $this->assertTrue(is_string($submitData[ApplicationSenderOnline::REQUEST_FILES][2]['contents']));

        $this->assertArrayHasKey('name', $submitData[ApplicationSenderOnline::REQUEST_FILES][3]);
        $this->assertArrayHasKey('contents', $submitData[ApplicationSenderOnline::REQUEST_FILES][3]);
        $this->assertTrue(is_string($submitData[ApplicationSenderOnline::REQUEST_FILES][3]['contents']));
    }

}
