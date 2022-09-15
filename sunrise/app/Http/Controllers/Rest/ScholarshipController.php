<?php namespace App\Http\Controllers\Rest;

use App\Entities\Application;
use App\Entities\ApplicationWinner;
use App\Entities\Scholarship;
use App\Entities\ScholarshipField;
use App\Http\Controllers\Rest\ScholarshipController\ScholarshipApplyRequest;
use App\Http\Controllers\Rest\ScholarshipController\ScholarshipCollectionAction;
use App\Http\Controllers\Rest\ScholarshipController\ScholarshipEligibleAction;
use App\Http\Controllers\Rest\ScholarshipController\ScholarshipEligibleRequest;
use App\Http\Controllers\Rest\ScholarshipController\ScholarshipRelatedApplicationExport;
use App\Http\Controllers\Rest\ScholarshipController\ScholarshipRelatedApplicationsAction;
use App\Http\Controllers\Rest\ScholarshipController\ScholarshipRelatedWinnersAction;
use App\Http\Controllers\RestController;
use App\Jobs\BatchScholarshipApply;
use App\Services\ApplicationService;
use App\Services\ScholarshipManager;
use App\Transformers\ApplicationTransformer;
use App\Transformers\ApplicationTransformerOld;
use App\Transformers\ApplicationWinnerTransformer;
use App\Transformers\ScholarshipFieldTransformer;
use App\Transformers\ScholarshipTransformer;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Response;
use League\Fractal\Resource\Item;
use Pz\Doctrine\Rest\Action\ItemAction;
use Pz\Doctrine\Rest\Action\Related\RelatedCollectionAction;
use Pz\Doctrine\Rest\Exceptions\RestException;
use Pz\Doctrine\Rest\Resource\Collection;
use Pz\Doctrine\Rest\RestRepository;
use Pz\Doctrine\Rest\RestResponse;
use Pz\Doctrine\Rest\RestResponseFactory;
use App\Http\Requests\RestRequest;

class ScholarshipController extends RestController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ApplicationService
     */
    protected $as;

    /**
     * @var ScholarshipManager
     */
    protected $sm;

    /**
     * ScholarshipController constructor.
     * @param EntityManager $em
     * @param ApplicationService $as
     * @param ScholarshipManager $sm
     */
    public function __construct(EntityManager $em, ApplicationService $as, ScholarshipManager $sm)
    {
        $this->em = $em;
        $this->repository = RestRepository::create($em, Scholarship::class);
        $this->transformer = new ScholarshipTransformer();
        $this->as = $as;
        $this->sm = $sm;
    }

    /**
     * @return RestRepository
     */
    public function applications()
    {
        return RestRepository::create($this->em, Application::class);
    }

    /**
     * @return array
     */
    public function getFilterable()
    {
        return ['id'];
    }

    /**
     * @param RestRequest $request
     *
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function index(RestRequest $request)
    {
        return (new ScholarshipCollectionAction($this->repository(), $this->transformer()))
            ->setFilterProperty($this->getFilterProperty())
            ->setFilterable($this->getFilterable())
            ->dispatch($request);
    }

    /**
     * @param RestRequest $request
     *
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function show(RestRequest $request)
    {
        return (new ItemAction($this->repository(), $this->transformer()))->dispatch($request);
    }

    /**
     * Receive student application fields and return list of scholarships that student eligible for.
     *
     * @param ScholarshipEligibleRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function eligible(ScholarshipEligibleRequest $request)
    {
        return (new ScholarshipEligibleAction($this->repository(), $this->transformer()))
            ->setFilterProperty($this->getFilterProperty())
            ->setFilterable($this->getFilterable())
            ->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return RestResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function republish(RestRequest $request)
    {
        /** @var Scholarship $scholarship */
        $scholarship = $this->repository()->findById($request->getId());

        /** @var Gate $gate */
        $gate = app(Gate::class);
        $gate->authorize('republish', $scholarship);

        $scholarship = $this->sm->republish($scholarship);

        $resource = new Item($scholarship, new ScholarshipTransformer(), $scholarship->getResourceKey());
        return (new RestResponseFactory())->resource($request, $resource, Response::HTTP_OK);
    }

    /**
     * @param ScholarshipApplyRequest $request
     * @return RestResponse
     * @throws RestException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function apply(ScholarshipApplyRequest $request)
    {
        /** @var Scholarship $scholarship */
        $scholarship = $this->repository()->findById($request->getId());

        try {
            $application = $this->as->apply($scholarship, $request->getApplicationFields());
        } catch (ApplicationService\ApplicationServiceException $e) {
            throw RestException::createFromException($e);
        }

        $resource = new Item($application, new ApplicationTransformer(), $application->getResourceKey());
        return (new RestResponseFactory())->resource($request, $resource, Response::HTTP_CREATED);
    }

    /**
     * @param RestRequest $request
     * @return RestResponse
     * @throws RestException
     * @throws \Doctrine\ORM\ORMException
     */
    public function chooseWinners(RestRequest $request)
    {
        /** @var Scholarship $scholarship */
        $scholarship = $this->repository()->findById($request->getId());

        $winners = $this->sm->chooseWinners($scholarship, $request->get('awards'));

        return (new RestResponseFactory())->resource($request,
            new Collection($winners, new ApplicationWinnerTransformer(), ApplicationWinner::getResourceKey())
        );
    }

    /**
     * @param ScholarshipEligibleRequest $request
     * @return RestResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function applyBatch(ScholarshipEligibleRequest $request)
    {
        $fields = $this->as->verifyEligibilityData($request->getData());
        BatchScholarshipApply::dispatch($fields);
        return RestResponse::create();
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedApplications(RestRequest $request)
    {
        return (
            new ScholarshipRelatedApplicationsAction(
                $this->repository(),
                'scholarship',
                $this->applications(),
                new ApplicationTransformer()
            )
        )
            ->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedApplicationsExport(RestRequest $request)
    {
        return (
            new ScholarshipRelatedApplicationExport(
                $this->repository(),
                'scholarship',
                $this->applications(),
                new ApplicationTransformerOld()
            )
        )
            ->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return RestResponse
     */
    public function relatedFields(RestRequest $request)
    {
        return (
            new RelatedCollectionAction($this->repository(), 'scholarship',
                RestRepository::create($this->repository()->getEntityManager(), ScholarshipField::class),
                new ScholarshipFieldTransformer()
            )
        )
            ->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedWinners(RestRequest $request)
    {
        return (
            new ScholarshipRelatedWinnersAction(
                $this->repository(),
                RestRepository::create($this->em, ApplicationWinner::class),
                new ApplicationWinnerTransformer()
            )
        )
            ->dispatch($request);
    }

    /**
     * Get scholarship by domain.
     *
     * @param RestRequest $request
     * @param string $domain
     * @return RestResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function showByDomain(RestRequest $request, $domain)
    {
        $qb = $this->repository()->createQueryBuilder('s');

        /**
         * Check if domain is not hosted. check without and with "www".
         */
        $notHostedDomainExpr = $qb->expr()->andX(
            $qb->expr()->eq('w.domainHosted', '0'),
            $qb->expr()->in('w.domain', [$domain, "www.$domain"])
        );

        /**
         * Check hosted domain contact the hosted domain.
         */
        $hostedDomainExpr = $qb->expr()->andX(
            $qb->expr()->eq('w.domainHosted', '1'),
            $qb->expr()->eq(
                $qb->expr()->concat(
                    'w.domain',
                    $qb->expr()->literal('.'),
                    $qb->expr()->literal(config('services.barn.hosted_domain'))
                ),
                ':domain'
            )
        );

        /** @var Scholarship $scholarship */
        $scholarship = $qb
            ->join('s.template', 't')
            ->join('t.website', 'w')
            ->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->isNull('s.expiredAt'),
                    $qb->expr()->orX(
                        $notHostedDomainExpr,
                        $hostedDomainExpr
                    )
                )
            )
            ->setParameter('domain', $domain)
            ->getQuery()
            ->getOneOrNullResult();

        /**
         * If published scholarship not found. Try to find last unpublished scholarship.
         */
        if (is_null($scholarship)) {
            $qb = $this->repository()->createQueryBuilder('s');
            /** @var Scholarship $scholarship */
            $scholarship = $qb
                ->join('s.template', 't')
                ->join('t.website', 'w')
                ->where(
                    $qb->expr()->orX(
                        $notHostedDomainExpr,
                        $hostedDomainExpr
                    )
                )
                ->addOrderBy('s.expiredAt', 'DESC')
                ->setParameter('domain', $domain)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        }

        if (is_null($scholarship)) {
            $message = 'Scholarship for specified domain not found!';
            throw RestException::create(Response::HTTP_NOT_FOUND, 'Entity not found.')
                ->error('entity-not-found', ['type' => 'scholarship', 'domain' => $domain], $message);
        }

        $resource = new Item($scholarship, $this->transformer(), $scholarship->getResourceKey());

        return (new RestResponseFactory())->resource($request, $resource);
    }
}
