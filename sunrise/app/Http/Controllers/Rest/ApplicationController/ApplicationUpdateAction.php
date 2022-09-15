<?php namespace App\Http\Controllers\Rest\ApplicationController;

use App\Entities\Application;
use App\Events\ApplicationStatusChangedEvent;
use Pz\Doctrine\Rest\Contracts\RestRequestContract;
use Pz\Doctrine\Rest\Resource\Item;
use Pz\Doctrine\Rest\RestRequest;
use Pz\Doctrine\Rest\RestResponse;
use Pz\LaravelDoctrine\Rest\Action\UpdateAction;

class ApplicationUpdateAction extends UpdateAction
{
    /**
     * @param RestRequestContract|RestRequest $request
     * @return RestResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function handle($request)
    {
        /** @var Application $application */
        $application = $this->repository()->findById($request->getId());

        $oldStatus = $application->getStatus();

        $this->authorize($request, $application);
        $this->hydrateEntity($application, $request->getData());
        $this->validateEntity($application);
        $this->repository()->getEntityManager()->flush();

        if ($application->getStatus() !== $oldStatus){ 
            ApplicationStatusChangedEvent::dispatch($application);
        }

        return $this->response()->resource($request,
            new Item($application, $this->transformer())
        );
    }
}
