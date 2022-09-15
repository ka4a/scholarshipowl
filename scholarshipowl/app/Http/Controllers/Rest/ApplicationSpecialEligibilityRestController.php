<?php namespace App\Http\Controllers\Rest;

use App\Entity\ApplicationSpecialEligibility;
use App\Entity\Repository\EntityRepository;
use App\Entity\RequirementSpecialEligibility;
use App\Entity\Resource\ApplicationSpecialEligibilityResource;
use App\Entity\Resource\ApplicationTextResource;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManager;
use App\Services\ApplicationService;

class ApplicationSpecialEligibilityRestController extends ApplicationRequirementAbstractController
{

    /**
     * @var ApplicationSpecialEligibilityResource
     */
    protected $resource;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ApplicationService
     */
    protected $applicationService;

    /**
     * ApplicationSpecialEligibilityRestController constructor.
     *
     * @param EntityManager            $em
     * @param ApplicationService $applicationService
     */
    public function __construct(EntityManager $em, ApplicationService $applicationService)
    {
        parent::__construct($em);
        $this->applicationService = $applicationService;
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return \EntityManager::getRepository(ApplicationSpecialEligibility::class);
    }

    /**
     * @return ApplicationSpecialEligibilityResource
     */
    public function getResource()
    {
        if ($this->resource === null) {
            $this->resource = new ApplicationSpecialEligibilityResource();
        }

        return $this->resource;
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBaseIndexQuery(Request $request)
    {
        return $this->getRepository()->createQueryBuilder('at');
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBaseIndexCountQuery(Request $request)
    {
        return $this->getBaseIndexQuery($request)->select('COUNT(at.id)');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->getResource()->setFullScholarship(false);

        return parent::index($request);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize('store', ApplicationSpecialEligibility::class);
        $account = $this->validateAccount($request);

        $this->validate($request, [
            'requirementId' => 'required|entity:RequirementSpecialEligibility',
            'val' => 'required|integer'
        ]);

        /** @var RequirementSpecialEligibility $requirementSpecialEligibility */
        $requirementSpecialEligibility = $this->findById(
            $request->get('requirementId'), RequirementSpecialEligibility::class
        );

        /** @var ApplicationSpecialEligibility $applicationSpecialEligibility */
        $applicationSpecialEligibility = $this->getRepository()->findOneBy([
            'requirement' => $requirementSpecialEligibility,
            'account' => $account
        ]);

        if ($applicationSpecialEligibility) {
            $applicationSpecialEligibility->setVal($request->get('val'));
        } else {
            $applicationSpecialEligibility = new ApplicationSpecialEligibility(
                $requirementSpecialEligibility,
                $account,
                $request->get('val')
            );

            $this->em->persist($applicationSpecialEligibility);
        }

        $this->em->flush();

        return $this->jsonResponse($applicationSpecialEligibility);
    }
}
