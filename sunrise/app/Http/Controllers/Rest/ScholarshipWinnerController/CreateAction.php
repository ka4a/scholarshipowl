<?php namespace App\Http\Controllers\Rest\ScholarshipWinnerController;

use App\Events\ScholarshipWinnerPublished;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;
use Pz\Doctrine\Rest\Resource\Item;
use Pz\Doctrine\Rest\Contracts\RestRequestContract;
use Pz\Doctrine\Rest\RestResponse;

class CreateAction extends \Pz\LaravelDoctrine\Rest\Action\CreateAction
{
    /**
     * @param RestRequestContract $request
     *
     * @return RestResponse
     */
    protected function handle($request)
    {
        $headers = [];
        $class = $this->repository()->getClassName();

        $this->authorize($request, $class);
        $entity = $this->hydrateEntity($class, $request->getData());
        $this->validateEntity($entity);
        $this->repository()->getEntityManager()->persist($entity);
        $this->repository()->getEntityManager()->flush();

        if ($entity instanceof JsonApiResource) {
            $headers['Location'] = $this->repository()->linkJsonApiResource($request, $entity);
        }

        ScholarshipWinnerPublished::dispatch($entity);

        $resource = new Item($entity, $this->transformer());
        return $this->response()->resource($request, $resource, RestResponse::HTTP_CREATED, $headers);
    }
}
