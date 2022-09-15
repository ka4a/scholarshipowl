<?php namespace App\Http\Controllers\Rest\ApplicationController;

use App\Doctrine\Extensions\UploadedFileInfo;
use App\Entities\ApplicationFile;
use League\Fractal\Resource\Item;
use Pz\Doctrine\Rest\RestAction;
use Pz\Doctrine\Rest\RestRequest;
use Pz\Doctrine\Rest\RestResponse;
use Pz\Doctrine\Rest\Traits\CanHydrate;
use Pz\Doctrine\Rest\Traits\CanValidate;
use Pz\LaravelDoctrine\Rest\Action\CreateAction;

class ApplicationFileCreateAction extends CreateAction
{
    use CanHydrate;
    use CanValidate;

    /**
     * @param ApplicationFileCreateRequest|RestRequest $request
     * @return RestResponse
     */
    public function handle($request)
    {
        $this->authorize($request, ApplicationFile::class);

        $file = ApplicationFile::uploaded($request->file('file'));
        $this->hydrateEntity($file, $request->getData());
        $this->validateEntity($file);

        $this->repository()->getEntityManager()->persist($file);
        $this->repository()->getEntityManager()->flush($file);

        $resource = new Item($file, $this->transformer());
        return $this->response()->resource($request, $resource, RestResponse::HTTP_CREATED);
    }
}
