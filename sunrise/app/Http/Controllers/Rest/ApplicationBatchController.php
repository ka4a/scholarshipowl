<?php namespace App\Http\Controllers\Rest;

use App\Entities\Application;
use App\Entities\ApplicationBatch;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Rest\ApplicationBatchController\CreateAction;
use App\Http\Controllers\Rest\ApplicationBatchController\CreateRequest;
use App\Http\Requests\RestRequest;
use App\Services\ApplicationService;
use App\Transformers\ApplicationBatchTransformer;
use App\Transformers\ApplicationTransformer;
use Doctrine\ORM\EntityManager;
use Pz\Doctrine\Rest\Action\Related\RelatedCollectionAction;
use Pz\Doctrine\Rest\RestRepository;
use Pz\LaravelDoctrine\Rest\Action\DeleteAction;
use Pz\LaravelDoctrine\Rest\Action\ShowAction;

class ApplicationBatchController extends Controller
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
     * @var RestRepository
     */
    protected $repository;

    /**
     * ApplicationBatchController constructor.
     * @param EntityManager         $em
     * @param ApplicationService    $as
     */
    public function __construct(EntityManager $em, ApplicationService $as)
    {
        $this->em = $em;
        $this->as = $as;
        $this->repository = RestRepository::create($this->em, ApplicationBatch::class);
    }

    /**
     * @param CreateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function create(CreateRequest $request)
    {
        return (new CreateAction($this->repository, new ApplicationBatchTransformer()))->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function show(RestRequest $request)
    {
        return (new ShowAction($this->repository, new ApplicationBatchTransformer()))->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function delete(RestRequest $request)
    {
        return (new DeleteAction($this->repository, new ApplicationBatchTransformer()))->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedApplications(RestRequest $request)
    {
        return (
            new RelatedCollectionAction(
                $this->repository,
                'batch',
                RestRepository::create($this->em, Application::class),
                new ApplicationTransformer()
            )
        )->dispatch($request);
    }
}
