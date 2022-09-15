<?php namespace App\Http\Controllers\Rest;

use App\Entities\ApplicationWinner;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Rest\OrganisationController\OrganisationRelatedWinnersAction;
use App\Http\Controllers\Rest\OrganisationController\OrganisationUpdateRequest;
use App\Http\Controllers\Rest\OrganisationController\RelatedScholarshipsCollection;
use App\Http\Controllers\Rest\OrganisationController\RelatedScholarshipsCreateRequest;
use App\Entities\ScholarshipTemplate;
use App\Entities\Organisation;
use App\Transformers\ApplicationWinnerTransformer;
use App\Transformers\ScholarshipTemplateTransformer;
use App\Transformers\OrganisationTransformer;
use Doctrine\ORM\EntityManager;
use Pz\Doctrine\Rest\Action\CollectionAction;
use Pz\Doctrine\Rest\Action\Related\RelatedCollectionCreateAction;
use Pz\Doctrine\Rest\Exceptions\RestException;
use Pz\Doctrine\Rest\Resource\Item;
use Pz\Doctrine\Rest\RestRepository;
use Pz\Doctrine\Rest\RestResponse;
use Pz\Doctrine\Rest\RestResponseFactory;
use Pz\LaravelDoctrine\Rest\Action\ShowAction;
use Pz\LaravelDoctrine\Rest\Action\UpdateAction;
use App\Http\Requests\RestRequest;

class OrganisationController extends Controller
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var RestRepository
     */
    protected $repository;

    /**
     * @var RestRepository
     */
    protected $scholarships;

    /**
     * @var RestRepository
     */
    protected $winners;

    /**
     * @var OrganisationTransformer
     */
    protected $transformer;

    /**
     * OrganisationController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->transformer = new OrganisationTransformer($this->em);
        $this->repository = RestRepository::create($em, Organisation::class);
        $this->scholarships = RestRepository::create($em, ScholarshipTemplate::class);
        $this->winners = RestRepository::create($em, ApplicationWinner::class);
    }

    /**
     * @param RestRequest $request
     * @return RestResponse
     */
    public function show(RestRequest $request)
    {
        return (new ShowAction($this->repository, new OrganisationTransformer($this->em)))->dispatch($request);
    }

    /**
     * @param OrganisationUpdateRequest $request
     * @return RestResponse
     */
    public function update(OrganisationUpdateRequest $request)
    {
        return (new UpdateAction($this->repository, $this->transformer))->dispatch($request);
    }

    /**
     * @param RestRequest $request
     *
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function index(RestRequest $request)
    {
        return (new CollectionAction($this->repository, $this->transformer))
            ->setFilterProperty('title')
            ->setFilterable([])
            ->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     * @throws RestException
     */
    public function me(RestRequest $request)
    {
        $organisation = $request->user('organisation');
        if (!$organisation instanceof Organisation) {
            throw RestException::createForbidden();
        }

        $responseFactory = new RestResponseFactory();
        return $responseFactory->resource($request,
            new Item($organisation, $this->transformer, $organisation->getResourceKey())
        );
    }

    /**
     * @param RestRequest $request
     *
     * @return RestResponse
     */
    public function relatedScholarships(RestRequest $request)
    {
        return (
            new RelatedScholarshipsCollection(
                $this->repository, 'organisation', $this->scholarships,
                new ScholarshipTemplateTransformer()
            )
        )
            ->setFilterProperty('title')
            ->dispatch($request);
    }

    /**
     * @param RelatedScholarshipsCreateRequest $request
     * @return RestResponse
     */
    public function relatedScholarshipsCreate(RelatedScholarshipsCreateRequest $request)
    {
        return (new RelatedCollectionCreateAction(
            $this->repository, 'scholarships', 'organisation', $this->scholarships,
            new ScholarshipTemplateTransformer()
        ))->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return RestResponse
     */
    public function relatedWinners(RestRequest $request)
    {
        return (
            new OrganisationRelatedWinnersAction(
                $this->repository, null, $this->winners, new ApplicationWinnerTransformer()
            )
        )
            ->setFilterable(['disqualifiedAt'])
            ->dispatch($request);
    }
}
