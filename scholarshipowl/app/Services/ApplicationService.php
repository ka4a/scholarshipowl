<?php namespace App\Services;

use App\Contracts\RequirementFileContract;
use App\Entity\Account;
use App\Entity\Application;
use App\Entity\ApplicationSpecialEligibility;
use App\Entity\ApplicationStatus;
use App\Entity\RequirementImage;
use App\Entity\RequirementSpecialEligibility;
use App\Entity\Scholarship;
use App\Entity\Subscription;
use App\Events\Account\ApplicationsAddEvent;
use App\Events\Application\ApplicationSentSuccessEvent;
use App\Events\Subscription\SubscriptionCreditExhausted;
use App\Services\ApplicationService\ApplicationSenderEmail;
use App\Services\ApplicationService\ApplicationSenderInterface;
use App\Services\ApplicationService\ApplicationSenderOnline;
use App\Services\ApplicationService\ApplicationSenderSunrise;
use App\Services\ApplicationService\Exception\ApplicationException;
use App\Services\ApplicationService\Exception\EssayNotFinishedException;
use App\Services\ApplicationService\Exception\MissingRequirementException;
use App\Services\ApplicationService\Exception\MissingSubscriptionCredits;
use App\Services\ApplicationService\Exception\ApplicationSubscriptionNotFound;
use App\Services\ApplicationService\Exception\ScholarshipNotActive;
use App\Services\ApplicationService\Exception\ScholarshipNotEligible;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ApplicationService
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ScholarshipService
     */
    private $ss;

    /**
     * @var SubscriptionService
     */
    private $subscriptionService;

    /**
     * @var ApplicationSenderEmail
     */
    private $senderEmail;

    /**
     * @var ApplicationSenderOnline
     */
    private $senderOnline;

    /**
     * @var ApplicationSenderSunrise
     */
    private $senderSunrise;

    /**
     * ApplicationService constructor.
     *
     * @param SubscriptionService $subscriptionService
     * @param EntityManager       $em
     */
    public function __construct(
        ScholarshipService $scholarshipService,
        SubscriptionService $subscriptionService,
        EntityManager $em
    ) {
        $this->ss = $scholarshipService;
        $this->subscriptionService = $subscriptionService;
        $this->em = $em;
    }

    /**
     * @param ApplicationSenderEmail $senderEmail
     *
     * @return $this
     */
    public function setSenderEmail(ApplicationSenderEmail $senderEmail)
    {
        $this->senderEmail = $senderEmail;

        return $this;
    }

    /**
     * @return ApplicationSenderEmail
     */
    public function getSenderEmail()
    {
        if ($this->senderEmail === null) {
            $this->senderEmail = new ApplicationSenderEmail();
        }

        return $this->senderEmail;
    }

    /**
     * @param ApplicationSenderOnline $senderOnline
     *
     * @return $this
     */
    public function setSenderOnline(ApplicationSenderOnline $senderOnline)
    {
        $this->senderOnline = $senderOnline;

        return $this;
    }

    /**
     * @return ApplicationSenderOnline
     */
    public function getSenderOnline()
    {
        if ($this->senderOnline === null) {
            $this->senderOnline = new ApplicationSenderOnline();
        }

        return $this->senderOnline;
    }

    /**
     * @param ApplicationSenderSunrise $senderSunrise
     *
     * @return $this
     */
    public function setSenderSunrise(ApplicationSenderSunrise $senderSunrise)
    {
        $this->senderSunrise = $senderSunrise;

        return $this;
    }

    /**
     * @return ApplicationSenderSunrise
     */
    public function getSenderSunrise()
    {
        if ($this->senderSunrise === null) {
            $this->senderSunrise = new ApplicationSenderSunrise();
        }

        return $this->senderSunrise;
    }

    /**
     * @param Application $application
     *
     * @throws \Exception
     */
    public function sendApplication(Application $application)
    {
        try {
            $this->validateScholarshipApplication($application->getAccount(), $application->getScholarship(), false);

            $sender = $this->getSender($application);
            $scholarship = $sender->prepareScholarship($application->getScholarship(), $application->getAccount());
            $submitData = $sender->prepareSubmitData($scholarship, $application->getAccount());

            $application->setSubmitedData($submitData);
            $application->setApplicationStatus(ApplicationStatus::IN_PROGRESS);
            $this->em->flush($application);

            $response = $sender->sendApplication($application->getScholarship(), $submitData, $application);
            $application->setComment($response);
            $application->setApplicationStatus(ApplicationStatus::SUCCESS);
            $this->em->flush($application);

            \Event::dispatch(new ApplicationSentSuccessEvent($application, $response));

        } catch (\Exception $e) {
            if ($this->em->isOpen()) {
                $this->em->flush($application
                    ->setApplicationStatus(ApplicationStatus::ERROR)
                    ->setComment(sprintf(
                        "Message: %s\nCode: %s\nFile: %s\nLine: %s",
                        $e->getMessage(),
                        $e->getCode(),
                        $e->getFile(),
                        $e->getLine()
                    ))
                );
            }

            throw $e;
        }
    }

    /**
     * @param Application $application
     *
     * @return ApplicationSenderInterface
     */
    protected function getSender(Application $application) : ApplicationSenderInterface
    {
        switch ($application->getScholarship()->getApplicationType()) {
            case Scholarship::APPLICATION_TYPE_ONLINE:
                return $this->getSenderOnline();
                break;
            case Scholarship::APPLICATION_TYPE_EMAIL:
                return $this->getSenderEmail();
                break;
            case Scholarship::APPLICATION_TYPE_SUNRISE:
                return $this->getSenderSunrise();
                break;
            default:
                throw new \LogicException('Unknown application type!');
                break;
        }
    }

    /**
     * @param Account       $account
     * @param array         $ids
     *
     * @return array|Application[]
     */
    public function applyFreeScholarships(Account $account, array $ids = [])
    {
        $applications = [];
        foreach ($this->ss->getRepository()->findFreeScholarships($account, $ids) as $scholarship) {
            try {
                $applications[] = $this->applyScholarship($account, $scholarship);
            } catch (ApplicationException $e) {}
        }

        return $applications;
    }

    /**
     * @param Account     $account
     * @param Scholarship $scholarship
     * @param bool        $forceFree
     *
     * @return Application
     * @throws ApplicationSubscriptionNotFound
     * @throws MissingSubscriptionCredits
     * @throws ScholarshipNotEligible
     */
    public function applyScholarship(Account $account, Scholarship $scholarship, bool $forceFree = false)
    {
        if (!$scholarship->getIsFree() && !$forceFree) {
            if (null === ($subscription = $this->subscriptionService->getApplicationSubscription($account))) {
                throw new ApplicationSubscriptionNotFound(sprintf(
                    "Account (%s) subscription for apply scholarship (%s) not found",
                    $account->getAccountId(),
                    $scholarship->getScholarshipId()
                ));
            }

            $this->decreaseCredits($subscription);
            $this->em->flush($subscription);
        }

        if (null === ($application = $this->validateScholarshipApplication($account, $scholarship))) {
            $application = new Application($account, $scholarship, $subscription ?? null);
            $this->em->persist($application);
        }

        $application->setApplicationStatus(
            $scholarship->getApplicationType() === Scholarship::APPLICATION_TYPE_NONE ?
                ApplicationStatus::SUCCESS : ApplicationStatus::PENDING
        );

        if ($scholarship->getApplicationType() === Scholarship::APPLICATION_TYPE_SUNRISE &&
            ($templateId = $scholarship->getExternalScholarshipTemplateId())) {
            $application->setExternalScholarshipTemplateId($templateId);
        }

        $this->em->flush($application);
        $this->em->detach($application);

        \Event::dispatch(new ApplicationsAddEvent($account));

        return $application;
    }

    public function applySunriseRecurrentScholarship(Account $account, Scholarship $scholarship, bool $forceFree = false)
    {
        if (!$scholarship->getIsFree() && !$forceFree) {
            if (null === ($subscription = $this->subscriptionService->getApplicationSubscription($account))) {
                throw new ApplicationSubscriptionNotFound(sprintf(
                    "Account (%s) subscription for apply scholarship (%s) not found",
                    $account->getAccountId(),
                    $scholarship->getScholarshipId()
                ));
            }

            $this->decreaseCredits($subscription);
            $this->em->flush($subscription);
        }

        /** @var Application $latestApplication */
        $latestApplication = $this->em->createQueryBuilder()
            ->select(['a1'])
            ->from(Application::class, 'a1')
            ->leftJoin(
                Application::class, 'a2', 'WITH',
                'a1.account = a2.account and a1.scholarship > a2.scholarship and
                 a1.externalScholarshipTemplateId = a2.externalScholarshipTemplateId'

            )
            ->where('a2.account IS NULL')
            ->andWhere('a1.applicationStatus IN (:statuses)')
            ->andwhere('a1.account = :accountId')
            ->andwhere('a1.externalScholarshipTemplateId = :templateId')
            ->setParameter('accountId', $account->getAccountId())
            ->setParameter('templateId', $scholarship->getExternalScholarshipTemplateId())
            ->setParameter('statuses', [ApplicationStatus::SUCCESS, ApplicationStatus::PENDING])
            ->getQuery()
            ->setHint(Query::HINT_REFRESH, true)
            ->getSingleResult();

          $this->ss->copySunriseApplicationRequirements($latestApplication->getScholarship(), $scholarship);

        if (null === ($application = $this->validateScholarshipApplication($account, $scholarship))) {
            $application = new Application($account, $scholarship, $subscription ?? null);
            $this->em->persist($application);
        }

        $application->setApplicationStatus(
            $scholarship->getApplicationType() === Scholarship::APPLICATION_TYPE_NONE ?
                ApplicationStatus::SUCCESS : ApplicationStatus::PENDING
        );

        if ($scholarship->getApplicationType() === Scholarship::APPLICATION_TYPE_SUNRISE &&
            ($templateId = $scholarship->getExternalScholarshipTemplateId())) {
            $application->setExternalScholarshipTemplateId($templateId);
        }

        $this->em->flush($application);
        $this->em->detach($application);

        \Event::dispatch(new ApplicationsAddEvent($account));

        return $application;
    }

    /**
     * @param File                    $file
     * @param RequirementFileContract $requirementFile
     *
     * @return \Illuminate\Validation\Validator
     */
    public function validatorRequirementFile(File $file, RequirementFileContract $requirementFile)
    {
        list($rules, $messages) = $this->getRulesAndMessageForRequirementFile($requirementFile);

        $size = $file instanceof UploadedFile ? $file->getClientSize() : $file->getSize();
        $extension = $file instanceof UploadedFile ? $file->getClientOriginalExtension() : $file->getExtension();
        $extension = strtolower($extension);
        return \Validator::make(['extension' => $extension, 'size' => $size / (1024 * 1024)], $rules, $messages);
    }

    /**
     * @param File             $file
     * @param RequirementImage $requirementImage
     *
     * @return \Illuminate\Validation\Validator
     */
    public function validatorRequirementImage(File $file, RequirementImage $requirementImage)
    {
        $dimensions = [];
        list($rules, $messages) = $this->getRulesAndMessageForRequirementFile($requirementImage);
        $rules['file'] = 'image';

        $data = [
            'size' => ($file instanceof UploadedFile ? $file->getClientSize() : $file->getSize()) / (1024 * 1024),
            'extension' => $file instanceof UploadedFile ? $file->getClientOriginalExtension() : $file->getExtension(),
            'file' => $file,
        ];

        $minWidth = $requirementImage->getMinWidth();
        $maxWidth = $requirementImage->getMaxWidth();
        $dimensionsWidthMessage = ($minWidth || $maxWidth) ? 'Image width should be ' : '';
        if ($minWidth && $maxWidth && $minWidth === $maxWidth) {
            $dimensions[] = "width=$minWidth";
            $dimensionsWidthMessage .= "$minWidth pixels";
        } else {
            if ($minWidth) {
                $dimensions[] = "min_width=$minWidth";
                $dimensionsWidthMessage .= "at least $minWidth pixels";
            }
            if ($maxWidth) {
                $dimensions[] = "max_width=$maxWidth";
                $dimensionsWidthMessage .= ($minWidth ? 'and ' : '') . "most $maxWidth pixels";
            }
        }

        $minHeight = $requirementImage->getMinHeight();
        $maxHeight = $requirementImage->getMaxHeight();
        $dimensionsHeightMessage = ($minHeight || $maxHeight) ? 'Image height should be ' : '';
        if ($minHeight && $maxHeight && $minHeight === $maxHeight) {
            $dimensions[] = "height=$minHeight";
            $dimensionsHeightMessage .= "$minHeight pixels";
        } else {
            if ($minHeight) {
                $dimensions[] = "min_height=$minHeight";
                $dimensionsHeightMessage .= "at least $minHeight pixels";
            }
            if ($maxHeight) {
                $dimensions[] = "max_height=$maxHeight";
                $dimensionsHeightMessage .= ($minHeight ? 'and ' : '') . "most $maxHeight pixels";
            }
        }

        if (!empty($dimensions)) {
            $rules['file'] .= sprintf('|dimensions:%s', implode(',', $dimensions));
            $data['file'] = $file;

            $messages['dimensions'] =
                ($dimensionsWidthMessage ? $dimensionsWidthMessage . '.' : '') .
                ($dimensionsWidthMessage && $dimensionsHeightMessage ? "\n" : '') .
                ($dimensionsHeightMessage ? $dimensionsHeightMessage . '.' : '');
        }

        return \Validator::make($data, $rules, $messages);
    }

    /**
     * @param RequirementFileContract $requirementFile
     *
     * @return array
     */
    protected function getRulesAndMessageForRequirementFile(RequirementFileContract $requirementFile)
    {
         $rules = [
            'extension' => 'required',
            'size'      => 'required|numeric',
        ];

        $messages = [
            'between' => 'File size should be maximum: :max Mb.',
            'in' => 'The file format is incorrect. Please try one of these - :values',
        ];

        if ($maxFileSize = $requirementFile->getMaxFileSize()) {
            $rules['size'] .= sprintf('|between:0,%d', $maxFileSize);
        }

        if ($fileExtension = $requirementFile->getFileExtension()) {
            $rules['extension'] .= '|in:' . preg_replace('/\s+/', '', strtolower($fileExtension));
        }

        return [$rules, $messages];
    }

    /**
     * @param Subscription $subscription
     * @param int          $credits
     *
     * @return Subscription
     * @throws MissingSubscriptionCredits
     */
    protected function decreaseCredits(Subscription $subscription, int $credits = 1)
    {
        if (!$subscription->getIsScholarshipsUnlimited()) {
            if (($subscription->getCredit() - $credits) >= 0) {
                $creditAmount = $subscription->getCredit() - $credits;
                $subscription->setCredit($creditAmount);
                if($creditAmount == 0 && $subscription->isFreemium()){
                    \Event::dispatch(new SubscriptionCreditExhausted($subscription));
                }
            } else {
                throw new MissingSubscriptionCredits('Subscription missing credits');
            }
        }

        return $subscription;
    }

    /**
     * @param Account     $account
     * @param Scholarship $scholarship
     *
     * @return Application|null
     * @throws ScholarshipNotEligible
     * @throws ScholarshipNotActive
     * @throws EssayNotFinishedException
     */
    private function validateScholarshipApplication(
        Account $account, Scholarship $scholarship, bool $checkEligibility = true
    )
    {
        $previousApplication = null;
        $scholarship = $this->updateScholarshipWithApplicationRequirements($scholarship, $account);

        if ($checkEligibility && !$this->ss->isEligible($account, $scholarship)) {
            throw new ScholarshipNotEligible('Scholarship not eligible for the user');
        }

        if (!$scholarship->isPublished() || !$scholarship->isActive() || $scholarship->isExpired()) {
            throw new ScholarshipNotActive(sprintf('Scholarship not active or expired'));
        }

        foreach ($scholarship->getApplications() as $application) {
            if ($application->getApplicationStatus()->is(ApplicationStatus::SUCCESS)) {
                throw new \LogicException("Account already applied to the scholarship!");
            } else {
                $previousApplication = $application;
            }
        }

        $finishedRequirements = $scholarship->getFinishedRequirements($account);

        foreach ($scholarship->getMandatoryRequirementTexts() as $requirementText) {
            if (!$finishedRequirements->contains($requirementText)) {
                throw new MissingRequirementException(sprintf(
                    'Scholarship missing text requirement: %s', $requirementText->getId()
                ));
            }
        }

        foreach ($scholarship->getMandatoryRequirementFiles() as $requirementFile) {
            if (!$finishedRequirements->contains($requirementFile)) {
                throw new MissingRequirementException(sprintf(
                    'Scholarship missing file requirement: %s', $requirementFile->getId()
                ));
            }
        }

        foreach ($scholarship->getMandatoryRequirementImages() as $requirementImage) {
            if (!$finishedRequirements->contains($requirementImage)) {
                throw new MissingRequirementException(sprintf(
                    'Scholarship missing image requirement: %s', $requirementImage->getId()
                ));
            }
        }

        foreach ($scholarship->getMandatoryRequirementInputs() as $requirementInput) {
            if (!$finishedRequirements->contains($requirementInput)) {
                throw new MissingRequirementException(sprintf(
                    'Scholarship missing input requirement: %s', $requirementInput->getId()
                ));
            }
        }

        foreach ($scholarship->getMandatoryRequirementSpecialEligibility() as $requirementSpecialElb) {
            if (!$finishedRequirements->contains($requirementSpecialElb)) {
                throw new MissingRequirementException(sprintf(
                    'Scholarship missing special eligibility requirement: %s', $requirementSpecialElb->getId()
                ));
            }
        }
        foreach ($scholarship->getApplicationSpecialEligibility($account) as $applicationSpecialEligibility) {
            if (!$applicationSpecialEligibility->getVal()) {
                throw new MissingRequirementException('Special eligibility requirement checkbox must be ticked');
            }
        }

        foreach ($scholarship->getMandatoryRequirementSurvey() as $requirementSurvey) {
            if (!$finishedRequirements->contains($requirementSurvey)) {
                throw new MissingRequirementException(sprintf(
                    'Scholarship missing survey requirement: %s', $requirementSurvey->getId()
                ));
            }
        }

        return $previousApplication;
    }

    /**
     * @param Scholarship $scholarship
     * @param Account     $account
     *
     * @return Scholarship
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function updateScholarshipWithApplicationRequirements(Scholarship $scholarship, Account $account)
    {
        return $this->em->createQueryBuilder()
            ->select(['s', 'a', 'rt', 'rf', 'ri', 'rsp', 'rs', 'at', 'af', 'ai', 'asp', 'asr'])
            ->from(Scholarship::class, 's')
            ->where('s.scholarshipId = :scholarship')
            ->leftJoin('s.requirementTexts', 'rt')
            ->leftJoin('s.requirementFiles', 'rf')
            ->leftJoin('s.requirementImages', 'ri')
            ->leftJoin('s.requirementSpecialEligibility', 'rsp')
            ->leftJoin('s.requirementSurvey', 'rs')
            ->leftJoin('s.applications', 'a', Query\Expr\Join::WITH, 'a.account = :account')
            ->leftJoin('s.applicationTexts', 'at', Query\Expr\Join::WITH, 'at.account = :account')
            ->leftJoin('s.applicationFiles', 'af', Query\Expr\Join::WITH, 'af.account = :account')
            ->leftJoin('s.applicationImages', 'ai', Query\Expr\Join::WITH, 'ai.account = :account')
            ->leftJoin('s.applicationSpecialEligibility', 'asp', Query\Expr\Join::WITH, 'asp.account = :account')
            ->leftJoin('s.applicationSurvey', 'asr', Query\Expr\Join::WITH, 'asr.account = :account')
            ->setParameter('scholarship', $scholarship)
            ->setParameter('account', $account)
            ->getQuery()
            ->setHint(Query::HINT_REFRESH, true)
            ->getOneOrNullResult();
    }
}
