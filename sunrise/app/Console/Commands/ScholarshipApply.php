<?php

namespace App\Console\Commands;

use App\Entities\Field;
use App\Entities\Requirement;
use App\Entities\Scholarship;
use App\Entities\ScholarshipField;
use App\Entities\ScholarshipRequirement;
use App\Services\ApplicationService;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Illuminate\Http\Testing\File;
use Illuminate\Validation\ValidationException;

class ScholarshipApply extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:apply
        { scholarship : Send scholarship application. }
        { --test : Use test data for application }
        { --test-email= : Use test email for application }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send new application.';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ApplicationService
     */
    protected $service;

    /**
     * Create a new command instance.
     *
     * @param EntityManager $em
     * @param ApplicationService $service
     */
    public function __construct(EntityManager $em, ApplicationService $service)
    {
        $this->em = $em;
        $this->service = $service;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Scholarship $scholarship */
        if (null === ($scholarship = $this->em->find(Scholarship::class, $this->argument('scholarship')))) {
            $this->error(sprintf('Can\'t find scholarship: %s', $this->argument('scholarship')));
            exit(1);
        }

        $data = [];

        if (!$this->option('test')) {

            $scholarship->getFields()->forAll(function(ScholarshipField $field) use (&$data) {
                switch ($field->getField()->getType()) {
                    case Field::TYPE_OPTION:
                        foreach ($field->getField()->getOptions() as $id => $option) {
                            $this->info(sprintf('[%d] %s', $id, is_array($option) ? $option['name'] : $option));
                        }
                        break;
                    default:
                        break;
                }

                $data[$field->getField()->getId()] = $this->ask($field->getField()->getName());
                return true;
            });


            $scholarship->getRequirements()->forAll(function(ScholarshipRequirement $requirement) use (&$use) {
                $this->info('Title: ' . $requirement->getTitle());
                $this->info('Description: ' . $requirement->getDescription());
                $this->info('Type: ' . $requirement->getRequirement()->getType());
                if (!isset($data[ApplicationService::KEY_REQUIREMENTS])) {
                    $data[ApplicationService::KEY_REQUIREMENTS] = [];
                }
                $data[ApplicationService::KEY_REQUIREMENTS][$requirement->getId()] =
                   $this->ask($requirement->getRequirement()->getName());
                return true;
            });

        } else {

            $scholarship->getFields()->forAll(function($key, ScholarshipField $field) use (&$data) {

                if ($field->isOptional()) {
                    return true;
                }

                $id = $field->getField()->getId();
                $value = null;

                switch ($id) {
                    case Field::NAME:
                        $value = 'Test application';
                        break;
                    case Field::EMAIL:
                        $value = $this->option('test-email') ?: (str_random().'@test.com');
                        break;
                    case Field::PHONE:
                        $value = '+1111111111';
                        break;
                    case Field::DATE_OF_BIRTH:
                        $value = Carbon::now()->subYears(18)->format('c');
                        break;
                    default:
                        break;
                }

                if (!$value) {
                    switch ($field->getField()->getType()) {
                        case Field::TYPE_OPTION:
                            $allowedOptions = array_filter(
                                array_keys($field->getField()->getOptions()),
                                function($option) use ($field) {
                                    if ($field->getEligibilityType() === ScholarshipField::ELIGIBILITY_TYPE_EQUALS) {
                                        return $option === $field->getEligibilityValue();
                                    }
                                    if ($field->getEligibilityType() === ScholarshipField::ELIGIBILITY_TYPE_NOT) {
                                        return $option !== $field->getEligibilityValue();
                                    }
                                    if ($field->getEligibilityType() === ScholarshipField::ELIGIBILITY_TYPE_IN) {
                                        return in_array($option, explode(',', $field->getEligibilityValue()));
                                    }
                                    if ($field->getEligibilityType() === ScholarshipField::ELIGIBILITY_TYPE_NOT_IN) {
                                        return !in_array($option, explode(',', $field->getEligibilityValue()));
                                    }
                                    return true;
                                }
                            );
                            $value = $allowedOptions[array_rand($allowedOptions)];
                            break;
                        default:
                            $value = str_random(128);
                            break;
                    }
                }

                $data[$id] = $value;
                return true;
            });

            $scholarship->getRequirements()->forAll(function($key, ScholarshipRequirement $requirement) use (&$data) {
                $value = null;

                if (!isset($data[ApplicationService::KEY_REQUIREMENTS])) {
                    $data[ApplicationService::KEY_REQUIREMENTS] = [];
                }

                switch ($requirement->getRequirement()->getType()) {
                    case Requirement::TYPE_FILE:
                        $value = File::fake()->create('test.txt', 20);
                        break;
                    case Requirement::TYPE_IMAGE:
                        $value = File::image('test.jpg');
                        break;
                    case Requirement::TYPE_LINK:
                        $value = 'https://test.com';
                        break;
                    default:
                        $value = str_random(16);
                        break;
                }

                $data[ApplicationService::KEY_REQUIREMENTS][$requirement->getId()] = $value;
                return true;
            });

        }

        try {
            $application = $this->service->apply($scholarship, $data);
        } catch (ValidationException $e) {
            $this->error('Got error on application');
            $this->warn($e->validator->errors());
            exit(1);
        }

        $this->warn(sprintf('Application sent: %s', $application->getId()));
    }
}
