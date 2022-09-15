<?php namespace Test\Http\Controller\Index;

use App\Entity\FeatureSet;
use App\Entity\Field;
use App\Entity\PaymentMethod;
use App\Entity\ScholarshipStatus;
use App\Http\Controllers\Index\StripeController;
use App\Services\PaymentManager;
use App\Services\StripeService;
use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;
use App\Traits\SunriseSync;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Token;


class PubSubSunriseScholarshipEndpointControllerTest extends TestCase
{
    use WithoutMiddleware;
    use EntityGenerator;

    use SunriseSync;

    public function setUp(): void
    {
        parent::setUp();
        static::$truncate[] = 'scholarship';
        static::$truncate[] = 'eligibility';
        static::$truncate[] = 'requirement_text';
        static::$truncate[] = 'requirement_input';
        static::$truncate[] = 'requirement_file';
        static::$truncate[] = 'requirement_image';
        static::$truncate[] = 'requirement_survey';
        static::$truncate[] = 'requirement_special_eligibility';
    }

    public function testManageScholarships()
    {
        $secretKey = config('pubsub.sunrise.push_endpoint_secret');

        $message = $this->generateMessage();
        $body = $message->getMessage();

        //test with invalid secret
        $resp = $this->postJson(route('pubsub.sunrise.manageScholarships', 'xxx'), $body);
        $this->assertTrue($resp->status() === 403);

        //test without message
        $resp = $this->postJson(route('pubsub.sunrise.manageScholarships', $secretKey), []);
        $this->assertTrue($resp->status() === 400);

        // ok
        $resp = $this->postJson(route('pubsub.sunrise.manageScholarships', $secretKey), $body);
        $this->assertTrue($resp->status() === 204);
        $this->assertDatabaseHas('scholarship', [
            'external_scholarship_id' => $message->getData('id'),
            'external_scholarship_template_id' => $message->getData('template'),
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'required',
            'value' => '""',
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'value' => '"+123456789"',
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'not',
            'value' => '"+987654321"',
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'in',
            'value' => '"t@t.com,t2@t.com"',
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'nin',
            'value' => '"t3@t.com,t4@t.com"',
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'greater_than',
            'value' => 2,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'greater_than_or_equal',
            'value' => 3,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'less_than',
            'value' => 7,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'less_than_or_equal',
            'value' => 6,
        ]);

        // test update
        $message->setData('title', 'Updated title');
        $message->setAttribute('timestamp', $message->getAttribute('timestamp') + 1);
        $body = $message->getMessage();

        $resp = $this->postJson(route('pubsub.sunrise.manageScholarships', $secretKey), $body);
        $this->assertTrue($resp->status() === 204);
        $this->assertDatabaseHas('scholarship', [
            'external_scholarship_id' => $message->getData('id'),
            'external_scholarship_template_id' => $message->getData('template'),
            'title' => $message->getData('title'),
        ]);

        // test idempotency (discarding message with stale time mark - unordered push)
        $message->setData('title', 'Updated title2');
        $body = $message->getMessage();

        $resp = $this->postJson(route('pubsub.sunrise.manageScholarships', $secretKey), $body);
        $this->assertTrue($resp->status() === 204);
        $this->assertDatabaseMissing('scholarship', [
            'external_scholarship_id' => $message->getData('id'),
            'external_scholarship_template_id' => $message->getData('template'),
            'title' => $message->getData('title'),
        ]);

        // test deadline (expire)
        $message->setAttribute('event', 'scholarship.deadline');
        $message->setData('expiredAt', $message->getData('deadline'));
        $message->setAttribute('timestamp', $message->getAttribute('timestamp') + 1);
        $body = $message->getMessage();

        $resp = $this->postJson(route('pubsub.sunrise.manageScholarships', $secretKey), $body);
        $this->assertTrue($resp->status() === 204);
        $this->assertDatabaseHas('scholarship', [
            'external_scholarship_id' => $message->getData('id'),
            'external_scholarship_template_id' => $message->getData('template'),
            'status' => ScholarshipStatus::EXPIRED,
        ]);
    }

    public function testEligibilityFieldMap()
    {
        $secretKey = config('pubsub.sunrise.push_endpoint_secret');

        $message = $this->generateMessage();
        $message->setData('fields', [
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 'test',
                'field' => [
                    'id' => 'name',
                    'name' => 'Name',
                    'type' => 'text',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 'test',
                'field' => [
                    'id' => 'email',
                    'name' => 'Email',
                    'type' => 'email',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 'test',
                'field' => [
                    'id' => 'phone',
                    'name' => 'Phone',
                    'type' => 'phone',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 'test',
                'field' => [
                    'id' => 'state',
                    'name' => 'State',
                    'type' => 'option',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 16,
                'field' => [
                    'id' => 'dateOfBirth',
                    'name' => 'DateOfBirth',
                    'type' => 'text',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 'test',
                'field' => [
                    'id' => 'city',
                    'name' => 'City',
                    'type' => 'text',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 'test',
                'field' => [
                    'id' => 'address',
                    'name' => 'Address',
                    'type' => 'text',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 'test',
                'field' => [
                    'id' => 'zip',
                    'name' => 'Zip',
                    'type' => 'text',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 'test',
                'field' => [
                    'id' => 'schoolLevel',
                    'name' => 'SchoolLevel',
                    'type' => 'option',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 'test',
                'optional' => true,
                'field' => [
                    'id' => 'fieldOfStudy',
                    'name' => 'FieldOfStudy',
                    'type' => 'option',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 5,
                'field' => [
                    'id' => 'degreeType',
                    'name' => 'DegreeType',
                    'type' => 'option',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => '11',
                'field' => [
                    'id' => 'GPA',
                    'name' => 'GPA',
                    'type' => 'option',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 2,
                'field' => [
                    'id' => 'gender',
                    'name' => 'Gender',
                    'type' => 'option',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'in',
                'eligibilityValue' => '1,2',
                'field' => [
                    'id' => 'gender',
                    'name' => 'Gender',
                    'type' => 'option',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => null,
                'eligibilityValue' => null,
                'field' => [
                    'id' => 'gender',
                    'name' => 'Gender',
                    'type' => 'option',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 2,
                'field' => [
                    'id' => 'ethnicity',
                    'name' => 'Ethnicity',
                    'type' => 'option',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'in',
                'eligibilityValue' => '1,2',
                'field' => [
                    'id' => 'ethnicity',
                    'name' => 'Ethnicity',
                    'type' => 'option',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => null,
                'eligibilityValue' => null,
                'field' => [
                    'id' => 'enrollmentDate',
                    'name' => 'EnrollmentDate',
                    'type' => 'date',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'gte',
                'eligibilityValue' => '25-12-2019',
                'field' => [
                    'id' => 'enrollmentDate',
                    'name' => 'EnrollmentDate',
                    'type' => 'date',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 3,
                'optional' => true,
                'field' => [
                    'id' => 'careerGoal',
                    'name' => 'CareerGoal',
                    'type' => 'option',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'eq',
                'eligibilityValue' => 'My lovely school',
                'field' => [
                    'id' => 'highSchoolName',
                    'name' => 'HighSchoolName',
                    'type' => 'text',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'lt',
                'eligibilityValue' => '01-05-2018',
                'field' => [
                    'id' => 'highSchoolGraduationDate',
                    'name' => 'HighSchoolGraduationDate',
                    'type' => 'date',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => 'nin',
                'eligibilityValue' => ['My lovely collage', 'My best collage'],
                'field' => [
                    'id' => 'collegeName',
                    'name' => 'CollegeName',
                    'type' => 'text',
                    'options' => []
                ]
            ],
            [
                'eligibilityType' => null,
                'eligibilityValue' => null,
                'field' => [
                    'id' => 'collegeGraduationDate',
                    'name' => 'CollegeGraduationDate',
                    'type' => 'date',
                    'options' => []
                ]
            ],
        ]);

        $body = $message->getMessage();
        $resp = $this->postJson(route('pubsub.sunrise.manageScholarships', $secretKey), $body);

        $this->assertTrue($resp->status() === 204);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::FULL_NAME,
            'value' => '"test"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::EMAIL,
            'value' => '"test"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::PHONE,
            'value' => '"test"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::AGE,
            'value' => 16,
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::CITY,
            'value' => '"test"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::ADDRESS,
            'value' => '"test"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::ZIP,
            'value' => '"test"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::SCHOOL_LEVEL,
            'value' => '"test"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::DEGREE,
            'value' => '"test"',
            'is_optional' => true,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::DEGREE_TYPE,
            'value' => 5,
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::GPA,
            'value' => '"2.9"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::GENDER,
            'value' => '"male"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'in',
            'field_id' => Field::GENDER,
            'value' => '"female,male"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'required',
            'field_id' => Field::GENDER,
            'value' => '""',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::ETHNICITY,
            'value' => 2,
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'in',
            'field_id' => Field::ETHNICITY,
            'value' => '"1,2"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'in',
            'field_id' => Field::ETHNICITY,
            'value' => '"1,2"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'greater_than_or_equal',
            'field_id' => Field::ENROLLMENT_YEAR,
            'value' => '"2019"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'greater_than_or_equal',
            'field_id' => Field::ENROLLMENT_MONTH,
            'value' => '"12"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'required',
            'field_id' => Field::ENROLLMENT_YEAR,
            'value' => '""',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'required',
            'field_id' => Field::ENROLLMENT_MONTH,
            'value' => '""',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::CAREER_GOAL,
            'value' => 3,
            'is_optional' =>  true,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'value',
            'field_id' => Field::HIGH_SCHOOL_NAME,
            'value' => '"My lovely school"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'less_than',
            'field_id' => Field::HIGH_SCHOOL_GRADUATION_YEAR,
            'value' => '"2018"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'less_than',
            'field_id' => Field::HIGH_SCHOOL_GRADUATION_MONTH,
            'value' => '"05"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'nin',
            'field_id' => Field::COLLEGE_NAME,
            'value' => '"My lovely collage,My best collage"',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'required',
            'field_id' => Field::COLLEGE_GRADUATION_YEAR,
            'value' => '""',
            'is_optional' => false,
        ]);
        $this->assertDatabaseHas('eligibility', [
            'type' => 'required',
            'field_id' => Field::COLLEGE_GRADUATION_MONTH,
            'value' => '""',
            'is_optional' => false,
        ]);
    }

    public function testRequirements() {
        $secretKey = config('pubsub.sunrise.push_endpoint_secret');
        $message = $this->generateMessage();

        $body = $message->getMessage();
        $resp = $this->postJson(route('pubsub.sunrise.manageScholarships', $secretKey), $body);

        $this->assertTrue($resp->status() === 204);
        $requirements = $message->getData('requirements');
        $this->assertDatabaseHas('requirement_text', [
            'external_id' => 1,
            'scholarship_id' => 1,
            'requirement_name_id' => $this->requirementNameMap()['essay'],
            'title' => 'Test Essay',
            'description' => 'Description Essay',
            'min_words' => 100,
            'max_words' => 500,
            'min_characters' => 400,
            'max_characters' => 2000,
        ]);
        $this->assertDatabaseHas('requirement_text', [
            'external_id' => 11,
            'scholarship_id' => 1,
            'requirement_name_id' => $this->requirementNameMap()['essay'],
            'title' => 'Test Essay 2',
            'description' => 'Description Essay 2',
        ]);

        $this->assertDatabaseHas('requirement_input', [
            'external_id' => 2,
            'scholarship_id' => 1,
            'requirement_name_id' => $this->requirementNameMap()['link'],
            'title' => 'Test Link',
            'description' => 'Description Link',
        ]);
        $this->assertDatabaseHas('requirement_input', [
            'external_id' => 22,
            'scholarship_id' => 1,
            'requirement_name_id' => $this->requirementNameMap()['video-link'],
            'title' => 'Test video link',
            'description' => 'Description video link',
        ]);
        $this->assertDatabaseHas('requirement_input', [
            'external_id' => 222,
            'scholarship_id' => 1,
            'requirement_name_id' => $this->requirementNameMap()['input-text'],
            'title' => 'Test text input',
            'description' => 'Description text input',
        ]);

        $this->assertDatabaseHas('requirement_file', [
            'external_id' => 3,
            'scholarship_id' => 1,
            'requirement_name_id' => $this->requirementNameMap()['proof-of-enrollment'],
            'title' => 'Test Proof of enrollment',
            'description' => 'Description Proof of enrollment',
        ]);
        $this->assertDatabaseHas('requirement_file', [
            'external_id' => 33,
            'scholarship_id' => 1,
            'requirement_name_id' => $this->requirementNameMap()['class-schedule'],
            'title' => 'Class schedule',
            'description' => 'Description class schedule',
            'max_file_size' => 10,
            'file_extension' => 'pdf,png',
        ]);

        $this->assertDatabaseHas('requirement_image', [
            'external_id' => 4,
            'scholarship_id' => 1,
            'requirement_name_id' => $this->requirementNameMap()['generic-picture'],
            'title' => 'Test Generic picture',
            'description' => 'Description Generic picture',
            'max_file_size' => 100,
            'file_extension' => 'png,jpg',
            'max_width' => 200,
            'max_height' => 100,
            'min_width' => 50,
            'min_height' => 25,
        ]);

        $this->assertDatabaseHas('requirement_survey', [
            'external_id' => 5,
            'scholarship_id' => 1,
            'requirement_name_id' => $this->requirementNameMap()['survey'],
            'title' => null,
            'description' => null,
            'survey' => $this->castToJson('[{"type": "checkbox", "options": {"1": "Mn", "2": "Tu", "3": "We", "4": "Thu", "5": "Fr", "6": "Sun", "7": "Sut"}, "question": "Favorite days of week?", "description": "Some optional description!!"}]')
        ]);

        $this->assertDatabaseHas('requirement_special_eligibility', [
            'external_id' => 6,
            'scholarship_id' => 1,
            'requirement_name_id' => $this->requirementNameMap()['checkbox'],
            'title' => 'Checkbox Req',
            'description' => 'Description chb req',
            'text' => 'Are you sure?'
        ]);

        $this->assertDatabaseHas('requirement_special_eligibility', [
            'external_id' => 66,
            'scholarship_id' => 1,
            'requirement_name_id' => $this->requirementNameMap()['offline-requirement'],
            'title' => 'Special Eligibility (Offline Requirement)',
            'description' => 'Description of Sp.Elb req',
            'text' => 'Are you agree to mail us you id data?'
        ]);

        // test update
        $message->setData('requirements', [
            [
                'id' => 1,
                'permanentId' => 1,
                'title' => 'Test Cover Letter',
                'description' => 'Description Cover Letter',
                'config' => [
                    'minWords' => 200,
                    'maxWords' => 1000,
                    'minChars' => 800,
                    'maxChars' => 4000,
                ],
                'requirement' => [
                    'id' => 'cover-letter',
                    'type' => 'text',
                    'name' => 'Cover Letter'
                ]
            ],
        ]);
        $message->setAttribute('timestamp', $message->getAttribute('timestamp') + 1);;
        $body = $message->getMessage();
        $resp = $this->postJson(route('pubsub.sunrise.manageScholarships', $secretKey), $body);

        $this->assertTrue($resp->status() === 204);
        $requirements = $message->getData('requirements');
        $this->assertDatabaseHas('requirement_text', [
            'external_id' => 1,
            'scholarship_id' => 1,
            'requirement_name_id' => $this->requirementNameMap()['cover-letter'],
            'title' => 'Test Cover Letter',
            'description' => 'Description Cover Letter',
            'min_words' => 200,
            'max_words' => 1000,
            'min_characters' => 800,
            'max_characters' => 4000,
        ]);
        $this->assertDatabaseMissing('requirement_text', [
            'external_id' => 11,
            'scholarship_id' => 1,
        ]);

        $this->assertDatabaseMissing('requirement_input', [
            'external_id' => 2,
            'scholarship_id' => 1,
        ]);
        $this->assertDatabaseMissing('requirement_input', [
            'external_id' => 22,
            'scholarship_id' => 1,
        ]);

        $this->assertDatabaseMissing('requirement_file', [
            'external_id' => 3,
            'scholarship_id' => 1,
        ]);
        $this->assertDatabaseMissing('requirement_file', [
            'external_id' => 33,
            'scholarship_id' => 1,
        ]);

        $this->assertDatabaseMissing('requirement_image', [
            'external_id' => 4,
            'scholarship_id' => 1,
        ]);

    }

    private function generateMessage(
        $externalScholarshipId = '15f821d9-9ede-11e8-81a1-0a580a080026',
        $externalScholarshipTmplId = '12jshfht-dy76-ee74-1242-01qwt74609r6', 
        $event = 'scholarship.published'
    )
    {
        $class = new class($externalScholarshipId, $externalScholarshipTmplId, $event)
        {
            protected $message;

            public function __construct($externalScholarshipId, $externalScholarshipTmplId, $event)
            {
                $this->message = [
                    'message' => [
                        'attributes' => [
                            'event' => $event,
                            'timestamp' => time()
                        ],
                        'data' => [
                            'id' => $externalScholarshipId,
                            'template' => $externalScholarshipTmplId,
                            'title' => 'Super test Sunrise scholarship',
                            'description' => 'Some description',
                            'start' => '2018-05-25T16:44:01+00:00',
                            'deadline' => '2018-06-25T16:44:01+00:00',
                            'timezone' => 'US/Eastern',
                            'amount' => '5000',
                            'recurringValue' => 1,
                            'recurringType' => 'week',
                            'expiredAt' => null,
                            'url' => "https://sunrise.scholarshipowl.com/$externalScholarshipId",
                            'fields' => [
                                [
                                    'eligibilityType' => null,
                                    'eligibilityValue' => null,
                                    'field' => [
                                        'id' => 'name',
                                        'name' => 'Name',
                                        'type' => 'text',
                                        'options' => []
                                    ]
                                ],
                                [
                                    'eligibilityType' => 'eq',
                                    'eligibilityValue' => '+123456789',
                                    'field' => [
                                        'id' => 'phone',
                                        'name' => 'Phone',
                                        'type' => 'phone',
                                        'options' => []
                                    ]
                                ],
                                [
                                    'eligibilityType' => 'neq',
                                    'eligibilityValue' => '+987654321',
                                    'field' => [
                                        'id' => 'phone',
                                        'name' => 'Phone',
                                        'type' => 'phone',
                                        'options' => []
                                    ]
                                ],
                                [
                                    'eligibilityType' => 'in',
                                    'eligibilityValue' => 't@t.com,t2@t.com',
                                    'field' => [
                                        'id' => 'email',
                                        'name' => 'E-mail',
                                        'type' => 'email',
                                        'options' => []
                                    ]
                                ],
                                [
                                    'eligibilityType' => 'nin',
                                    'eligibilityValue' => 't3@t.com,t4@t.com',
                                    'field' => [
                                        'id' => 'fieldOfStudy',
                                        'name' => 'fieldOfStudy',
                                        'type' => 'fieldOfStudy',
                                        'options' => []
                                    ]
                                ],
                                [
                                    'eligibilityType' => 'gt',
                                    'eligibilityValue' => 2,
                                    'field' => [
                                        'id' => 'fieldOfStudy',
                                        'name' => 'fieldOfStudy',
                                        'type' => 'fieldOfStudy',
                                        'options' => []
                                    ]
                                ],
                                [
                                    'eligibilityType' => 'gte',
                                    'eligibilityValue' => 3,
                                    'field' => [
                                        'id' => 'fieldOfStudy',
                                        'name' => 'fieldOfStudy',
                                        'type' => 'fieldOfStudy',
                                        'options' => []
                                    ]
                                ],
                                [
                                    'eligibilityType' => 'lt',
                                    'eligibilityValue' => 7,
                                    'field' => [
                                        'id' => 'fieldOfStudy',
                                        'name' => 'fieldOfStudy',
                                        'type' => 'fieldOfStudy',
                                        'options' => []
                                    ]
                                ],
                                [
                                    'eligibilityType' => 'lte',
                                    'eligibilityValue' => 6,
                                    'field' => [
                                        'id' => 'fieldOfStudy',
                                        'name' => 'fieldOfStudy',
                                        'type' => 'fieldOfStudy',
                                        'options' => []
                                    ]
                                ]
                            ],
                            'requirements' => [
                                [
                                    'id' => 1,
                                    'permanentId' => 1,
                                    'title' => 'Test Essay',
                                    'description' => 'Description Essay',
                                    'config' => [
                                        'minWords' => 100,
                                        'maxWords' => 500,
                                        'minChars' => 400,
                                        'maxChars' => 2000,
                                    ],
                                    'requirement' => [
                                        'id' => 'essay',
                                        'type' => 'text',
                                        'name' => 'Essay'
                                    ]
                                ],
                                [
                                    'id' => 11,
                                    'permanentId' => 11,
                                    'title' => 'Test Essay 2',
                                    'description' => 'Description Essay 2',
                                    'config' => [

                                    ],
                                    'requirement' => [
                                        'id' => 'essay',
                                        'type' => 'text',
                                        'name' => 'Essay'
                                    ]
                                ],
                                [
                                    'id' => 2,
                                    'permanentId' => 2,
                                    'title' => 'Test Link',
                                    'description' => 'Description Link',
                                    'config' => [

                                    ],
                                    'requirement' => [
                                        'id' => 'link',
                                        'type' => 'link',
                                        'name' => 'Link'
                                    ]
                                ],
                                [
                                    'id' => 22,
                                    'permanentId' => 22,
                                    'title' => 'Test video link',
                                    'description' => 'Description video link',
                                    'config' => [

                                    ],
                                    'requirement' => [
                                        'id' => 'video-link',
                                        'type' => 'link',
                                        'name' => 'Video link'
                                    ]
                                ],
                                [
                                    'id' => 222,
                                    'permanentId' => 222,
                                    'title' => 'Test text input',
                                    'description' => 'Description text input',
                                    'config' => [

                                    ],
                                    'requirement' => [
                                        'id' => 'input-text',
                                        'type' => 'input',
                                        'name' => 'Text input'
                                    ]
                                ],
                                [
                                    'id' => 3,
                                    'permanentId' => 3,
                                    'title' => 'Test Proof of enrollment',
                                    'description' => 'Description Proof of enrollment',
                                    'config' => [

                                    ],
                                    'requirement' => [
                                        'id' => 'proof-of-enrollment',
                                        'type' => 'file',
                                        'name' => 'Proof of enrollment'
                                    ]
                                ],
                                [
                                    'id' => 33,
                                    'permanentId' => 33,
                                    'title' => 'Class schedule',
                                    'description' => 'Description class schedule',
                                    'config' => [
                                        'fileExtensions' => 'pdf,png',
                                        'maxFileSize' => 10
                                    ],
                                    'requirement' => [
                                        'id' => 'class-schedule',
                                        'type' => 'file',
                                        'name' => 'Class schedule'
                                    ]
                                ],
                                [
                                    'id' => 4,
                                    'permanentId' => 3,
                                    'title' => 'Test Generic picture',
                                    'description' => 'Description Generic picture',
                                    'config' => [
                                        'maxFileSize' => 100,
                                        'fileExtensions' => 'png,jpg',
                                        'maxWidth' => 200,
                                        'maxHeight' => 100,
                                        'minWidth' => 50,
                                        'minHeight' => 25,
                                    ],
                                    'requirement' => [
                                        'id' => 'generic-picture',
                                        'type' => 'image',
                                        'name' => 'Generic picture'
                                    ]
                                ],
                                [
                                    'id' => 5,
                                    'permanentId' => 5,
                                    'title' => 'Favorite days of week?',
                                    'description' => 'Some optional description!!',
                                    'config' => [
                                    'multi' => true,
                                        'options' => [
                                            '1' => 'Mn',
                                            '2' => 'Tu',
                                            '3' => 'We',
                                            '4' => 'Thu',
                                            '5' => 'Fr',
                                            '6' => 'Sun',
                                            '7' => 'Sut'
                                        ],
                                    ],
                                    'optional' => true,
                                        'requirement' => [
                                        'id' => 'survey',
                                        'type' => 'survey',
                                        'name' => 'Survey'
                                    ]
                                ],
                                [
                                    'id' => 6,
                                    'permanentId' => 6,
                                    'title' => 'Checkbox Req',
                                    'description' => 'Description chb req',
                                    'config' => [
                                        'label' => 'Are you sure?'
                                    ],
                                    'requirement' => [
                                        'id' => 'checkbox',
                                        'type' => 'checkbox',
                                        'name' => 'Checkbox'
                                    ]
                                ],
                                [
                                    'id' => 66,
                                    'permanentId' => 66,
                                    'title' => 'Special Eligibility (Offline Requirement)',
                                    'description' => 'Description of Sp.Elb req',
                                    'config' => [
                                        'label' => 'Are you agree to mail us you id data?'
                                    ],
                                    'requirement' => [
                                        'id' => 'offline-requirement',
                                        'type' => 'checkbox',
                                        'name' => 'Offline Requirement'
                                    ]
                                ],
                            ]
                        ],
                    ],
                    'subscription' => 'projects/myproject/subscriptions/mysubscription'
                ];
            }

            public function setAttribute($key, $value)
            {
                $this->message['message']['attributes'][$key] = $value;
                return $this;
            }

            public function getAttribute($key)
            {
                return $this->message['message']['attributes'][$key];
            }

            public function setData($key, $value)
            {
                $this->message['message']['data'][$key] = $value;
                return $this;
            }

            public function getData($key)
            {
                return $this->message['message']['data'][$key];
            }

            public function getMessage()
            {
                $message = $this->message;
                $message['message']['data'] = base64_encode(json_encode($message['message']['data']));

                return $message;
            }
        };

        return $class;
    }
}
