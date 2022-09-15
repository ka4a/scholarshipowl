<?php namespace App\Services;

use App\Doctrine\QueryIterator;
use App\Entities\Application;
use App\Entities\ApplicationFile;
use App\Entities\ApplicationRequirement;
use App\Entities\ApplicationStatus;
use App\Entities\Field;
use App\Entities\Requirement;
use App\Entities\Scholarship;
use App\Entities\ScholarshipField;
use App\Events\ApplicationCreatedEvent;
use App\Repositories\ScholarshipRepository;
use App\Rules\EligibilityBetween;
use App\Rules\EligibilityEquals;
use App\Rules\EligibilityGt;
use App\Rules\EligibilityGte;
use App\Rules\EligibilityIn;
use App\Rules\EligibilityLt;
use App\Rules\EligibilityLte;
use App\Rules\EligibilityNot;
use App\Rules\EligibilityNotIn;
use App\Rules\MaxWords;
use App\Rules\MinWords;
use App\Services\ApplicationService\ApplicationServiceException;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ApplicationService
{
    const APPLICATION_SOURCE = 'source';

    const APPLICATION_STATUS = '_status';

    const KEY_REQUIREMENTS = 'requirements';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * ApplicationService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Scholarship $scholarship
     * @param array $data
     * @return Application
     * @throws ValidationException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function apply(Scholarship $scholarship, array $data)
    {
        if ($scholarship->isExpired()) {
            throw new ApplicationServiceException(
                sprintf('Can\'t apply to expired scholarship: %s', $scholarship->getId())
            );
        }

        $fields = $this->validateEligibilityRules($scholarship, $data);
        $requirements = $scholarship->getRequirements();

        $application = new Application();
        $application->setScholarship($scholarship);

        if (isset($fields[Field::NAME])) {
            $application->setName($fields[Field::NAME]);
        }

        if (isset($fields[Field::EMAIL])) {
            $application->setEmail($fields[Field::EMAIL]);
        }

        if (isset($fields[Field::PHONE])) {
            $application->setPhone($fields[Field::PHONE]);
            $fields[Field::PHONE] = phone_format_us($fields[Field::PHONE]);
        }

        if (isset($fields[Field::STATE])) {
            $application->setState($fields[Field::STATE]);
        }

        $application->setData($fields);

        if (isset($data[static::APPLICATION_SOURCE])) {
            $application->setSource($data[static::APPLICATION_SOURCE]);
        }

        if (isset($data[static::APPLICATION_STATUS])) {
            $application->setStatus($data[static::APPLICATION_STATUS]);
        } else if ($requirements->isEmpty()) {
            $application->setStatus(ApplicationStatus::ACCEPTED);
        }

        foreach ($requirements as $requirement) {
            $id = $requirement->getId();
            $applicationRequirement = new ApplicationRequirement();
            $applicationRequirement->setApplication($application);
            $applicationRequirement->setRequirement($requirement);
            switch ($requirement->getRequirement()->getType()) {
                case Requirement::TYPE_TEXT:
                case Requirement::TYPE_INPUT:
                case Requirement::TYPE_LINK:
                    $applicationRequirement->setValue($data[static::KEY_REQUIREMENTS][$id]);
                    break;
                case Requirement::TYPE_FILE:
                case Requirement::TYPE_IMAGE:
                case Requirement::TYPE_VIDEO:
                    $applicationRequirement->addFiles(ApplicationFile::uploaded($data[static::KEY_REQUIREMENTS][$id]));
                    break;
            }

            $application->addRequirements($applicationRequirement);
        }

        $this->em->persist($application);
        $this->em->flush($application);

        ApplicationCreatedEvent::dispatch($application->getId());

        return $application;
    }

    /**
     * Receive student profile ( application data ) and return list of eligible scholarship IDs.
     *
     * @param array         $data
     * @param Query|null    $query
     * @param bool          $verifiedData
     * @return array
     * @throws ValidationException
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     */
    public function eligible(array $data, $query = null, $verifiedData = false)
    {
        $result = [];
        $fields = $verifiedData === false ? $this->verifyEligibilityData($data) : $data;

        if (is_null($query)) {

            /** @var ScholarshipRepository $scholarshipRepository */
            $scholarshipRepository = $this->em->getRepository(Scholarship::class);
            $query = $scholarshipRepository->queryAllPublished();

        }

        foreach (QueryIterator::create($query, 100) as $scholarships) {
            /** @var Scholarship $scholarship */
            foreach ($scholarships as $scholarship) {
                try {
                    $this->validateEligibilityRules($scholarship, $fields);
                    $result[] = $scholarship->getId();
                } catch (ValidationException $e) {}
            }

            $this->em->clear(Scholarship::class);
        }

        return $result;
    }

    /**
     * Verify received data and return validated fields.
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     * @throws \Doctrine\ORM\ORMException
     */
    public function verifyEligibilityData(array $data)
    {
        /** @var Field[] $allFields */
        $allFields = $this->em->getRepository(Field::class)->findAll();
        $rules = [];

        foreach ($allFields as $field) {
            if (isset($data[$field->getId()])) {
                $rules[$field->getId()] = $this->buildBasicFieldRules($field);
            }
        }

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($data, $rules, $this->validationMessages());
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $fields = [];
        foreach (array_keys($rules) as $fieldId) {
            $fields[$fieldId] = $data[$fieldId];
        }

        return $fields;
    }

    /**
     * @param Scholarship $scholarship
     * @param array $data
     * @return array
     * @throws ValidationException
     * @throws \Doctrine\ORM\ORMException
     */
    protected function validateEligibilityRules(Scholarship $scholarship, array $data)
    {
        $rules = [];
        $fields = $this->validateBasicScholarshipFields($scholarship, $data);

        foreach ($scholarship->getFields() as $eligibility) {
            $uid = $eligibility->getField()->getId();
            $fieldRules = $this->buildEligibilityRules($eligibility);
            $rules[$uid] = isset($rules[$uid]) ? array_merge($rules[$uid], $fieldRules) : $fieldRules;
        }

        /**
         * Override eligibility check for DATE OF BIRTH and check not date but AGE.
         */
        if (isset($fields[Field::DATE_OF_BIRTH])) {
            $dateOfBirth = Carbon::parse($fields[Field::DATE_OF_BIRTH]);
            $fields[Field::DATE_OF_BIRTH] = $dateOfBirth->age;
        }


        $fields = Validator::make($fields, $rules, [], [Field::DATE_OF_BIRTH => 'age'])->validate();

        /**
         * Set proper date of birth after age check.
         */
        if (isset($fields[Field::DATE_OF_BIRTH]) && isset($dateOfBirth)) {
            $fields[Field::DATE_OF_BIRTH] = $dateOfBirth->format('Y-m-d');
        }

        $rules = [];
        $customAttributes = [];

        if (!$scholarship->getRequirements()->isEmpty()) {
            $rules[static::KEY_REQUIREMENTS] = ['required', 'array'];
        }

        foreach ($scholarship->getRequirements() as $requirement) {
            $key = static::KEY_REQUIREMENTS.'.'.$requirement->getId();
            $type = $requirement->getRequirement()->getType();
            $requirementRules = $this->buildRequirementsRules($type, $requirement->getConfig());
            $rules[$key] = isset($rules[$key]) ? array_merge($rules[$key], $requirementRules) : $requirementRules;
            $customAttributes[$key] = strtolower($requirement->getRequirement()->getName());
        }

        Validator::make($data, $rules, [], $customAttributes)->validate();

        return $fields;
    }

    /**
     * @param Scholarship $scholarship
     * @param array $data
     * @return array
     * @throws ValidationException
     * @throws \Doctrine\ORM\ORMException
     */
    protected function validateBasicScholarshipFields(Scholarship $scholarship, array $data)
    {
        $rules = [];

        foreach ($scholarship->getFields() as $eligibility) {
            $uid = $eligibility->getField()->getId();

            $fieldRules = $this->buildBasicFieldRules($eligibility->getField(), !$eligibility->isOptional());

            /**
             * Verify student with such email not applied for the scholarship.
             */
            if ($uid === Field::EMAIL) {
                $fieldRules[] = 'unique:'.Application::class.',email,NULL,id,scholarship,'.$scholarship->getId();
            }

            $rules[$uid] = isset($rules[$uid]) ? array_merge($rules[$uid], $fieldRules) : $fieldRules;
        }

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($data, $rules, $this->validationMessages());

        return $validator->validate();
    }

    /**
     * @return array
     */
    protected function validationMessages()
    {
        return [
            Field::EMAIL.'.unique' => 'Student with such email already applied for the scholarship!'
        ];
    }


    /**
     * Get basic fields validation rules.
     * Add additional verification to field depends on field type.
     *
     * @param Field $field
     * @param bool  $required
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    protected function buildBasicFieldRules(Field $field, $required = true)
    {
        $rules = $required ? ['required'] : ['nullable'];

        switch ($field->getType()) {
            case Field::TYPE_EMAIL:
                $rules[] = 'email';
                break;
            case Field::TYPE_DATE:
                $rules[] = 'date';
                break;
            case Field::TYPE_PHONE:
            case Field::TYPE_TEXT:
                $rules[] = 'string';
                break;
            case Field::TYPE_OPTION:
                $rules[] = sprintf('in:%s',
                    implode(',', array_keys(Field::find($field->getId())->getOptions()))
                );
                break;
            default:
                break;
        }

        return $rules;
    }

    /**
     * @param ScholarshipField $field
     * @return array
     */
    protected function buildEligibilityRules(ScholarshipField $field)
    {
        $type = $field->getEligibilityType();
        $value = $field->getEligibilityValue();
        $rules = [];

        if ($type && $value) {
            $options = $field->getField()->getOptions();
            $options = is_array($options) ? $options : [];

            switch ($type) {
                case ScholarshipField::ELIGIBILITY_TYPE_EQUALS:
                    $rules[] = new EligibilityEquals($value, $options);
                    break;

                case ScholarshipField::ELIGIBILITY_TYPE_NOT:
                    $rules[] = new EligibilityNot($value, $options);
                    break;

                case ScholarshipField::ELIGIBILITY_TYPE_GT:
                    $rules[] = new EligibilityGt($value, $options);
                    break;

                case ScholarshipField::ELIGIBILITY_TYPE_GTE:
                    $rules[] = new EligibilityGte($value, $options);
                    break;

                case ScholarshipField::ELIGIBILITY_TYPE_LT:
                    $rules[] = new EligibilityLt($value, $options);
                    break;

                case ScholarshipField::ELIGIBILITY_TYPE_LTE:
                    $rules[] = new EligibilityLte($value, $options);
                    break;

                case ScholarshipField::ELIGIBILITY_TYPE_BETWEEN:
                    $rules[] = new EligibilityBetween($value, $options);
                    break;

                case ScholarshipField::ELIGIBILITY_TYPE_IN:
                    $rules[] = new EligibilityIn($value, $options);
                    break;

                case ScholarshipField::ELIGIBILITY_TYPE_NOT_IN:
                    $rules[] = new EligibilityNotIn($value, $options);
                    break;

                default:
                    throw new \LogicException(sprintf('Unknown eligibility type "%s"', $type));
                    break;
            }
        }

        return $rules;
    }

    /**
     * @param $type
     * @param array $config
     * @return array
     */
    protected function buildRequirementsRules($type, array $config)
    {
        $rules = ['required'];

        switch ($type) {
            case Requirement::TYPE_TEXT:
                $rules[] = 'string';
                if (isset($config[Requirement::TYPE_TEXT_KEY_MIN_WORDS])) {
                    $rules[] = new MinWords($config[Requirement::TYPE_TEXT_KEY_MIN_WORDS]);
                }
                if (isset($config[Requirement::TYPE_TEXT_KEY_MAX_WORDS])) {
                    $rules[] = new MaxWords($config[Requirement::TYPE_TEXT_KEY_MAX_WORDS]);
                }
                if (isset($config[Requirement::TYPE_TEXT_KEY_MIN_CHARS])) {
                    $rules[] = sprintf('min:%s', $config[Requirement::TYPE_TEXT_KEY_MIN_CHARS]);
                }
                if (isset($config[Requirement::TYPE_TEXT_KEY_MAX_CHARS])) {
                    $rules[] = sprintf('max:%s', $config[Requirement::TYPE_TEXT_KEY_MAX_CHARS]);
                }
                break;
            case Requirement::TYPE_INPUT:
                $rules[] = 'string';
                if (isset($config[Requirement::TYPE_INPUT_KEY_MIN_CHARS])) {
                    $rules[] = sprintf('min:%s', $config[Requirement::TYPE_INPUT_KEY_MIN_CHARS]);
                }
                if (isset($config[Requirement::TYPE_INPUT_KEY_MAX_CHARS])) {
                    $rules[] = sprintf('max:%s', $config[Requirement::TYPE_INPUT_KEY_MAX_CHARS]);
                }
                break;
            case Requirement::TYPE_LINK:
                $rules[] = 'string';
                $rules[] = 'url';
                if (isset($config[Requirement::TYPE_LINK_KEY_MIN_CHARS])) {
                    $rules[] = sprintf('min:%s', $config[Requirement::TYPE_LINK_KEY_MIN_CHARS]);
                }
                if (isset($config[Requirement::TYPE_LINK_KEY_MAX_CHARS])) {
                    $rules[] = sprintf('max:%s', $config[Requirement::TYPE_LINK_KEY_MAX_CHARS]);
                }
                break;
            case Requirement::TYPE_FILE:
                $rules[] = 'file';
                if (isset($config[Requirement::TYPE_FILE_KEY_MAX_FILE_SIZE])) {
                    $rules[] = sprintf('max:%s', $config[Requirement::TYPE_FILE_KEY_MAX_FILE_SIZE]);
                }
                if (isset($config[Requirement::TYPE_FILE_KEY_FILE_EXTENSIONS])) {
                    $rules[] = sprintf('mimes:%s', $config[Requirement::TYPE_FILE_KEY_FILE_EXTENSIONS]);
                }
                break;
            case Requirement::TYPE_IMAGE:
                $rules[] = 'image';
                if (isset($config[Requirement::TYPE_IMAGE_KEY_MAX_FILE_SIZE])) {
                    $rules[] = sprintf('max:%s', $config[Requirement::TYPE_IMAGE_KEY_MAX_FILE_SIZE]);
                }
                if (isset($config[Requirement::TYPE_IMAGE_KEY_FILE_EXTENSIONS])) {
                    $mimes =  str_replace('jpg', 'jpeg', $config[Requirement::TYPE_IMAGE_KEY_FILE_EXTENSIONS]);
                    $rules[] = sprintf('mimes:%s', $mimes);
                }

                $dimensions = array_map(
                    function($key) use ($config) {
                        return sprintf('%s=%s', $key, $config[$key]);
                    },
                    array_filter(array_keys($config), function($key) {
                        return in_array($key, [
                            Requirement::TYPE_IMAGE_KEY_MIN_WIDTH,
                            Requirement::TYPE_IMAGE_KEY_MAX_WIDTH,
                            Requirement::TYPE_IMAGE_KEY_MIN_HEIGHT,
                            Requirement::TYPE_IMAGE_KEY_MAX_HEIGHT,
                        ]);
                    })
                );

                if (!empty($dimensions)) {
                    $rules[] = 'dimensions:' . join(',', $dimensions);
                }

                break;
            case Requirement::TYPE_VIDEO:
                $rules[] = 'video';
                if (isset($config[Requirement::TYPE_FILE_KEY_MAX_FILE_SIZE])) {
                    $rules = sprintf('max:%s', $config[Requirement::TYPE_FILE_KEY_MAX_FILE_SIZE]);
                }
                if (isset($config[Requirement::TYPE_FILE_KEY_FILE_EXTENSIONS])) {
                    $rules = sprintf('mimes:%s', $config[Requirement::TYPE_FILE_KEY_FILE_EXTENSIONS]);
                }
                break;
        }

        return $rules;
    }
}
