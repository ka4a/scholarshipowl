<?php

namespace App\Http\Controllers\Rest;

use App\Entity\Account;
use App\Entity\AccountsFavoriteScholarships;
use App\Entity\Exception\EntityNotFound;
use App\Entity\Application;
use App\Entity\ApplicationEssayStatus;
use App\Entity\ApplicationStatus;
use App\Entity\Repository\ApplicationRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Resource\ScholarshipResource;
use App\Entity\Scholarship;
use App\Http\Controllers\RestController;
use App\Services\ApplicationService\Exception\ScholarshipNotActive;
use App\Services\ApplicationService\Exception\ScholarshipNotEligible;
use App\Services\ApplicationService;
use App\Services\EligibilityCacheService;
use App\Services\EligibilityService;
use App\Services\ScholarshipService;
use Carbon\Carbon;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Http\Request;

class ScholarshipRestController extends RestController
{
    const PARAM_ELIGIBLE_ACCOUNT = 'accountId';

    const START = 0;
    const LIMIT = 1000;

    const FAVORITE_STATUS = 1;
    const UNFAVORITE_STATUS = 0;

    const APPLICATION_ORDER_LIST = [
        'won',
        'awarded',
        'choosing winner',
        'winner chosen',
        'accepted',
        'declined',
        'missed',
        'draw closed',
        'sent',
    ];

    /**
     * @var EligibilityService
     */
    protected $es;

    /**
     * @var ScholarshipService $ss
     */
    protected $ss;
    /**
     * ScholarshipRestController constructor.
     *
     * @param EntityManager      $em
     * @param EligibilityService $es
     * @param ScholarshipService $ss
     */

    public function __construct(EntityManager $em, EligibilityService $es, ScholarshipService $ss)
    {
        parent::__construct($em);
        $this->es = $es;
        $this->ss = $ss;
    }

    /**
     * @return ScholarshipRepository
     */
    protected function getRepository()
    {
        return \EntityManager::getRepository(Scholarship::class);
    }

    /**
     * @return ScholarshipResource
     */
    protected function getResource()
    {
        return new ScholarshipResource();
    }

    /**
     * @param Request $request
     *
     * @return QueryBuilder
     */
    protected function getBaseIndexQuery(Request $request)
    {
        $queryBuilder = $this->getRepository()->createQueryBuilder('s');
        return $queryBuilder;

    }

    /**
     * @param Request $request
     *
     * @return QueryBuilder
     */
    protected function getBaseIndexCountQuery(Request $request)
    {
        return $this->getBaseIndexQuery($request)->select('COUNT(s.scholarshipId)');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function eligible(Request $request)
    {
        $account = $this->validateAccount($request);

        $this->authorize('show', Scholarship::class);

        $start = $request->input('start', ScholarshipRestController::START);
        $limit = $request->input('limit', ScholarshipRestController::LIMIT);

        /** @var EligibilityCacheService $elbCacheService */
        $elbCacheService = app()->get(EligibilityCacheService::class);
        $accountId = $account->getAccountId();
        $eligibleIds = $elbCacheService->getAccountEligibleScholarshipIds($accountId);

        $paginatedEligibleIds = array_slice($eligibleIds, $start, $limit);

        /** @var Scholarship[] $scholarships */
        $scholarships = $this->getBaseIndexQueryBuilderChain($request)
            ->process($this->getRepository()->withAccountApplications($paginatedEligibleIds, $account))
            ->getQuery()
            ->getResult();

        $favoriteScholarships = $this->ss->getFavoritesScholarship($account);
        $statuses = $this->getRepository()->getScholarshipStatus($scholarships, $account);

        foreach ($scholarships as $scholarship) {
            $scholarshipId = $scholarship->getScholarshipId();
            $scholarship->nl2br();
            if (isset($statuses[$scholarshipId])) {
                $scholarship->setApplicationStatus($statuses[$scholarshipId]['status']);
                $scholarship->setDerivedStatus($statuses[$scholarshipId]['derivedStatus']);
            }

            if (in_array($scholarshipId, $favoriteScholarships)){
                $scholarship->setFavorite();
            }
        }

        return $this->jsonResponse($scholarships, [
            'count' => count($eligibleIds),
            'start' => $start,
            'limit' => $limit
        ], new ScholarshipResource($account));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function eligibleBase(Request $request)
    {
        $this->validate($request, [
            'birth_date'    => 'required_without:birth_day,birth_month,birth_year|date_format:m-d-Y',
            'birth_day'     => 'required_without:birth_date',
            'birth_month'   => 'required_without:birth_date',
            'birth_year'    => 'required_without:birth_date',
            'gender'        => 'required',
            'degree'        => 'required|exists:App\Entity\Degree,id',
            'schoolLevel'   => 'required|exists:App\Entity\SchoolLevel,id',
        ]);

        $this->setRegistrationData([
            'birthday_date'     => $request->get('birth_date'),
            'birthday_day'      => $request->get('birth_day'),
            'birthday_month'    => $request->get('birth_month'),
            'birthday_year'     => $request->get('birth_year'),
            'gender'            => $request->get('gender'),
            'degree_id'         => $request->get('degree'),
            'school_level_id'   => $request->get('schoolLevel'),
        ]);

        $birthDate = $request->has('birth_date') ?
            Carbon::createFromFormat('m-d-Y', $request->get('birth_date')) :
            Carbon::createFromDate(
                $request->get('birth_year'),
                $request->get('birth_month'),
                $request->get('birth_day')
            );

        $ids = $this->es->getBasicEligibilityScholarshipIds(
            $request->get('gender'),
            $request->get('degree'),
            $request->get('schoolLevel'),
            $birthDate->age
        );

        return $this->jsonDataResponse([
            'count'     => count($ids),
            'amount'    => '$'.number_format($this->getRepository()->sumEligibleScholarships($ids)),
        ]);
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sentApplication(Request $request)
    {
        $account = $this->validateAccount($request);

        $this->authorize('show', Scholarship::class);

        $start = $request->input('start', ScholarshipRestController::START);
        $limit = $request->input('limit', ScholarshipRestController::LIMIT);

        /**
         * @var ApplicationRepository $applicationRepository
         */
        $applicationRepository = $this->em->getRepository(Application::class);

        $applications = $applicationRepository->getApplicationsByStatuses($account->getAccountId(), [
            ApplicationStatus::IN_PROGRESS,
            ApplicationStatus::PENDING,
            ApplicationStatus::SUCCESS
        ]);
        
        $scholarships = [];
        if (!empty($applications)) {
            /** @var ScholarshipRepository $sc */
            $sc = $this->em->getRepository(Scholarship::class);
            $scholarships = $sc->withAccountApplications(array_keys($applications), $account)->getQuery()->getResult();
            $statuses = $this->getRepository()->getScholarshipStatus($scholarships, $account);
            /**
             * @var Scholarship $scholarship
             */
            foreach ($scholarships as $scholarship) {
                $scholarshipId = $scholarship->getScholarshipId();
                $scholarship->setIsSent();
                $scholarship->nl2br();
                if (isset($statuses[$scholarshipId])) {
                    $scholarship->setApplicationStatus($statuses[$scholarshipId]['status']);
                    $scholarship->setDerivedStatus($statuses[$scholarshipId]['derivedStatus']);
                }
            }

            $scholarships = $this->sortingApplicationList($scholarships);

        }

        return $this->jsonResponse($scholarships, [
            'start' => $start,
            'limit' => $limit
        ], new ScholarshipResource($account));
    }

    /**
     * @param Account $account
     * @param integer $scholarshipId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeFavoriteAction(Account $account, $scholarshipId){
        return $this->switchScholarshipsFavoriteStatus($account, $scholarshipId, self::FAVORITE_STATUS);
    }

    /**
     * @param Account $account
     * @param integer $scholarshipId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeUnfavoriteAction(Account $account, $scholarshipId){
        return $this->switchScholarshipsFavoriteStatus($account, $scholarshipId, self::UNFAVORITE_STATUS);
    }

    /**
     * @param Account $account
     * @param integer $scholarshipId
     * @param integer $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function switchScholarshipsFavoriteStatus(Account $account, $scholarshipId, $status) {
        $response = $this->jsonSuccessResponse([]);
        try {
            /**
             * @var Scholarship $scholarship
             */
            $scholarship = $this->getRepository()->findById($scholarshipId);

            if (!$scholarship->isPublished() || !$scholarship->isActive() || $scholarship->isExpired()) {
                throw new ScholarshipNotActive(sprintf("Can't make favorite scholarship - scholarship not active or expired"));
            }

            if (!$this->es->isEligible($account, $scholarship)) {
                throw new ScholarshipNotEligible(sprintf("Scholarship not eligible for the user"));
            }

            $favoriteRepo = $this->em->getRepository(AccountsFavoriteScholarships::class);

            /**
             * @var AccountsFavoriteScholarships $favoriteRecord
             */
            $favoriteRecord = $favoriteRepo->findOneBy([
                'accountId' => $account->getAccountId(),
                'scholarship' => $scholarshipId
            ]);

            if (is_null($favoriteRecord)) {
                $favoriteRecord = new AccountsFavoriteScholarships($account, $scholarship);
                $favoriteRecord->setFavorite($status);
                $this->em->persist($favoriteRecord);
            } else if ($status == self::UNFAVORITE_STATUS) {
                $this->em->remove($favoriteRecord);
            }

            $this->em->flush();
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if ($e instanceof UniqueConstraintViolationException) {
                // do nothing, favorite record already exists
            }
            if ($e instanceof EntityNotFound) {
                $message = "Scholarship [ $scholarshipId ] does not exist";
                $response = $this->jsonErrorResponse($message, 404);
            }
        }

        return $response;
    }

    /**
     * @param $scholarships
     * @param array $oderList
     * @return mixed
     */
    public function sortingApplicationList($scholarships, $oderList = [])
    {
        $sortOrder = empty($oderList) ? self::APPLICATION_ORDER_LIST : $oderList;

        usort($scholarships, function ($a, $b) use ($sortOrder) {
            $aKey = array_search(strtolower($a->getDerivedStatus()), $sortOrder);
            $bKey = array_search(strtolower($b->getDerivedStatus()), $sortOrder);

            if ($aKey === false) {
                return 1;
            } elseif ($bKey === false) {
                return -1;
            }
            if ($aKey == $bKey) {
                return 0;
            }
            return ($aKey < $bKey) ? -1 : 1;
        });

        return $scholarships;
    }
}


