<?php namespace App\Http\Controllers\Rest;

use App\Entities\ScholarshipWinner;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Rest\ScholarshipWinnerController\ScholarshipWinnerCreateRequest;
use App\Http\Controllers\Rest\ScholarshipWinnerController\CreateAction;
use App\Http\Controllers\Rest\ScholarshipWinnerController\ScholarshipWinnerIndexAction;
use App\Http\Controllers\Rest\ScholarshipWinnerController\UpdateAction;
use App\Http\Requests\RestRequest;
use App\Transformers\ScholarshipWinnerTransformer;
use Doctrine\ORM\EntityManager;
use League\Fractal\TransformerAbstract;
use Pz\Doctrine\Rest\Action\ItemAction;
use Pz\Doctrine\Rest\RestRepository;

class ScholarshipWinnerController extends Controller
{
    /**
     * @var TransformerAbstract
     */
    protected $transformer;

    /**
     * @var RestRepository
     */
    protected $repository;

    /**
     * ScholarshipWinnerController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = RestRepository::create($em, ScholarshipWinner::class);
        $this->transformer = new ScholarshipWinnerTransformer();
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function index(RestRequest $request)
    {
        return (new ScholarshipWinnerIndexAction($this->repository, $this->transformer))
            ->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function show(RestRequest $request)
    {
        return (new ItemAction($this->repository, $this->transformer))
            ->dispatch($request);
    }

    /**
     * @param ScholarshipWinnerCreateRequest $request
     *
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function create(ScholarshipWinnerCreateRequest $request)
    {
        return (new CreateAction($this->repository, $this->transformer))
            ->dispatch($request);
    }

    /**
     * @param ScholarshipWinnerCreateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function update(ScholarshipWinnerCreateRequest $request)
    {
        return (new UpdateAction($this->repository, $this->transformer))
            ->dispatch($request);
    }
}
