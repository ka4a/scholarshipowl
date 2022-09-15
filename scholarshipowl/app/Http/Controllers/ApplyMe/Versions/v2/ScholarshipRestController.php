<?php

namespace App\Http\Controllers\ApplyMe\Versions\v2;

use App\Entity\Account;
use App\Entity\AccountsFavoriteScholarships;
use App\Entity\Exception\EntityNotFound;
use App\Entity\Application;
use App\Entity\ApplicationEssayStatus;
use App\Entity\ApplicationStatus;
use App\Entity\Repository\ApplicationRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\RequirementInput;
use App\Entity\Resource\ScholarshipResource;
use App\Entity\Scholarship;
use App\Http\Controllers\RestController;
use App\Services\ApplicationService\Exception\ScholarshipNotActive;
use App\Services\ApplicationService\Exception\ScholarshipNotEligible;
use App\Services\ApplicationService;
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

        $eligible = $this->getRepository()->findEligibleNotAppliedScholarshipsIds($account);
        $eligibleScholarships = array_slice($eligible, $start, $limit);

        /** @var Scholarship[] $scholarships */
        $scholarships = $this->getBaseIndexQueryBuilderChain($request)
            ->process($this->getRepository()->withAccountApplications($eligibleScholarships, $account))
            ->andWhere('rit.id IS NULL')
            ->getQuery()
            ->getResult();

        $favoriteScholarships = $this->ss->getFavoritesScholarship($account);
        $statuses = $this->getRepository()->getScholarshipStatus($scholarships, $account);
        foreach ($scholarships as $scholarship) {
            $scholarship->nl2br();
            if (isset($statuses[$scholarship->getScholarshipId()])) {
                $scholarship->setApplicationStatus($statuses[$scholarship->getScholarshipId()]['status']);
            }

            if(in_array($scholarship->getScholarshipId(), $favoriteScholarships)){
                $scholarship->setFavorite();
            }
        }

        return $this->jsonResponse($scholarships, [
            'count' => count($scholarships),
            'start' => $start,
            'limit' => $limit
        ], new ScholarshipResource($account));
    }
}
