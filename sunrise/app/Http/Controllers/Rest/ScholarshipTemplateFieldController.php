<?php namespace App\Http\Controllers\Rest;

use App\Entities\ScholarshipTemplateField;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Rest\ScholarshipTemplateFieldController\CreateRequest;
use App\Http\Controllers\Rest\ScholarshipTemplateFieldController\UpdateRequest;
use App\Http\Requests\RestRequest;
use App\Transformers\ScholarshipTemplateFieldTransformer;
use Doctrine\ORM\EntityManager;
use Pz\Doctrine\Rest\RestRepository;
use Pz\LaravelDoctrine\Rest\Action\CreateAction;
use Pz\LaravelDoctrine\Rest\Action\DeleteAction;
use Pz\LaravelDoctrine\Rest\Action\UpdateAction;

class ScholarshipTemplateFieldController extends Controller
{
    /**
     * @var RestRepository
     */
    protected $repository;

    /**
     * @var ScholarshipTemplateFieldTransformer
     */
    protected $transformer;

    /**
     * ScholarshipTemplateFieldController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = RestRepository::create($em, ScholarshipTemplateField::class);
        $this->transformer = new ScholarshipTemplateFieldTransformer();
    }

    /**
     * @param CreateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function create(CreateRequest $request)
    {
        return (new CreateAction($this->repository, $this->transformer))->dispatch($request);
    }

    /**
     * @param UpdateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function update(UpdateRequest $request)
    {
        return (new UpdateAction($this->repository, $this->transformer))->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function delete(RestRequest $request)
    {
        return (new DeleteAction($this->repository, $this->transformer))->dispatch($request);
    }
}
