<?php namespace App\Http\Controllers\Rest\ScholarshipWinnerController;

use App\Events\ScholarshipWinnerPublished;
use Pz\Doctrine\Rest\Resource\Item;
use Pz\Doctrine\Rest\Contracts\RestRequestContract;
use Pz\Doctrine\Rest\RestResponse;

class UpdateAction extends \Pz\LaravelDoctrine\Rest\Action\UpdateAction
{
    /**
     * @param RestRequestContract $request
     *
     * @return RestResponse
     */
    public function handle($request)
    {
        $entity = $this->repository()->findById($request->getId());

        $this->authorize($request, $entity);
        $this->hydrateEntity($entity, $request->getData());
        $this->validateEntity($entity);
        $this->repository()->getEntityManager()->flush();

        ScholarshipWinnerPublished::dispatch($this);

        return $this->response()->resource($request,
            new Item($entity, $this->transformer())
        );
    }
}
