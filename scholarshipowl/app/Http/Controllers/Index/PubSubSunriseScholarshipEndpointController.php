<?php

namespace App\Http\Controllers\Index;

use App\Entity\Citizenship;
use App\Entity\Country;
use App\Entity\Eligibility;
use App\Entity\Field;
use App\Entity\RequirementFile;
use App\Entity\RequirementImage;
use App\Entity\RequirementInput;
use App\Entity\RequirementName;
use App\Entity\RequirementSpecialEligibility;
use App\Entity\RequirementSurvey;
use App\Entity\RequirementText;
use App\Entity\Scholarship;
use App\Entity\ScholarshipStatus;
use App\Events\Scholarship\ScholarshipCreatedEvent;
use App\Events\Scholarship\ScholarshipUpdatedEvent;
use App\Facades\EntityManager;
use App\Services\EligibilityService;
use App\Services\ScholarshipService;
use App\Traits\SunriseSync;
use Google\Cloud\PubSub\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Svg\Tag\Text;

class PubSubSunriseScholarshipEndpointController extends PubSubSunrisePushEndpointBaseController
{
    use SunriseSync;

    CONST EVENT_DEADLINE = 'scholarship.deadline';
    CONST EVENT_PUBLISHED = 'scholarship.published';
    CONST EVENT_STATUS_CHANGED = 'scholarship.status_changed';

    /**
     * uuid if external scholarship
     *
     * @var string
     */
    public $externalScholarshipId;

    /**
     * Sync with Sunrise scholarships
     * Response with 204 status code implies implicit message ack.
     *
     * @param Request $request
     * @return Response
     * @throws \App\Facades\ORMException
     * @throws \App\Facades\ORMInvalidArgumentException
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Exception
     */
    public function manageScholarships(Request $request): Response
    {
        $this->handleRequest($request);

        $externalData = json_decode($this->message->data(), true);
        $externalScholarshipId = $this->externalScholarshipId;

        /** @var Scholarship $scholarship */
        $scholarship = EntityManager::getRepository(Scholarship::class)
            ->findOneBy(['externalScholarshipId' => $externalScholarshipId]);

        $isActive = $externalData['isActive'] ?? true;
        $incomingStatus = isset($externalData['status']) ? $this->statusMap()[$externalData['status']] : null;
        $status = $incomingStatus ?? ScholarshipStatus::PUBLISHED;
        $transitionalStatus = null;
        if ($this->event == self::EVENT_DEADLINE) {
            $isActive = $externalData['isActive'] ?? false;
            $status = $incomingStatus ?? ScholarshipStatus::EXPIRED;
            $transitionalStatus = Scholarship::TRANSITIONAL_STATUS_CHOOSING_WINNER;
        }
        else if ($scholarship) {
            $isActive = $externalData['isActive'] ?? $scholarship->isActive();
            $status = $incomingStatus ?? $scholarship->getStatus()->getId();
        }

        $attributes = [
            'external_scholarship_id' => $externalData['id'],
            'external_scholarship_template_id' => $externalData['template'] ?? null,
            'title' => $externalData['title'],
            'description' => isset($externalData['description']) && !empty($externalData['description']) ?
                strip_tags($externalData['description'], '<a><b><em><strong><br><p><ul><ol><li>') : null,
            'start_date' => new \DateTime($externalData['start']),
            'expiration_date' => new \DateTime($externalData['deadline']),
            'timezone' => $externalData['timezone'],
            'amount' => $externalData['amount'],
            'up_to' => $externalData['amount'],
            'awards' => $externalData['awards'] ?? 1,
            'url' => $externalData['url'] ?? '',
            'is_recurrent' => $externalData['recurringValue'] ?? 0,
            'recurring_value' => $externalData['recurringValue'] ?? null,
            'recurring_type' => $externalData['recurringType'] ?? null,
            'application_type' => Scholarship::APPLICATION_TYPE_SUNRISE,
            'is_active' => $isActive,
            'is_free' => $externalData['isFree'] ?? false,
            'is_automatic' => $externalData['isFree'] ?? false, // if is_free is TRUE then it's YDI scholarship, which is automatic one
            'status' => $status,
            'transitional_status' => $transitionalStatus,
            'terms_of_service_url' => $externalData['url_terms_of_use'] ?? null,
            'privacy_policy_url' => $externalData['url_privacy_policy'] ?? null
        ];

        $isNew = false;
        $isStatusUpdated = false;
        if ($scholarship) {
            $prevTransitionalStatus = $scholarship->getTransitionalStatus();
            // this endpoint only responsible for setting Scholarship::TRANSITIONAL_STATUS_CHOOSING_WINNER
            if ($prevTransitionalStatus !== null) {
                unset($attributes['transitional_status']);
            }

            if (isset($attributes['transitional_status'])) {
                \Log::info(
                    sprintf(
                        'Setting transitional status [ %s ] for Sunrise scholarship [ %s ]',
                        $attributes['transitional_status'], $externalScholarshipId
                    )
                );
            }


            $isStatusUpdated = (int)$scholarship->getStatus()->getId() !== (int)$status ||
                (int)$scholarship->isActive() !== (int)$isActive;

            $scholarship->hydrate($attributes);
            EntityManager::persist($scholarship);

            /** @var EligibilityService $eligibilityService */
            $eligibilityService = app()->get(EligibilityService::class);
            $eligibilityService->deleteEligibilities($scholarship);
        } else {
            $scholarship = new Scholarship($attributes);
            EntityManager::persist($scholarship);
            $isNew = true;
            $isStatusUpdated = true;
        }

        $this->populateEligibilities($externalData['fields'] ?? [], $scholarship);
        $this->populateRequirements($externalData['requirements'] ?? [], $scholarship, $isNew);

        EntityManager::flush();

        if ($isNew) {
            \Event::dispatch(new ScholarshipCreatedEvent($scholarship->getScholarshipId()));
        } else {
            \Event::dispatch(new ScholarshipUpdatedEvent($scholarship->getScholarshipId(), true, $isStatusUpdated));
        }

        \Log::info(
            sprintf(
                'Added/updated Sunrise Scholarship with external id [ %s ]. PubSub message: %s ',
                $externalScholarshipId,
                var_export($this->rawMessage, true)
            )
        );

        return response('', 204);
    }

    /**
     * @param array $fields
     * @param Scholarship $scholarship
     */
    protected function populateEligibilities(array $fields, Scholarship $scholarship)
    {
        $this->normalizeEligibilities($fields);

        foreach ($fields as $field) {
            $fieldIds = $this->eligibilityFieldsMap()[$field['alias']];

            foreach ($fieldIds as $id) {
                $value = $field['value'];

                if ($field['alias'] === 'enrollmentDate' && $id === Field::ENROLLMENT_MONTH) {
                    $value = !empty($field['value']) ? $this->dateFormat($field['value'], 'm') : '';
                } else if ($field['alias'] === 'enrollmentDate' && $id === Field::ENROLLMENT_YEAR) {
                    $value = !empty($field['value']) ? $this->dateFormat($field['value'], 'Y') : '';
                } else if ($field['alias'] === 'highSchoolGraduationDate' && $id === Field::HIGH_SCHOOL_GRADUATION_MONTH) {
                    $value = !empty($field['value']) ? $this->dateFormat($field['value'], 'm') : '';
                } else if ($field['alias'] === 'highSchoolGraduationDate' && $id === Field::HIGH_SCHOOL_GRADUATION_YEAR) {
                    $value = !empty($field['value']) ? $this->dateFormat($field['value'], 'Y') : '';
                }

                $eligibility = new Eligibility($id, $field['operator'], $value, $field['isOptional']);
                $eligibility->setScholarship($scholarship);
                EntityManager::persist($eligibility);
            }
        }

        $eligibility = new Eligibility(Field::COUNTRY, Eligibility::TYPE_VALUE, Country::USA);
        $eligibility->setScholarship($scholarship);
        EntityManager::persist($eligibility);
    }


    /**
     * @param array $requirements
     * @param Scholarship $scholarship
     */
    protected function populateRequirements(array $requirements, Scholarship $scholarship, bool $isNew)
    {
        $incomingTextRequirementIds = [];
        $existingTextRequirements = $isNew ? [] : $this->getRequirements(RequirementText::class, $scholarship);

        $incomingInputRequirementIds = [];
        $existingInputRequirements = $isNew ? [] : $this->getRequirements(RequirementInput::class, $scholarship);

        $incomingFileRequirementIds = [];
        $existingFileRequirements = $isNew ? [] : $this->getRequirements(RequirementFile::class, $scholarship);

        $incomingImageRequirementIds = [];
        $existingImageRequirements = $isNew ? [] : $this->getRequirements(RequirementImage::class, $scholarship);

        $incomingSurveyRequirementIds = [];
        $existingSurveyRequirements = $isNew ? [] : $this->getRequirements(RequirementSurvey::class, $scholarship);

        $incomingSpElbRequirementIds = [];
        $existingSpElbRequirements = $isNew ? [] : $this->getRequirements(RequirementSpecialEligibility::class, $scholarship);

        foreach ($requirements as $item) {
            $config = $item['config'];
            $externalId = $item['id'];
            $externalIdPermanent = $item['permanentId'];
            $requirementType = $item['requirement']['type'];
            $requirementNameId = $item['requirement']['id'];
            $isOptional = $item['optional'] ?? false;

            // force "text" requirement type because for these requirements SOWL does not support "file" type,
            // instead "allow files" flag is turned on.
            if (in_array($requirementNameId, ['cv', 'resume', 'recommendation-letter'])) {
                $requirementType = 'text';
            }

            if ($requirementType === 'text') {
                $incomingTextRequirementIds[] = $externalId;
                $data = [
                    'title' => $item['title'],
                    'description' => $item['description'] ?? '',
                    'send_type' => 'field'
                ];

                if (isset($item['config']) && !empty($item['config'])) {
                    $data['minWords'] = $config['minWords'] ?? null;
                    $data['maxWords'] = $config['maxWords'] ?? null;
                    $data['minCharacters'] = $config['minChars'] ?? null;
                    $data['maxCharacters'] = $config['maxChars'] ?? null;
                }

                /** @var RequirementText $requirementText */
                $requirementText = $existingTextRequirements[$externalId] ?? null;

                if (!$requirementText) {
                    $requirementText = new RequirementText($data);
                    $requirementText->setExternalId($externalId);
                    $requirementText->setExternalIdPermanent($externalIdPermanent);
                    $requirementText->setPermanentTag(substr(uniqid(), -8));
                    $requirementText->setAllowFile(true);
                    EntityManager::persist($requirementText);
                    $scholarship->addRequirementText($requirementText);
                } else {
                    $requirementText->hydrate($data);
                }

                $requirementText->setIsOptional($isOptional);
                $requirementText->setRequirementName($this->requirementNameMap()[$requirementNameId]);

                if (in_array($requirementNameId, ['cv', 'resume', 'recommendation-letter'])) {
                    $requirementText->setAllowFile(true);
                }
            } else if (in_array($requirementType, ['link', 'video-link', 'input'])) {
                $incomingInputRequirementIds[] = $externalId;

                /** @var RequirementInput $requirementInput */
                $requirementInput = $existingInputRequirements[$externalId] ?? null;

                if (!$requirementInput) {
                    $requirementInput = new RequirementInput(
                        $this->requirementNameMap()[$requirementNameId],
                        $item['title'],
                        substr(uniqid(), -8),
                        $item['description'] ?? ''
                    );
                    $requirementInput->setExternalId($externalId);
                    $requirementInput->setExternalIdPermanent($externalIdPermanent);
                    EntityManager::persist($requirementInput);
                    $scholarship->addRequirementInput($requirementInput);
                } else {
                    $requirementInput->setTitle($item['title']);
                    $requirementInput->setDescription($item['description']);
                }

                $requirementInput->setIsOptional($isOptional);
            } else if ($requirementType === 'file') {
                $incomingFileRequirementIds[] = $externalId;

                /** @var RequirementFile $requirementFile */
                $requirementFile = $existingFileRequirements[$externalId] ?? null;

                if (!$requirementFile) {
                    $requirementFile = new RequirementFile(
                        $this->requirementNameMap()[$requirementNameId],
                        $item['title'],
                        $item['description'] ?? '',
                        $config['fileExtensions'] ?? null,
                        $config['maxFileSize'] ?? null
                    );

                    $requirementFile->setExternalId($externalId);
                    $requirementFile->setExternalIdPermanent($externalIdPermanent);
                    EntityManager::persist($requirementFile);
                    $scholarship->addRequirementFile($requirementFile);
                } else {
                    $requirementFile->setTitle($item['title']);
                    $requirementFile->setDescription($item['description']);
                    $requirementFile->setFileExtension($config['fileExtensions'] ?? null);
                    $requirementFile->setMaxFileSize($config['maxFileSize'] ?? null);
                }

                $requirementFile->setIsOptional($isOptional);
            } else if ($requirementType === 'image') {
                $incomingImageRequirementIds[] = $externalId;

                /** @var RequirementImage $requirementImage */
                $requirementImage = $existingImageRequirements[$externalId] ?? null;

                if (!$requirementImage) {
                    $requirementImage = new RequirementImage(
                        $this->requirementNameMap()[$requirementNameId],
                        $item['title'],
                        $item['description'] ?? '',
                        $config['fileExtensions'] ?? null,
                        $config['maxFileSize'] ?? null,
                        $config['minWidth'] ?? null,
                        $config['maxWidth'] ?? null,
                        $config['minHeight'] ?? null,
                        $config['maxHeight'] ?? null
                    );

                    $requirementImage->setExternalId($externalId);
                    $requirementImage->setExternalIdPermanent($externalIdPermanent);
                    EntityManager::persist($requirementImage);
                    $scholarship->addRequirementImage($requirementImage);
                } else {
                    $requirementImage->setTitle($item['title']);
                    $requirementImage->setDescription($item['description']);
                    $requirementImage->setFileExtension($config['fileExtensions'] ?? null);
                    $requirementImage->setMaxFileSize($config['maxFileSize'] ?? null);
                    $requirementImage->setMinWidth($config['minWidth'] ?? null);
                    $requirementImage->setMaxWidth($config['maxWidth'] ?? null);
                    $requirementImage->setMinHeight($config['minHeight'] ?? null);
                    $requirementImage->setMaxHeight($config['maxHeight'] ?? null);
                }

                $requirementImage->setIsOptional($isOptional);
            } else if ($requirementType === 'survey') {
                $incomingSurveyRequirementIds[] = $externalId;

                /** @var RequirementSurvey $requirementSurvey */
                $requirementSurvey = $existingSurveyRequirements[$externalId] ?? null;
                $survey = [
                    [
                        'type' => $item['config']['multi'] ? 'checkbox' : 'radio',
                        'question' => $item['title'],
                        'description' => $item['description'] ?? '',
                        'options' => $item['config']['options']
                    ]
                ];

                if (!$requirementSurvey) {
                    $requirementSurvey = new RequirementSurvey([
                        'requirementName' => $this->requirementNameMap()[$requirementNameId],
                        'permanentTag' => substr(uniqid(), -8),
                        'externalId' => $externalId,
                        'externalIdPermanent' => $externalIdPermanent,
                        'survey' => $survey
                    ]);
                    EntityManager::persist($requirementSurvey);
                    $scholarship->addRequirementSurvey($requirementSurvey);
                } else {
                    $requirementSurvey->setSurvey($survey);
                }

                $requirementSurvey->setIsOptional($isOptional);
            } else if ($requirementType === 'checkbox') {
                $incomingSpElbRequirementIds[] = $externalId;

                /** @var RequirementSpecialEligibility $requirementSpElb */
                $requirementSpElb = $existingSpElbRequirements[$externalId] ?? null;

                if (!$requirementSpElb) {
                    $requirementSpElb = new RequirementSpecialEligibility([
                        'title' => $item['title'],
                        'description' => $item['description'] ?? '',
                        'requirementName' => $this->requirementNameMap()[$requirementNameId],
                        'permanentTag' => substr(uniqid(), -8),
                        'externalId' => $externalId,
                        'externalIdPermanent' => $externalIdPermanent,
                        'text' => $item['config']['label']
                    ]);
                    EntityManager::persist($requirementSpElb);
                    $scholarship->addRequirementSpecialEligibility($requirementSpElb);
                } else {
                    $requirementSpElb->setTitle($item['title'] ?? '');
                    $requirementSpElb->setDescription($item['description'] ?? '');
                    $requirementSpElb->setText($item['config']['label']);
                }

                $requirementSpElb->setIsOptional($isOptional);
            }
        }

        if ($diff = array_diff(array_keys($existingTextRequirements), $incomingTextRequirementIds)) {
            foreach ($diff as $externalId) {
                /** @var RequirementText $requirement */
                $requirement = $existingTextRequirements[$externalId];
                $scholarship->removeRequirementText($requirement);
            }
        }

        if ($diff = array_diff(array_keys($existingInputRequirements), $incomingInputRequirementIds)) {
            foreach ($diff as $externalId) {
                /** @var RequirementInput $requirement */
                $requirement = $existingInputRequirements[$externalId];
                $scholarship->removeRequirementInput($requirement);
            }
        }

        if ($diff = array_diff(array_keys($existingFileRequirements), $incomingFileRequirementIds)) {
            foreach ($diff as $externalId) {
                /** @var RequirementFile $requirement */
                $requirement = $existingFileRequirements[$externalId];
                $scholarship->removeRequirementFile($requirement);
            }
        }

        if ($diff = array_diff(array_keys($existingImageRequirements), $incomingImageRequirementIds)) {
            foreach ($diff as $externalId) {
                /** @var RequirementImage $requirement */
                $requirement = $existingImageRequirements[$externalId];
                $scholarship->removeRequirementImage($requirement);
            }
        }

        if ($diff = array_diff(array_keys($existingSurveyRequirements), $incomingSurveyRequirementIds)) {
            foreach ($diff as $externalId) {
                /** @var RequirementSurvey $requirement */
                $requirement = $existingSurveyRequirements[$externalId];
                $scholarship->removeRequirementSurvey($requirement);
            }
        }
    }

    /**
     * @param string $requirementEntity
     * @param Scholarship $scholarship
     * @return array
     */
    protected function getRequirements(string $requirementEntity, Scholarship $scholarship) {
        return \EntityManager::createQueryBuilder()
            ->select('r')
            ->from($requirementEntity, 'r', 'r.externalId')
            ->where('r.scholarship = :scholarship')
            ->setParameter('scholarship', $scholarship)
            ->getQuery()
            ->getResult();
    }

    /**
     * Validates PubSub message and creates Message instance.
     * Response with 204 status code implies implicit message ack.
     *
     * @param Request $request
     */
    protected function handleRequest(Request $request): void
    {
        parent::handleRequest($request);

        $externalData = json_decode($this->message->data(), true);

        $this->externalScholarshipId = $this->message->attribute('id') ?
            $this->message->attribute('id') : ($externalData['id'] ?? null);

        if (!$this->externalScholarshipId) {
            $msg = 'Sunrise scholarship must have an id';
            $this->logValidationError($msg);
            abort(400, $msg);
        }

        $this->event = $this->message->attribute('event');
        if (!in_array($this->event, [self::EVENT_PUBLISHED, self::EVENT_DEADLINE, self::EVENT_STATUS_CHANGED])) {
            $msg = sprintf('Unknown event [ %s ]', $this->event);
            $this->logValidationError($msg);
            abort(400, $msg);
        }

        if (!$this->maintainIdempotency("manageScholarships.timestamp.{$this->externalScholarshipId}", $this->message->attribute('timestamp'))) {
            $msg = sprintf(
                'sunrise scholarship with external id [ %s ] discarded because a later message was already processed',
                $this->externalScholarshipId
            );
            $this->logValidationError($msg);

            abort(204);
        }
    }

    protected function dateFormat(string $date, string $format)
    {
        try {
            return (new \DateTime($date))->format($format);
        } catch (\Throwable $e) {
            \Log::error($e);

            return '';
        }
    }
}

