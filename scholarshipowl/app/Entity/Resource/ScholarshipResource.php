<?php namespace App\Entity\Resource;

use App\Entity\Account;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\RequirementSurvey;
use App\Entity\Scholarship;
use Doctrine\ORM\Query;
use ScholarshipOwl\Data\AbstractResource;
use ScholarshipOwl\Data\ResourceCollection;

class ScholarshipResource extends AbstractResource
{
    /**
     * @var Scholarship
     */
    protected $entity;

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var ScholarshipRepository
     */
    protected $repository;

    /**
     * ScholarshipResource constructor.
     *
     * @param Account|null $account
     */
    public function __construct(Account $account = null)
    {
        if ($account) {
            $this->setAccount($account);
        }
        $this->repository = \EntityManager::getRepository(Scholarship::class);
    }

    /**
     * @param $account
     *
     * @return $this
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return Account|null
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $data = [
            'scholarshipId'  => $this->entity->getScholarshipId(),
            'isFavorite'     => $this->entity->isFavorite(),
            'isSent'         => $this->entity->isSent(),
            'isAutomatic'    => $this->entity->getIsAutomatic(),
            'derivedStatus'  => $this->entity->getDerivedStatus(),
            'url'            => $this->entity->getPublicUrl(),
            'logo'           => $this->entity->getLogoUrl(),
            'title'          => $this->entity->getTitle(),
            'description'    => $this->entity->getDescription(),
            'externalUrl'    => $this->entity->getUrl(),
            'TOSUrl'         => $this->entity->getTermsOfServiceUrl(),
            'PPUrl'          => $this->entity->getPrivacyPolicyUrl(),
            'amount'         => $this->entity->getAmount(),
            'timezone'       => $this->entity->getTimezone(),
            'timezoneAbbr'   => $this->entity->getTimezone() ?
                (new \DateTime('now', new \DateTimeZone($this->entity->getTimezone())))->format('T') : null,
            'expirationDate' => $this->entity->getExpirationDate(),
            'isRecurrent'    => $this->entity->getIsRecurrent(),
            'image'          => $this->entity->getImage() != null ? \Storage::public($this->entity->getImage()) : null,
            'requirements'   => [
                'texts' => [],
                'files' => [],
                'images' => [],
                'inputs' => [],
                'survey' => [],
                'specialEligibility' => [],
            ],
        ];

        if ($this->entity->getDerivedStatus() == Scholarship::DERIVED_STATUS_WON) {
            $data['winnerFormUrl'] =  $this->entity->getWinnerFormUrl();
        } else {
            $data['winnerFormUrl'] = '';
        }

        if ($this->entity->getRequirementTexts()->count()) {
            $data['requirements']['texts'] = ResourceCollection::collectionToArray(
                new RequirementTextResource(),
                $this->entity->getRequirementTexts()
            );
        }

        if ($this->entity->getRequirementFiles()->count()) {
            $data['requirements']['files'] = ResourceCollection::collectionToArray(
                new RequirementFileResource(),
                $this->entity->getRequirementFiles()
            );
        }

        if ($this->entity->getRequirementImages()->count()) {
            $data['requirements']['images'] = ResourceCollection::collectionToArray(
                new RequirementImageResource(),
                $this->entity->getRequirementImages()
            );
        }

        if ($this->entity->getRequirementInputs()->count()) {
            $data['requirements']['inputs'] = ResourceCollection::collectionToArray(
                new RequirementInputResource(),
                $this->entity->getRequirementInputs()
            );
        }

        if ($this->entity->getRequirementSurvey()->count()) {
            $data['requirements']['survey'] = ResourceCollection::collectionToArray(
                new RequirementSurveyResource(),
                $this->entity->getRequirementSurvey()
            );
        }

        if ($this->entity->getRequirementSpecialEligibility()->count()) {
            $data['requirements']['specialEligibility'] = ResourceCollection::collectionToArray(
                new RequirementSpecialEligibilityResource(),
                $this->entity->getRequirementSpecialEligibility()
            );
        }

        if ($this->getAccount()) {
            $data['application'] = [
                'status' => $this->entity->getApplicationStatus() ?? $this->getApplicationStatus(),
                'texts' => [],
                'files' => [],
                'images' => [],
                'inputs' => [],
                'survey' => [],
                'specialEligibility' => [],
            ];

            $application = $this->entity->getApplications()->toArray();
            if(isset($application[0]) && is_array($application)){
                $data['application']['submitedDate'] = $application[0]->getDateApplied()->getTimestamp();
                $data['application']['externalStatusUpdatedAt'] = $application[0]->getExternalStatusUpdatedAt();
            }

            if ($applicationTexts = $this->entity->getApplicationTexts($this->getAccount())) {
                $data['application']['texts'] = ResourceCollection::collectionToArray(
                    new ApplicationTextResource(false),
                    $applicationTexts
                );
            }

            if ($applicationFiles = $this->entity->getApplicationFiles($this->getAccount())) {
                $data['application']['files'] = ResourceCollection::collectionToArray(
                    new ApplicationFileResource(false),
                    $applicationFiles
                );
            }

            if ($applicationImages = $this->entity->getApplicationImages($this->getAccount())) {
                $data['application']['images'] = ResourceCollection::collectionToArray(
                    new ApplicationImageResource(false),
                    $applicationImages
                );
            }

            if ($applicationInputs = $this->entity->getApplicationInputs($this->getAccount())) {
                $data['application']['inputs'] = ResourceCollection::collectionToArray(
                    new ApplicationInputResource(false),
                    $applicationInputs
                );
            }

            if ($applicationSurvey = $this->entity->getApplicationSurvey($this->getAccount())) {
                $data['application']['survey'] = ResourceCollection::collectionToArray(
                    new ApplicationSurveyResource(false),
                    $applicationSurvey
                );
            }

            if ($applicationSpecialEligibility = $this->entity->getApplicationSpecialEligibility($this->getAccount())) {
                $data['application']['specialEligibility'] = ResourceCollection::collectionToArray(
                    new ApplicationSpecialEligibilityResource(false),
                    $applicationSpecialEligibility
                );
            }
        }

        return $data;
    }

    /**
     * @return int
     */
    protected function getApplicationStatus()
    {
        $id = $this->entity->getScholarshipId();
        return $this->repository->getScholarshipStatus([$id], $this->account)[$id]['status'] ?? 0;
    }
}
