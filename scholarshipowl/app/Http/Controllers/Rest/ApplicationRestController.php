<?php namespace App\Http\Controllers\Rest;

use App\Entity\{Account, AccountType, Counter, Repository\ScholarshipRepository, Resource\ScholarshipResource};
use App\Entity\Application;
use App\Entity\Resource\ApplicationResource;
use App\Entity\Scholarship;
use App\Http\Controllers\RestController;
use App\Services\ApplicationService;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ApplicationRestController extends RestController
{
    /**
     * @var ApplicationService
     */
    protected $applicationService;

    /**
     * ApplicationRestController constructor.
     *
     * @param EntityManager $em
     * @param ApplicationService $applicationService
     */
    public function __construct(EntityManager $em, ApplicationService $applicationService)
    {
        parent::__construct($em);
        $this->applicationService = $applicationService;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return \EntityManager::getRepository(Application::class);
    }

    /**
     * @return ApplicationResource
     */
    protected function getResource()
    {
        return new ApplicationResource();
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getBaseIndexQuery(Request $request)
    {
        $queryBuilder = $this->getRepository()->createQueryBuilder('a');

        $account = $this->getAuthenticatedAccount();
        if ($account !== null) {
            $queryBuilder->where('a.account = :account');
            $queryBuilder->setParameter('account', $account);
        }

        return $queryBuilder;
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getBaseIndexCountQuery(Request $request)
    {
        $queryBuilder = $this->getBaseIndexQuery($request);
        return $queryBuilder->select($queryBuilder->expr()->count('a.account'));
    }

    /**
     * @param $id
     * @param int $accountId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showApplication($id, int $accountId = null)
    {
        return $this->show([
            'account' => $accountId ?: $this->getAuthenticatedAccount(),
            'scholarship' => $id,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ApplicationService\Exception\ApplicationSubscriptionNotFound
     * @throws ApplicationService\Exception\ScholarshipNotEligible
     */
    public function store(Request $request)
    {
        $account = $this->validateAccount($request);
        $this->authorize('store', Application::class);
        $this->validate($request, ['scholarshipId' => 'required|entity:Scholarship']);

        try {
            /** @var Scholarship $scholarship */
            $scholarship = $this->findById($request->get('scholarshipId'), Scholarship::class);
            $application = $this->apply($account, $scholarship);

        } catch (ApplicationService\Exception\ApplicationSubscriptionNotFound $e) {
            return $this->jsonErrorResponse($e->getMessage(), JsonResponse::HTTP_CONFLICT);
        } catch (ApplicationService\Exception\ApplicationException $e) {
            return $this->jsonErrorResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }
        $scholarship = $application->getScholarship();
        $scholarship->setApplications(new ArrayCollection([$application]));
        /** @var ScholarshipRepository $repo */
        $repo = \EntityManager::getRepository(Scholarship::class);
        $statuses = $repo->getScholarshipStatus([$scholarship], $account);
        $scholarship->setDerivedStatus($statuses[$scholarship->getScholarshipId()]['derivedStatus']);

        return $this->jsonResponse($scholarship, ['credits' => $account->getCredits()],  new ScholarshipResource($account));
    }

    /**
     * @param  $account
     * @param  $scholarship
     * @return Application
     */
    protected function apply($account, $scholarship)
    {
        return $this->applicationService->applyScholarship($account, $scholarship);
    }

    /**
     * @return Integer
     */
    protected function count(Request $request)
    {
        if (\Cache::tags(["counter"])->has("application")) {
            $counter = \Cache::tags(["counter"])->get("application");
        } else {
            $counter = Counter::findByName("application")->getCount();
        }

        $response = \Response::make($counter);

        if ($time = $request->get('t')) {
            $response->setPublic()
                ->setExpires(Carbon::createFromTimestampUTC($time)->addDay())
                ->setMaxAge(3600 * 24)
                ->setSharedMaxAge(3600 * 24);
        }

        return $response;
    }
}
