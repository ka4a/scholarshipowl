<?php namespace App\Services;

use App\Entity\Account;
use App\Entity\AccountFreshScholarship;
use App\Entity\AccountsFavoriteScholarships;
use App\Entity\Application;
use App\Entity\Contracts\ApplicationRequirementContract;
use App\Entity\Contracts\RequirementContract;
use App\Entity\EligibilityCache;
use App\Entity\Form;
use App\Entity\Repository\EligibilityCacheRepository;
use App\Entity\Repository\FreshScholarshipRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\RequirementFile;
use App\Entity\RequirementImage;
use App\Entity\RequirementInput;
use App\Entity\RequirementText;
use App\Entity\Scholarship;

use App\Entity\ScholarshipStatus;
use App\Events\Scholarship\FreshScholarshipEvent;
use App\Events\Scholarship\ScholarshipBeforeRecurredEvent;
use App\Events\Scholarship\ScholarshipDeletedEvent;
use App\Events\Scholarship\ScholarshipExpiredEvent;
use App\Events\Scholarship\ScholarshipPublishedEvent;
use App\Events\Scholarship\ScholarshipRecurredEvent;

use App\Http\Controllers\Rest\ScholarshipRestController;
use App\Mail\RecurrentScholarshipNotify;
use Carbon\Carbon;

use Doctrine\ORM\EntityManager;

use Doctrine\ORM\Query;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Mail;
use ScholarshipOwl\Data\DateHelper;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;
use ScholarshipOwl\Util\Mailer;
use Symfony\Component\HttpFoundation\File\File;

class ScholarshipService
{
    const CACHE_KEY_IS_ELIGIBLE = 'IS_ELIGIBLE_ACCOUNT_SCHOLARSHIP_%d_%d';
    const CACHE_TTL_IS_ELIGIBLE = 24 * 60;

    /**
     * @var DocumentGenerator
     */
    protected $documentGenerator;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ScholarshipRepository
     */
    protected $repository;

    /**
     * @var EligibilityService
     */
    protected $eligibilityService;

    /**
     * @var EligibilityCacheService
     */
    protected $elbCacheService;

    /**
     * ScholarshipService constructor.
     *
     * @param EligibilityService $eligibilityService
     * @param DocumentGenerator  $documentGenerator
     * @param EntityManager      $em
     */
    public function __construct(
        EligibilityService $eligibilityService,
        DocumentGenerator $documentGenerator,
        EntityManager $em
    ) {
        $this->em = $em;
        $this->repository = $em->getRepository(Scholarship::class);
        $this->eligibilityService = $eligibilityService;
        $this->documentGenerator = $documentGenerator;
        $this->elbCacheService = app()->get(EligibilityCacheService::class);
    }

    /**
     * @return ScholarshipRepository|\Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param RequirementText $requirementText
     * @param string          $text
     *
     * @return File
     */
    public function generateRequirementFile(RequirementText $requirementText, string $text)
    {
        return $this->documentGenerator->generate(
            $requirementText->getAttachmentType(),
            $requirementText->getTitle(),
            $text
        );
    }

    /**
     * @param \App\Entity\Account         $account
     * @param int|\App\Entity\Scholarship $scholarship
     *
     * @return bool
     */
    public function isEligible(Account $account, $scholarship)
    {
        return $this->eligibilityService->isEligible($account, $scholarship);
    }

    /**
     * @param array|Scholarship[]   $scholarships
     * @param Account|int $account
     *
     * @return array
     */
    public function filterEligible(array $scholarships, $account)
    {
        if (!count($scholarships)) {
            return [];
        }

        $accountId = ($account instanceof Account) ? $account->getAccountId() : $account;

        /** @var EligibilityCacheService $elbCacheService */
        $elbCacheService = app(EligibilityCacheService::class);


        $targetScholarshipIds = array_map(function($s) {
            return $s instanceof Scholarship ? $s->getScholarshipId() : $s;
        }, $scholarships);

        $targetEligibleIds = $elbCacheService->getAccountEligibleScholarshipIds($accountId, $scholarships);

        return array_values(
            array_filter($scholarships,
                function($scholarship) use ($targetEligibleIds, $accountId) {
                    $id = ($scholarship instanceof Scholarship) ? $scholarship->getScholarshipId() : $scholarship;
                    return in_array($id, $targetEligibleIds);
                }
            )
        );
    }

    /**
     * @param array $accountIds
     * @param array|null $scholarshipsIds
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function listEligibleScholarships(array $accountIds, array $scholarshipsIds = null)
    {
        $result = array_fill_keys(array_values($accountIds), null);

        $accountsToEligibleIds = $this->elbCacheService->getEligibleScholarshipIds($accountIds);
        $accountsToTargetIds = [];
        $scholarshipIdsAll = [];

        foreach ($accountsToEligibleIds as $accountId => $eligibleIds) {
            $accountsToTargetIds[$accountId] = array_intersect($scholarshipsIds, $eligibleIds);
            $scholarshipIdsAll = array_merge($scholarshipIdsAll, $accountsToTargetIds[$accountId]);
        }

        $scholarshipsDataAll = $this->scholarshipListData($scholarshipIdsAll);

        foreach ($accountsToTargetIds as $accountId => $targetIds) {
            $result[$accountId] = [
                'count' => count($targetIds),
                'titles' => [],
                'headlines' => []
            ];

            if (!$result[$accountId]['count']) {
                continue;
            }

            foreach ($targetIds as $targetId) {
                $result[$accountId]['titles'][] = $scholarshipsDataAll[$targetId]['title'];
                $result[$accountId]['headlines'][] = $scholarshipsDataAll[$targetId]['headline'];
            }
        }

        return $result;
    }

    /**
     * @param array $scholarshipIds
     * @return array [[scholarship_id, title, headline],...]
     * @throws \Exception
     */
    public function scholarshipListData(array $scholarshipIds)
    {
        $data = [];

        if (!empty($scholarshipIds)) {
            $items = \DB::table('scholarship')
                ->select(['scholarship_id', 'title', 'amount', 'expiration_date'])
                ->whereIn('scholarship_id', array_unique($scholarshipIds))
                ->get();

            foreach ($items as $item) {
                $deadline = (new \DateTime($item->expiration_date))->format('m/d/Y');
                $amount = '$'.number_format((int)$item->amount, 0, '.', ',');
                $data[$item->scholarship_id] = [
                    'scholarship_id' => (int)$item->scholarship_id,
                    'title' => $item->title,
                    'headline' => "$amount | $item->title | $deadline"
                ];
            }
        }

        return $data;
    }

    /**
     * @param array $accountIds
     * @param array $targetScholarshipIds
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function countEligibleScholarships(array $accountIds, array $targetScholarshipIds = [])
    {
        /**
         * @var EligibilityCacheService $elbCacheService
         */
        $elbCacheService = app()->get(EligibilityCacheService::class);
        return $elbCacheService->getEligibleCount($accountIds, $targetScholarshipIds);

    }

    /**
     * @param array $accountIds
     * @param array $targetScholarshipIds
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function amountEligibleScholarships(array $accountIds, array $targetScholarshipIds = [])
    {
        /**
         * @var EligibilityCacheService $elbCacheService
         */
        $elbCacheService = app()->get(EligibilityCacheService::class);
        return $elbCacheService->getEligibleAmount($accountIds, $targetScholarshipIds, null, true);
    }

    /**
     * @param array $accounts
     * @param array|null $expiringScholarships
     * @return array [accountId => amount]
     */
    public function amountEligibleExpiringScholarships(array $accounts, array $expiringScholarships = null)
    {
        if (!$expiringScholarships) {
            $expiringScholarships = $this->repository->findExpiringScholarshipsIds();
        }

        return $this->amountEligibleScholarships($accounts, $expiringScholarships);
    }

    /**
     * Copy scholarship
     *
     * @param Scholarship $from
     *
     * @return Scholarship
     */
    public function copy(Scholarship $from)
    {
        $scholarship = clone $from;
        $scholarship->setTitle(sprintf('Copy of %s', $from->getTitle()));
        $this->em->persist($scholarship);
        $this->em->flush($scholarship);

        $this->copyScholarshipRequirements($from, $scholarship);

        $this->em->flush();

        return $scholarship;
    }

    /**
     * @param Scholarship $from
     *
     * @return Scholarship
     */
    public function recur(Scholarship $from)
    {
        if (!$from->getIsRecurrent()) {
            throw new \InvalidArgumentException('Scholarship should be recurring!');
        }

        \Event::dispatch(new ScholarshipBeforeRecurredEvent($from));

        $scholarship = $this->copy($from);
        $scholarship->setTitle($from->getTitle());
        $scholarship->setParentScholarship($from);
        $interval = $scholarship->getRecurringInterval();
        $timezone = new \DateTimeZone($scholarship->getTimezone());

        $startDate = $scholarship->isRecurrenceStartNow() ?
            Carbon::now($timezone) : Carbon::instance($scholarship->getStartDate())->add($interval);

        $expirationDate = $scholarship->isRecurrenceEndMonth() ?
            $this->getEndOfMonth(Carbon::instance($scholarship->getExpirationDate())->startOfMonth()->add($interval)) :
            Carbon::instance($scholarship->getExpirationDate())->add($interval);

        $scholarship->setStartDate($startDate);
        $scholarship->setExpirationDate($expirationDate);

        if ($scholarship->isRecurrenceStartNow()) {
            $scholarship->setStatus(ScholarshipStatus::PUBLISHED);
        }

        $this->updateCurrentScholarship($from, $scholarship);
        $from->setCurrentScholarship($scholarship);

        $this->em->flush();

        \Event::dispatch(new ScholarshipRecurredEvent($from, $scholarship));

        Mail::send(new RecurrentScholarshipNotify(['scholarship' => serialize($scholarship)]));

        return $scholarship;
    }

    /**
     * Should run each 10 minutes to deactivate scholarships.
     *
     * @param \DateTime|null $date
     *
     * @return array
     */
    public function maintain(\DateTime $date = null)
    {
        $date = $date ?: new \DateTime();
        $format = DateHelper::DEFAULT_FORMAT;
        $deactivated = 0;
        $activated = 0;
        $recurred = 0;

        $query = $this->repository->createQueryBuilder('s')
            ->where('s.status != :expired')
            ->andWhere('s.applicationType != :applicationType')
            ->setParameter('expired', ScholarshipStatus::EXPIRED)
            ->setParameter('applicationType', Scholarship::APPLICATION_TYPE_SUNRISE)
            ->getQuery();

        /** @var Scholarship[] $scholarships */
        foreach (QueryIterator::create($query) as $scholarships) {
            foreach ($scholarships as $scholarship) {
                $timezone = $scholarship->getTimezoneObj();

                $now = clone $date;
                $now = new \DateTimeImmutable($now->setTimezone($timezone)->format($format), $timezone);
                $startAt = new \DateTimeImmutable($scholarship->getStartDate()->format($format), $timezone);
                $expireAt = new \DateTimeImmutable($scholarship->getExpirationDate()->format($format), $timezone);

                if ($now >= $expireAt) {
                    if ($scholarship->isPublished()) {
                        $scholarship->setStatus(ScholarshipStatus::EXPIRED);
                        \Log::info(sprintf(
                            'Scholarship %s was deactivated at %s. Expire date: %s',
                            $scholarship->getScholarshipId(),
                            $now->format(DateHelper::FULL_DATE_FORMAT),
                            $expireAt->format(DateHelper::FULL_DATE_FORMAT)
                        ));
                        \Event::dispatch(new ScholarshipExpiredEvent($scholarship));

                        $deactivated++;

                        if ($scholarship->isRecurrent() && !$scholarship->getCurrentScholarship()) {
                            $this->recur($scholarship);
                            $recurred++;
                        }
                    }
                } else if ($scholarship->isUnpublished() && $now >= $startAt) {
                    $scholarship->setStatus(ScholarshipStatus::PUBLISHED);
                    \Log::info(sprintf(
                        'Scholarship %s was activated at %s. Start date: %s',
                        $scholarship->getScholarshipId(),
                        $now->format(DateHelper::FULL_DATE_FORMAT),
                        $startAt->format(DateHelper::FULL_DATE_FORMAT)
                    ));
                    \Event::dispatch(new ScholarshipPublishedEvent($scholarship));

                    $activated++;
                }

                $this->em->flush($scholarship);
            }

            $this->em->flush();
            $this->em->clear();
        }

        return [$activated, $deactivated, $recurred];
    }

    /**
     * @param Scholarship $from
     * @param Scholarship $to
     *
     * @return mixed
     */
    protected function updateCurrentScholarship(Scholarship $from, Scholarship $to)
    {
        /** @var Scholarship $scholarship */
        foreach ($this->repository->findBy(['currentScholarship' => $from]) as $scholarship) {
            $scholarship->setCurrentScholarship($to);
        }
    }

    /**
     * @param Scholarship $from
     * @param Scholarship $scholarship
     *
     * @return array
     */
    protected function copyScholarshipRequirements(Scholarship $from, Scholarship $scholarship)
    {
        $copiedRequirements = [];

        foreach ($from->getRequirements() as $fromRequirement) {
            $this->em->persist($requirement = clone $fromRequirement);

            if ($from->isRecurrent()) {
                foreach ($this->repository->findApplicationRequirements($from, $fromRequirement) as $item) {
                    $applicationRequirement = clone $item;
                    $applicationRequirement->setRequirement($requirement);
                    $applicationRequirement->setScholarship($scholarship);
                    $this->em->persist($applicationRequirement);
                }
            }

            $scholarship->addRequirement($requirement);
            $copiedRequirements[$requirement->getType()][$fromRequirement->getId()] = $requirement;
        }

        $this->em->flush();

        $this->copyScholarshipFormValues($scholarship, $copiedRequirements);

        return $copiedRequirements;
    }

    /**
     * @param Scholarship $from
     * @param Scholarship $to
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function copySunriseApplicationRequirements(Scholarship $from, Scholarship $to)
    {
        $currentRequirements = $to->getRequirements();
        $currentRequirementsIndexed = [];
        /** @var RequirementContract $cr */
        foreach ($currentRequirements as $cr) {
            $currentRequirementsIndexed[$cr->getExternalIdPermanent()] = $cr;
        }

        /** @var RequirementContract $prevRequirement */
        foreach ($from->getRequirements() as $prevRequirement) {
            $prevApplicationRequirements = $this->repository->findSunriseApplicationRequirements($from, $prevRequirement);

            /** @var ApplicationRequirementContract $prevApplicationRequirement */
            foreach ($prevApplicationRequirements as $prevApplicationRequirement) {
                /** @var RequirementContract $cr */
                $cr = $currentRequirementsIndexed[$prevRequirement->getExternalIdPermanent()];
                $isAlreadyCopied = (bool)\EntityManager::getRepository(get_class($prevApplicationRequirement))->findOneBy([
                    'scholarship' => $to,
                    'requirement' => $cr,
                    'account' => $prevApplicationRequirement->getAccount()
                ]);

                if ($isAlreadyCopied) {
                    continue;
                }

                $copiedApplicationRequirement = clone $prevApplicationRequirement;
                $copiedApplicationRequirement->setRequirement($currentRequirementsIndexed[$prevRequirement->getExternalIdPermanent()]);
                $copiedApplicationRequirement->setScholarship($to);
                $this->em->persist($copiedApplicationRequirement);
                $this->em->flush($copiedApplicationRequirement);
            }
        }
    }

    /**
     * @param Scholarship $scholarship
     * @param array       $copiedRequirements
     */
    protected function copyScholarshipFormValues(Scholarship $scholarship, array $copiedRequirements)
    {
        foreach ($scholarship->getForms() as $form) {
            switch ($form->getSystemField()) {
                case Form::REQUIREMENT_UPLOAD_TEXT:
                case Form::TEXT:
                    /** @var RequirementText $requirement */
                    if ($requirement = $copiedRequirements[RequirementText::TYPE][$form->getValue()] ?? null) {
                        $form->setValue($requirement->getId());
                    }
                    break;
                case Form::REQUIREMENT_UPLOAD_FILE:
                    /** @var RequirementFile $requirement */
                    if ($requirement = $copiedRequirements[RequirementFile::TYPE][$form->getValue()] ?? null) {
                        $form->setValue($requirement->getId());
                    }
                    break;
                case Form::REQUIREMENT_UPLOAD_IMAGE:
                    /** @var RequirementImage $requirement */
                    if ($requirement = $copiedRequirements[RequirementImage::TYPE][$form->getValue()] ?? null) {
                        $form->setValue($requirement->getId());
                    }
                    break;
                case Form::INPUT:
                    /** @var RequirementInput $requirement */
                    if ($requirement = $copiedRequirements[RequirementInput::TYPE][$form->getValue()] ?? null) {
                        $form->setValue($requirement->getId());
                    }
                    break;
                default:
                    break;
            }
        }

        $this->em->flush();
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return Carbon
     */
    protected function getEndOfMonth(\DateTime $dateTime)
    {
        return Carbon::instance($dateTime)->endOfMonth();
    }


    /**
     * Retrun list of  favorite users scholarship
     * @param Account $account
     *
     * @return array
     */
    public function getFavoritesScholarship(Account $account){

        $favoriteRepo = \EntityManager::getRepository(AccountsFavoriteScholarships::class);

        /**
         * @var AccountsFavoriteScholarships[] $favorites
         */
        $favorites = $favoriteRepo->findBy(['accountId' => $account->getAccountId(),'favorite' => ScholarshipRestController::FAVORITE_STATUS]);
        $favoriteScholarshipIds = [];
        foreach ($favorites as $favorite){
            $favoriteScholarshipIds[] = $favorite->getScholarship()->getScholarshipId();
        }

        return $favoriteScholarshipIds;
    }

    /**
     * Delete scholarship and its relations
     *
     * @param int $id Scholarship id
     * @throws \Exception
     */
    public function  deleteScholarship(int $id)
    {
        $connection = \DB::connection();
        $connection->beginTransaction();

        // order does matter!
		try {
			\DB::table('application')->where('scholarship_id', [$id])->delete();
			\DB::table('application_essay')
                ->whereIn('essay_id', function(Builder $query) use ($id) {
                    $query->select('essay_id')
                      ->from('essay')
                      ->whereIn('scholarship_id', [$id]);
            })->delete();
			\DB::table('essay')->whereIn('scholarship_id', [$id])->delete();
			\DB::table('form')->whereIn('scholarship_id', [$id])->delete();
			\DB::table('eligibility')->whereIn('scholarship_id', [$id])->delete();
			\DB::table('application_text')->whereIn('scholarship_id', [$id])->delete();
			\DB::table('application_image')->whereIn('scholarship_id', [$id])->delete();
			\DB::table('application_file')->whereIn('scholarship_id', [$id])->delete();
			\DB::table('application_survey')->whereIn('scholarship_id', [$id])->delete();
			\DB::table('requirement_text')->whereIn('scholarship_id', [$id])->delete();
			\DB::table('requirement_image')->whereIn('scholarship_id', [$id])->delete();
			\DB::table('requirement_file')->whereIn('scholarship_id', [$id])->delete();
			\DB::table('requirement_survey')->whereIn('scholarship_id', [$id])->delete();
			\DB::table('scholarship')->whereIn('scholarship_id', [$id])->delete();

            $connection->commit();

            \Event::dispatch(new ScholarshipDeletedEvent($id));
        }
		catch(\Exception $e) {
			$connection->rollback();
			throw $e;
		}
	}

    /**
     * @param int $externalScholarshipId
     * @throws \Exception
     */
	public function  deleteScholarshipByExternalId(int $externalScholarshipId)
    {
         /** @var Scholarship $scholarship */
         $scholarship = $this->repository->findOneBy(['externalScholarshipId' => $externalScholarshipId]);

         if ($scholarship) {
             $this->deleteScholarship($scholarship->getScholarshipId());
         }
    }
}
