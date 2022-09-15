<?php namespace App\Http\Controllers\Rest;

use App\Entities\Application;
use App\Entities\ApplicationStatus;
use App\Entities\ApplicationWinner;
use App\Entities\User;
use App\Http\Controllers\Rest\ApplicationController\ApplicationCreateAction;
use App\Http\Controllers\Rest\ApplicationController\ApplicationRelatedWinnerUpdateAction;
use App\Http\Controllers\Rest\ApplicationController\ApplicationRelatedWinnerUpdateRequest;
use App\Http\Controllers\Rest\ApplicationController\ApplicationUpdateAction;
use App\Http\Controllers\RestController;
use App\Http\Controllers\Rest\ApplicationController\ApplicationCreateRequest;
use App\Http\Requests\RestRequest;
use App\Transformers\ApplicationTransformer;
use App\Transformers\ApplicationTransformerOld;
use App\Transformers\ApplicationWinnerTransformer;
use Doctrine\ORM\EntityManager;
use Pz\Doctrine\Rest\Action\Related\RelatedItemAction;
use Pz\Doctrine\Rest\RestRepository;

class ApplicationController extends RestController
{
    /**
     * @var RestRepository
     */
    protected $winners;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = RestRepository::create($em, Application::class);
        $this->winners = RestRepository::create($em, ApplicationWinner::class);
        $this->transformer = new ApplicationTransformer();
    }

    /**
     * @return array
     */
    public function getFilterable()
    {
        return ['email', 'scholarship'];
    }

    /**
     * Update scholarship status after review.
     *
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function show(RestRequest $request)
    {
        /** @var Application $application */
        $application = $this->repository()->findById($request->getId());
        if ($request->user() instanceof User && $application->getStatus()->is(ApplicationStatus::RECEIVED)) {
            $application->setStatus(ApplicationStatus::REVIEW);
            $this->repository()->getEntityManager()->flush($application);
        }

        return parent::show($request);
    }

    /**
     * @param ApplicationCreateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function apply(ApplicationCreateRequest $request)
    {
        return (new ApplicationCreateAction($this->repository(), new ApplicationTransformerOld()))->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function update(RestRequest $request)
    {
        return (new ApplicationUpdateAction($this->repository(), $this->transformer()))->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedWinner(RestRequest $request)
    {
        return (new RelatedItemAction(
            $this->repository(), 'winner', $this->winners, new ApplicationWinnerTransformer()
        ))->dispatch($request);
    }

    /**
     * @param ApplicationRelatedWinnerUpdateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedWinnerUpdate(ApplicationRelatedWinnerUpdateRequest $request)
    {
        return (new ApplicationRelatedWinnerUpdateAction(
            $this->repository(), new ApplicationWinnerTransformer()
        ))->dispatch($request);
    }
}
