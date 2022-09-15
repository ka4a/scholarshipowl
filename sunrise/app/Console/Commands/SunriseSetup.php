<?php namespace App\Console\Commands;

use App\Entities\Field;
use App\Entities\Requirement;
use App\Entities\Settings;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class SunriseSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sunrise:setup'.
        ' { --set-default-settings : Set default settings }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup sunrise application with default DB settings.';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * SunriseSetup constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function handle()
    {
        $this->setupFields();
        $this->setupSettings();
        $this->setupRequirements();
    }

    /**
     * Setup default settings for sunrise application.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function setupSettings()
    {
        $affidavitContent = file_get_contents(resource_path('legal-templates/affidavit.html'));
        $this->setSetting(Settings::CONFIG_LEGAL_AFFIDAVIT, 'Affidavit', $affidavitContent);

        $privacyPolicyContent = file_get_contents(resource_path('legal-templates/privacyPolicy.html'));
        $this->setSetting(Settings::CONFIG_LEGAL_PRIVACY_POLICY, 'Privacy Policy', $privacyPolicyContent);

        $termsOfUseContent = file_get_contents(resource_path('legal-templates/termsOfUse.html'));
        $this->setSetting(Settings::CONFIG_LEGAL_TERMS_OF_USE, 'Terms of Use', $termsOfUseContent);
    }

    /**
     * Setup scholarship fields id, names and options.
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    protected function setupFields()
    {
        $name = $this->findOrCreateField(Field::NAME);
        $name->setType(Field::TYPE_TEXT);
        $name->setName('Name');

        $phone = $this->findOrCreateField(Field::PHONE);
        $phone->setType(Field::TYPE_PHONE);
        $phone->setName('Phone');

        $email = $this->findOrCreateField(Field::EMAIL);
        $email->setType(Field::TYPE_EMAIL);
        $email->setName('E-mail');

        $state = $this->findOrCreateField(Field::STATE);
        $state->setType(Field::TYPE_OPTION);
        $state->setName('State');
        $state->setOptions($this->stateOptions());

        $age = $this->findOrCreateField(Field::DATE_OF_BIRTH);
        $age->setType(Field::TYPE_DATE);
        $age->setName('Date Of Birth');

        $city = $this->findOrCreateField(Field::CITY);
        $city->setType(Field::TYPE_TEXT);
        $city->setName('City');

        $address = $this->findOrCreateField(Field::ADDRESS);
        $address->setType(Field::TYPE_TEXT);
        $address->setName('Address');

        $zip = $this->findOrCreateField(Field::ZIP);
        $zip->setType(Field::TYPE_TEXT);
        $zip->setName('Zip code');

        $schoolLevel = $this->findOrCreateField(Field::SCHOOL_LEVEL);
        $schoolLevel->setType(Field::TYPE_OPTION);
        $schoolLevel->setName('School Level');
        $schoolLevel->setOptions($this->schoolLevelOptions());

        $fieldOfStudy = $this->findOrCreateField(Field::FIELD_OF_STUDY);
        $fieldOfStudy->setType(Field::TYPE_OPTION);
        $fieldOfStudy->setName('Field of study');
        $fieldOfStudy->setOptions($this->fieldOfStudyOptions());

        $degreeType = $this->findOrCreateField(Field::DEGREE_TYPE);
        $degreeType->setType(Field::TYPE_OPTION);
        $degreeType->setName('Degree type');
        $degreeType->setOptions($this->degreeTypeOptions());

        $gender = $this->findOrCreateField(Field::GENDER);
        $gender->setType(Field::TYPE_OPTION);
        $gender->setName('Gender');
        $gender->setOptions($this->genderOptions());

        $ethnicity = $this->findOrCreateField(Field::ETHNICITY);
        $ethnicity->setType(Field::TYPE_OPTION);
        $ethnicity->setName('Ethnicity');
        $ethnicity->setOptions($this->ethnicityOptions());

        $enrollmentDate = $this->findOrCreateField(Field::ENROLLMENT_DATE);
        $enrollmentDate->setType(Field::TYPE_DATE);
        $enrollmentDate->setName('Enrollment Date');

        $gpa = $this->findOrCreateField(Field::GPA);
        $gpa->setType(Field::TYPE_OPTION);
        $gpa->setName('GPA');
        $gpa->setOptions($this->GPAOptions());

        $careerGoal = $this->findOrCreateField(Field::CAREER_GOAL);
        $careerGoal->setType(Field::TYPE_OPTION);
        $careerGoal->setName('Career Goal');
        $careerGoal->setOptions($this->CareerGoalOptions());

        $highSchoolName = $this->findOrCreateField(Field::HIGH_SCHOOL_NAME);
        $highSchoolName->setType(Field::TYPE_TEXT);
        $highSchoolName->setName('High School Name');

        $highSchoolGraduationDate = $this->findOrCreateField(Field::HIGH_SCHOOL_GRADUATION_DATE);
        $highSchoolGraduationDate->setType(Field::TYPE_DATE);
        $highSchoolGraduationDate->setName('High School Graduation Date');

        $collegeName = $this->findOrCreateField(Field::COLLEGE_NAME);
        $collegeName->setType(Field::TYPE_TEXT);
        $collegeName->setName('College name');

        $collegeGraduationDate = $this->findOrCreateField(Field::COLLEGE_GRADUATION_DATE);
        $collegeGraduationDate->setType(Field::TYPE_DATE);
        $collegeGraduationDate->setName('College graduation date');

        $this->em->flush();
        $this->info('Scholarship fields setup.');
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function setupRequirements()
    {
        foreach ($this->requirements() as $requirementData) {
            $requirement =  $this->findOrCreateRequirement($requirementData['name']);
            $requirement->setType($requirementData['type']);
        }

        $this->em->flush();
        $this->info('Scholarship requirements updated.');
    }

    /**
     * @param string $id
     * @param string $name
     * @param mixed $config
     * @return Settings
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    protected function setSetting($id, $name, $config)
    {
        $new = false;

        /** @var Settings $setting */
        if (null === ($setting = $this->em->find(Settings::class, $id))) {
            $new = true;
            $setting = new Settings();
            $setting->setId($id);
            $setting->setConfig($config);
            $setting->setName($name);
            $this->em->persist($setting);
            $this->em->flush($setting);
            $this->warn(sprintf('New setting value "%s" was set.', $id));
        }

        if (!$new && $this->option('set-default-settings')) {
            $setting->setConfig($config);
            $setting->setName($name);
            $this->em->flush($setting);
            $this->warn(sprintf('Setting "%s" value updated.', $id));
        }

        return $setting;
    }

    /**
     * @param string $id
     * @return Field
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    protected function findOrCreateField($id)
    {
        if (null === ($field = $this->em->find(Field::class, $id))) {
            $field = new Field();
            $field->setId($id);
            $this->em->persist($field);
        }

        return $field;
    }

    /**
     * @param string $name
     * @return Requirement
     * @throws \Doctrine\ORM\ORMException
     */
    protected function findOrCreateRequirement($name)
    {
        if (null === ($requirement = $this->em->find(Requirement::class, str_slug($name)))) {
            $requirement = new Requirement();
            $requirement->setName($name);
            $this->em->persist($requirement);
        }
        return $requirement;
    }

    /**
     * @return array
     */
    protected function requirements()
    {
        return [
            [
                'name' => 'Essay',
                'type' => Requirement::TYPE_TEXT
            ],
            [
                'name' => 'Resume',
                'type' => Requirement::TYPE_TEXT
            ],
            [
                'name' => 'Recommendation Letter',
                'type' => Requirement::TYPE_TEXT
            ],
            [
                'name' => 'CV',
                'type' => Requirement::TYPE_TEXT
            ],
            [
                'name' => 'Cover letter',
                'type' => Requirement::TYPE_TEXT
            ],
            [
                'name' => 'Bio',
                'type' => Requirement::TYPE_TEXT
            ],
            [
                'name' => 'Input text',
                'type' => Requirement::TYPE_INPUT,
            ],
            [
                'name' => 'Link',
                'type' => Requirement::TYPE_LINK
            ],
            [
                'name' => 'Video link',
                'type' => Requirement::TYPE_LINK
            ],
            [
                'name' => 'Transcript',
                'type' => Requirement::TYPE_FILE
            ],
            [
                'name' => 'Class schedule',
                'type' => Requirement::TYPE_FILE
            ],
            [
                'name' => 'Proof of acceptance',
                'type' => Requirement::TYPE_FILE
            ],
            [
                'name' => 'Proof of enrollment',
                'type' => Requirement::TYPE_FILE
            ],
            [
                'name' => 'Generic picture',
                'type' => Requirement::TYPE_IMAGE
            ],
            [
                'name' => 'ProfilePic',
                'type' => Requirement::TYPE_IMAGE
            ],
        ];
    }

    /**
     * @return array
     */
    protected function stateOptions()
    {
        return [
            '1' => [ 'name' => 'Alabama', 'abbreviation' => 'AL' ],
            '2' => [ 'name' => 'Alaska', 'abbreviation' => 'AK' ],
            '3' => [ 'name' => 'Arizona', 'abbreviation' => 'AZ' ],
            '4' => [ 'name' => 'Arkansas', 'abbreviation' => 'AR' ],
            '5' => [ 'name' => 'California', 'abbreviation' => 'CA' ],
            '6' => [ 'name' => 'Colorado', 'abbreviation' => 'CO' ],
            '7' => [ 'name' => 'Connecticut', 'abbreviation' => 'CT' ],
            '8' => [ 'name' => 'Delaware', 'abbreviation' => 'DE' ],
            '9' => [ 'name' => 'District of Columbia', 'abbreviation' => 'DC' ],
            '10' => [ 'name' => 'Florida', 'abbreviation' => 'FL' ],
            '11' => [ 'name' => 'Georgia', 'abbreviation' => 'GA' ],
            '12' => [ 'name' => 'Hawaii', 'abbreviation' => 'HI' ],
            '13' => [ 'name' => 'Idaho', 'abbreviation' => 'ID' ],
            '14' => [ 'name' => 'Illinois', 'abbreviation' => 'IL' ],
            '15' => [ 'name' => 'Indiana', 'abbreviation' => 'IN' ],
            '16' => [ 'name' => 'Iowa', 'abbreviation' => 'IA' ],
            '17' => [ 'name' => 'Kansas', 'abbreviation' => 'KS' ],
            '18' => [ 'name' => 'Kentucky', 'abbreviation' => 'KY' ],
            '19' => [ 'name' => 'Louisiana', 'abbreviation' => 'LA' ],
            '20' => [ 'name' => 'Maine', 'abbreviation' => 'ME' ],
            '21' => [ 'name' => 'Maryland', 'abbreviation' => 'MD' ],
            '22' => [ 'name' => 'Massachusetts', 'abbreviation' => 'MA' ],
            '23' => [ 'name' => 'Michigan', 'abbreviation' => 'MI' ],
            '24' => [ 'name' => 'Minnesota', 'abbreviation' => 'MN' ],
            '25' => [ 'name' => 'Mississippi', 'abbreviation' => 'MS' ],
            '26' => [ 'name' => 'Missouri', 'abbreviation' => 'MO' ],
            '27' => [ 'name' => 'Montana', 'abbreviation' => 'MT' ],
            '28' => [ 'name' => 'Nebraska', 'abbreviation' => 'NE' ],
            '29' => [ 'name' => 'Nevada', 'abbreviation' => 'NV' ],
            '30' => [ 'name' => 'New Hampshire', 'abbreviation' => 'NH' ],
            '31' => [ 'name' => 'New Jersey', 'abbreviation' => 'NJ' ],
            '32' => [ 'name' => 'New Mexico', 'abbreviation' => 'NM' ],
            '33' => [ 'name' => 'New York', 'abbreviation' => 'NY' ],
            '34' => [ 'name' => 'North Carolina', 'abbreviation' => 'NC' ],
            '35' => [ 'name' => 'North Dakota', 'abbreviation' => 'ND' ],
            '36' => [ 'name' => 'Ohio', 'abbreviation' => 'OH' ],
            '37' => [ 'name' => 'Oklahoma', 'abbreviation' => 'OK' ],
            '38' => [ 'name' => 'Oregon', 'abbreviation' => 'OR' ],
            '39' => [ 'name' => 'Pennsylvania', 'abbreviation' => 'PA' ],
            '40' => [ 'name' => 'Puerto Rico', 'abbreviation' => 'PR' ],
            '41' => [ 'name' => 'Rhode Island', 'abbreviation' => 'RI' ],
            '42' => [ 'name' => 'South Carolina', 'abbreviation' => 'SC' ],
            '43' => [ 'name' => 'South Dakota', 'abbreviation' => 'SD' ],
            '44' => [ 'name' => 'Tennessee', 'abbreviation' => 'TN' ],
            '45' => [ 'name' => 'Texas', 'abbreviation' => 'TX' ],
            '46' => [ 'name' => 'Utah', 'abbreviation' => 'UT' ],
            '47' => [ 'name' => 'Vermont', 'abbreviation' => 'VT' ],
            '48' => [ 'name' => 'Virginia', 'abbreviation' => 'VA' ],
            '49' => [ 'name' => 'Washington', 'abbreviation' => 'WA' ],
            '50' => [ 'name' => 'West Virginia', 'abbreviation' => 'WV' ],
            '51' => [ 'name' => 'Wisconsin', 'abbreviation' => 'WI' ],
            '52' => [ 'name' => 'Wyoming', 'abbreviation' => 'WY' ],
        ];
    }

    /**
     * @return array
     */
    protected function schoolLevelOptions()
    {
        return [
            '1' => "High school freshman",
            '2' => "High school sophomore",
            '3' => "High school junior",
            '4' => "High school senior",
            '5' => "College 1st year",
            '6' => "College 2nd year",
            '7' => "College 3rd year",
            '8' => "College 4th year",
            '9' => "Graduate student",
            '10' => "Adult/Non-traditional Student",
        ];
    }

    /**
     * @return array
     */
    protected function fieldOfStudyOptions()
    {
        return [
            '1' => 'Agriculture and Related Sciences',
            '2' => 'Architecture and Related Services',
            '3' => 'Area, Ethnic, Cultural and Gender Studies',
            '4' => 'Biological and Biomedical Sciences',
            '5' => 'Business, Management and Marketing',
            '6' => 'Communication and Journalism',
            '7' => 'Computer and Information Sciences',
            '8' => 'Construction Trades',
            '9' => 'Education',
            '10' => 'Engineering',
            '11' => 'English Language and Literature',
            '12' => 'Family and Consumer Sciences',
            '13' => 'Foreign Languages, Literature and Linguistics',
            '14' => 'Health Professions and Clinical Sciences',
            '15' => 'History',
            '16' => 'Legal Professions and Law Studies',
            '17' => 'Liberal Arts / General Studies',
            '18' => 'Library Science',
            '19' => 'Mathematics and Statistics',
            '20' => 'Mechanic and Repair Tech / Technicians',
            '21' => 'Military Technologies',
            '22' => 'Multi / Interdisciplinary Studies',
            '23' => 'Natural Resources and Conservation',
            '24' => 'Parks, Recreation, and Fitness Studies',
            '25' => 'Personal and Culinary Services',
            '26' => 'Philosophy and Religious Studies',
            '27' => 'Physical Sciences',
            '28' => 'Precision Production',
            '29' => 'Psychology',
            '30' => 'Public Administration and Social Service',
            '31' => 'Security and Protective Services',
            '32' => 'Social Sciences',
            '33' => 'Technology Education / Industrial Arts',
            '34' => 'Theology and Religious Vocations',
            '35' => 'Transportation and Materials Moving',
            '36' => 'Visual and Performing Arts',
            '37' => 'Not Listed / Other',
        ];
    }

    /**
     * @return array
     */
    protected function genderOptions()
    {
        return [
            '1' => 'Female',
            '2' => 'Male',
            '3' => 'Other',
        ];
    }

    /**
     * @return array
     */
    protected function ethnicityOptions()
    {
        return [
            '1' => 'Caucasian',
            '2' => 'African American',
            '3' => 'Hispanic / Latino',
            '4' => 'Asian / Pacific Islander',
            '5' => 'American Indian / Native Alaskan',
            '6' => 'Other',
        ];
    }

    /**
     * @return array
     */
    protected function degreeTypeOptions()
    {
        return [
            '1' => 'Undecided',
            '2' => 'Certificate',
            '3' => 'Associate\'s Degree',
            '4' => 'Bachelor\'s Degree',
            '5' => 'Graduate Certificate',
            '6' => 'Master\'s Degree',
            '7' => 'Doctoral (Ph.D.)',
        ];
    }

    /**
     * @return array
     */
    protected function GPAOptions()
    {
        return [
            '1' => '2.0',
            '2' => '2.1',
            '3' => '2.2',
            '4' => '2.3',
            '5' => '2.4',
            '6' => '2.6',
            '7' => '2.7',
            '8' => '2.8',
            '9' => '2.9',
            '10' => '3.0',
            '11' => '3.1',
            '12' => '3.2',
            '13' => '3.3',
            '14' => '3.4',
            '15' => '3.5',
            '16' => '3.6',
            '17' => '3.7',
            '18' => '3.8',
            '19' => '3.9',
            '20' => '4.0',
        ];
    }

    /**
     * @return array
     */
    protected function careerGoalOptions()
    {
        return [
            '1' => 'Art, Design or Fashion',
            '2' => 'Beauty or Cosmetology',
            '3' => 'Business / Marketing / Management',
            '4' => 'Computers / IT / Technology',
            '5' => 'Culinary Arts',
            '6' => 'Health Care / Nursing',
            '7' => 'Law / Criminal Justice',
            '8' => 'Teaching / Education',
            '9' => 'Vocational / Technical',
            '10' => 'Other',
        ];
    }
}
